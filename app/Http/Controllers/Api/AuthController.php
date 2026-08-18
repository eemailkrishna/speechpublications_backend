<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Models\Otp;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Mail;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FileUploadHelper;
use App\Http\Resources\UserResource;


class AuthController extends Controller
{
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'nullable|digits:10',
            'email' => 'nullable|email',
            'country_code' => 'nullable|string',
        ]);

        // At least one of phone_number or email must be provided
        if (empty($validated['phone_number']) && empty($validated['email'])) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Either phone_number or email is required',
                ]
            ], 422);
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otpId = Str::uuid();
        $expiresAt = Carbon::now()->addMinutes(config('app.otp_expiry_minutes', 5));
        $cacheKey = $validated['phone_number'] ? "otp_attempts:{$validated['phone_number']}" : "otp_attempts:{$validated['email']}";
        $attempts = Cache::get($cacheKey, 0);

        // if ($attempts >= 3) {
        //     return response()->json([
        //         'success' => false,
        //         'error' => [
        //             'code' => 'RATE_LIMIT_EXCEEDED',
        //             'message' => 'Too many OTP requests. Try after 1 hour.',
        //         ]
        //     ], 429);
        // }

        // Save OTP

        $isExist=Otp::where('email', $validated['email'] )
        ->first();
        if($isExist){
            Otp::where('email', $validated['email'])
            ->update([
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'is_verified' => false,
                'attempts' => 0,        
            ]);
        }
        else{
        Otp::insert([
            'phone_number' => $validated['phone_number'] ?? null,
            'email' => $validated['email'] ?? null,
            'country_code' => $validated['country_code'] ?? null,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'is_verified' => false,
            'attempts' => 0,
        ]);
    }

        // Send OTP via email or SMS
        if ($validated['email']) {
            $this->sendOtpViaEmail($validated['email'], $otp);
        }
        
        // if ($validated['phone_number']) {
        //     $this->sendOtpViaSms($validated['phone_number'], $otp, $validated['country_code'] ?? '+91');
        // }

        // Log OTP in dev mode
        if (config('app.debug')) {
            $contact = $validated['phone_number'] ?? $validated['email'];
            \Log::info("OTP for $contact: $otp");
        }

        // Increment rate limit
        Cache::put($cacheKey, $attempts + 1, now()->addHour());

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp_id' => $otpId,
            'expires_in' => 300, // seconds
        ]);
    }

    // API 2: Verify OTP
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'nullable|digits:10',
            'email' => 'nullable|email',
            'country_code' => 'nullable|string',
            'otp' => 'required|digits:6',
            'otp_id' => 'required|string'
        ]);

       $otpRecord= null;
      
        // Find OTP record
        if($validated['email']){
            $otpRecord = Otp::where('email', $validated['email'])
            ->orderBy('created_at', 'desc')
            ->first();
        }else{          
        $otpRecord = Otp::where('phone_number', $validated['phone_number'])
            ->orderBy('created_at', 'desc')
            ->first();
        }

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_OTP',
                    'message' => 'Invalid phone number or OTP not found',
                ]
            ], 400);
        }

        


        // Check expiration
        if ($otpRecord->expires_at < Carbon::now()) {
            $otpRecord->delete();
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'OTP_EXPIRED',
                    'message' => 'OTP has expired',
                ]
            ], 400);
        }

        // Check attempts
        if ($otpRecord->attempts >= config('app.otp_max_attempts', 5)) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'RATE_LIMIT_EXCEEDED',
                    'message' => 'Too many failed attempts',
                ]
            ], 429);
        }

        // Check OTP
        if ($otpRecord->otp !== $validated['otp']) {
            $otpRecord->increment('attempts');
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_OTP',
                    'message' => 'OTP is incorrect',
                ]
            ], 400);
        }
       
        
        $field = $validated['email'] ?? null ? 'email' : 'phone_number';
        $value = $validated[$field];
        $user = User::where($field, $value)->latest()->first();
        $tempToken=0;
        if ($user) {
            User::where($field, $value)->update(['is_verified' => true]);
             $accessToken = $this->generateToken($user);
            return response()->json([
                'success' => true,
                'is_new_user' => false,
                'access_token' => $accessToken,
                'refresh_token' => $this->generateRefreshToken($user),
                'user' => $user->toArray(),
            ]);
        }
        else{
            $tempToken = Str::random(40);
        }
        
        Cache::put("temp_token:$tempToken", [
            'phone_number' => $validated['phone_number'],
            'country_code' => $validated['country_code'],
        ], now()->addHours(1));

        return response()->json([
            'success' => true,
            'is_new_user' => true,
            'temp_token' => $tempToken,
            'message' => 'OTP verified. Please complete profile setup',
        ]);
    }

    // API 4: Login (Send OTP for existing users)
    public function login(Request $request)
    {
        $validated = $request->validate([
            'phone_number' => 'nullable|digits:10',
            'country_code' => 'nullable|string',
            'email' => 'nullable|email',
        ]);

        if (empty($validated['phone_number']) && empty($validated['email'])) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'VALIDATION_ERROR',
                    'message' => 'Either phone_number or email is required',
                ]
            ], 422);
        }   
        if($validated['email']){
            $user = User::where('email', $validated['email'])->first();
        }else{

        $user = User::where('phone_number', $validated['phone_number'])->first();
        }

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User with this phone number does not exist. Please sign up first.',
                ]
            ], 404);
        }

        // Rate limit check: 3 OTPs per hour per mobile
        $cacheKey = "otp_attempts:{$validated['phone_number']}";
        $attempts = Cache::get($cacheKey, 0);

        // if ($attempts >= 3) {
        //     return response()->json([
        //         'success' => false,
        //         'error' => [
        //             'code' => 'RATE_LIMIT_EXCEEDED',
        //             'message' => 'Too many OTP requests. Try after 1 hour.',
        //             'field' => 'phone_number'
        //         ]
        //     ], 429);
        // }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(config('app.otp_expiry_minutes', 5));

        $otpRecord = Otp::where(function ($query) use ($validated) {
                $query->where('phone_number', $validated['phone_number']);

                if (!empty($validated['email'])) {
                    $query->orWhere('email', $validated['email']);
                }
            })
    ->first();
        if($otpRecord){
            $otpRecord->update([
                'otp' => $otp,
                'email' => $validated['email'] ?? null,
                'expires_at' => $expiresAt,
                'is_verified' => false,
                'attempts' => 0,        
            ]);
        }
        else{
            Otp::insert([
                'phone_number' => $validated['phone_number'],
                'country_code' => $validated['country_code'],
                'otp' => $otp,
                'email' => $validated['email'] ?? null,
                'expires_at' => $expiresAt,
                'is_verified' => false,
                'attempts' => 0,
            ]);
        }
        

        // Log OTP in dev mode
        if (config('app.debug')) {
            \Log::info("Login OTP for {$validated['phone_number']}: $otp");
        }

        // Increment rate limit
        Cache::put($cacheKey, $attempts + 1, now()->addHour());
        $this->sendOtpViaEmail($validated['email'], $otp);

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully',
            'otp_id' => Str::uuid(),
            'expires_in' => 300, // seconds
        ]);
    }
    // API 3: Complete Profile (New Users)
    public function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:50',
            'username' => 'required|string|min:3|max:30|unique:users|regex:/^[a-z0-9_]+$/',
            'bio' => 'nullable|string|max:150',
            'profile_photo' => 'nullable',
            'phone_number' => 'required|digits:10',
            'country_code' => 'required|string',
            'temp_token' => 'required|string',
            'dob' => 'required|string',
            'gender' => 'required|string',

            'email' => 'nullable|email|unique:users',
        ]);

        $user = User::where('phone_number', $validated['phone_number'])->first();
        if ($user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_EXISTS',
                    'message' => 'User with this phone number already exists',
                ]
            ], 400);
        }

        $user = User::where('phone_number', $validated['phone_number'])->first();
        if ($user) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'USER_EXISTS',
                    'message' => 'User with this phone number already exists',
                ]
            ], 400);
        }
        
       
          if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $fileName = uniqid('', true).'.'.$file->getClientOriginalExtension();
            Storage::disk('s3')->putFileAs(
                'profile',        // folder
                $file,
                $fileName
            );
            $validated['profile_photo'] = $fileName;
        }
        
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'phone_number' => $validated['phone_number'],
            'country_code' => $validated['country_code'],
            'bio' => $validated['bio'] ?? '',
            'gender' => $validated['gender'] ?? '',
            'dob' => $validated['dob'] ?? '',
            'profile_photo' => $validated['profile_photo'] ?? null, 
            'api_token' => Str::random(80),
            'email' => $validated['email'] ?? null,
        ]);

        // Assign default 'user' role
        $user->assignRole('user');

        // Clear temp token
        Cache::forget("temp_token:{$validated['temp_token']}");

        // return new UserResource($user);
        return response()->json([
            'success' => true,
            'access_token' => $this->generateToken($user),
            'refresh_token' => $this->generateRefreshToken($user),
            'user' => new UserResource($user),
        ], 201);
    }

    // API 26: Refresh Token
    public function refreshToken(Request $request)
    {
        $validated = $request->validate([
            'refresh_token' => 'required|string',
        ]);

        // Validate refresh token
        try {
            $payload = \Firebase\JWT\JWT::decode(
                $validated['refresh_token'],
                new \Firebase\JWT\Key(config('app.jwt_secret'), 'HS256')
            );

            $user = User::find($payload->user_id);
            if (!$user) {
                throw new \Exception('User not found');
            }

            return response()->json([
                'success' => true,
                'access_token' => $this->generateToken($user),
                'refresh_token' => $this->generateRefreshToken($user),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code' => 'INVALID_TOKEN',
                    'message' => 'Invalid refresh token',
                ]
            ], 401);
        }
    }

    // API 27: Logout
    public function logout(Request $request)
    {
        // Invalidate token (optional - depends on token strategy)
        auth('api')->logout();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    // Helper: Generate JWT access token
    private function generateToken(User $user)
    {
        $issuedAt = time();
        // Get expiry from .env, default to 30 days if not set
        $expiryString = config('app.jwt_access_expiry', '30d');
        $expire = $this->parseExpiry($expiryString, $issuedAt);

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'user_id' => $user->id,
            'phone_number' => $user->phone_number,
        ];

        return \Firebase\JWT\JWT::encode(
            $payload,
            config('app.jwt_secret'),
            'HS256'
        );
    }

    // Helper: Generate refresh token
    private function generateRefreshToken(User $user)
    {
        $issuedAt = time();
        // Get expiry from .env, default to 180 days if not set
        $expiryString = config('app.jwt_refresh_expiry', '180d');
        $expire = $this->parseExpiry($expiryString, $issuedAt);

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'user_id' => $user->id,
        ];

        return \Firebase\JWT\JWT::encode(
            $payload,
            config('app.jwt_secret'),
            'HS256'
        );
    }

    // Helper: Parse expiry string (e.g., "30d", "7h", "3600s")
    private function parseExpiry(string $expiryString, int $issuedAt): int
    {
        if (preg_match('/^(\d+)([dhms])$/', $expiryString, $matches)) {
            $value = (int)$matches[1];
            $unit = $matches[2];

            switch ($unit) {
                case 'd': // days
                    return $issuedAt + ($value * 24 * 60 * 60);
                case 'h': // hours
                    return $issuedAt + ($value * 60 * 60);
                case 'm': // minutes
                    return $issuedAt + ($value * 60);
                case 's': // seconds
                    return $issuedAt + $value;
            }
        }

        // Default to 30 days if parsing fails
        return $issuedAt + (30 * 24 * 60 * 60);
    }

    // Helper: Send OTP via Email
    private function sendOtpViaEmail(string $email, string $otp)
    {
        try {
            Mail::send('emails.otp', [
                'otp' => $otp,
                'expiryMinutes' => config('app.otp_expiry_minutes', 5),
            ], function ($mail) use ($email) {
                $mail->to($email)
                    ->subject('Your OTP - Vichaar Vaani')
                    ->from('speechpublications@gmail.com', 'Vichaar Vaani');
            });

        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email to $email: " . $e->getMessage());
        }
    }

    // Helper: Send OTP via SMS (Twilio)
    public function sendOtpViaSms(string $phone, string $otp, string $countryCode = '+91')
    {
        try {
            $smsDriver = config('app.sms_driver', 'twilio');

            if ($smsDriver === 'twilio') {
                $this->sendViaTwilio($phone, $otp, $countryCode);
            } else {
                // Add other SMS providers here (AWS SNS, Nexmo, etc)
                \Log::info("SMS driver '$smsDriver' not configured");
            }
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP SMS to $phone: " . $e->getMessage());
        }
    }

    // Helper: Send SMS via Twilio
    private function sendViaTwilio(string $phone, string $otp, string $countryCode)
    {
        $accountSid = env('TWILIO_ACCOUNT_SID');
        $authToken = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_PHONE_NUMBER');

        if (!$accountSid || !$authToken || !$twilioNumber) {
            \Log::warning("Twilio credentials not configured");
            return;
        }

        try {
            $client = new \Twilio\Rest\Client($accountSid, $authToken);
            $message = "Your Vichaar Vaani OTP is: $otp. It will expire in " . config('app.otp_expiry_minutes', 5) . " minutes.";

            $client->messages->create(
                $countryCode . $phone,
                [
                    'from' => $twilioNumber,
                    'body' => $message,
                ]
            );

            \Log::info("OTP sent via SMS to $countryCode$phone");
        } catch (\Exception $e) {
            \Log::error("Twilio error: " . $e->getMessage());
        }
    }


}