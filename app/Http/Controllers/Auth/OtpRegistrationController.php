<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;

use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Mail;
use DB;



class OtpRegistrationController extends Controller
{
    /**
     * Display the OTP registration view
     */
    public function create()
    {
        return view('auth.otp-register');
    }

    /**
     * Send OTP to email for registration
     */
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        // Check if user already exists
        $user = User::where('email', $validated['email'])->first();
        if ($user) {
            return response()->json([
                'success' => false,
                'message' => 'Email already registered. Please login instead.',
            ], 400);
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(config('app.otp_expiry_minutes', 5));

        // Check if OTP record exists for this email
        $otpRecord = Otp::where('email', $validated['email'])->first();

        if ($otpRecord) {
            $otpRecord->update([
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'is_verified' => false,
                'attempts' => 0,
            ]);
        } else {
            Otp::create([
                'email' => $validated['email'],
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'is_verified' => false,
                'attempts' => 0,
            ]);
        }

        // Send OTP via email
        $this->sendOtpViaEmail($validated['email'], $otp);

        // Log OTP in dev mode
        if (config('app.debug')) {
            \Log::info("Registration OTP for {$validated['email']}: $otp");
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your email',
            'expires_in' => 300,
        ]);
    }

    /**
     * Verify OTP and proceed to complete profile
     */
    public function verifyOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        // Find OTP record
        $otpRecord = Otp::where('email', $validated['email'])
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'OTP not found. Please request a new OTP.',
            ], 400);
        }

        // Check if OTP has expired
        if ($otpRecord->expires_at < Carbon::now()) {
            $otpRecord->delete();
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired. Please request a new OTP.',
            ], 400);
        }

        // Check attempts
        if ($otpRecord->attempts >= config('app.otp_max_attempts', 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many failed attempts. Please request a new OTP.',
            ], 429);
        }

        // Check if OTP is correct
        if ($otpRecord->otp !== $validated['otp']) {
            $otpRecord->increment('attempts');
            return response()->json([
                'success' => false,
                'message' => 'Incorrect OTP. Please try again.',
            ], 400);
        }

        // Mark OTP as verified
        $otpRecord->update(['is_verified' => true]);

        // Generate temp token for profile completion
        $tempToken = Str::random(40);
        Cache::put("temp_token:$tempToken", [
            'email' => $validated['email'],
        ], now()->addHours(1));

        return response()->json([
            'success' => true,
            'message' => 'OTP verified. Please complete your profile',
            'temp_token' => $tempToken,
        ]);
    }

    /**
     * Complete user profile and create account
     */
    public function completeProfile(Request $request)
    {
        $roleId = Role::where('name', 'user')->first();
          
        $validated = $request->validate([
            'temp_token' => 'required|string',
            'name' => 'required|string|min:2|max:50',
            'username' => 'required|string|min:3|max:30|unique:users|regex:/^[a-z0-9_]+$/',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'dob' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'bio' => 'nullable|string|max:150',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $tempData = Cache::get("temp_token:{$validated['temp_token']}");
        if (!$tempData) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired token. Please start registration again.',
            ], 400);
        }

        $email = $validated['email'];
        
        try {
            $profilePhotoPath = null;

            // Upload profile photo to S3 if provided
            if ($request->hasFile('profile_photo')) {
                try {
                    $file = $request->file('profile_photo');
                    $fileName = uniqid('profile_', true) . '.' . $file->getClientOriginalExtension();
                    Storage::disk('s3')->putFileAs(
                        'profile',
                        $file,
                        $fileName
                    );
                    $profilePhotoPath = $fileName;
                } catch (\Exception $e) {
                    \Log::error('Profile photo upload failed: ' . $e->getMessage());
                    // Continue without photo if upload fails
                }
            }

            // Create new user
            $user = User::create([
                'email' => $email,
                'name' => $validated['name'],
                'username' => $validated['username'],
                'password' => bcrypt($validated['password']),
                'dob' => $validated['dob'],
                'gender' => $validated['gender'],
                'bio' => $validated['bio'] ?? '',
                'profile_photo' => $profilePhotoPath,
                'is_verified' => true,
            ]);


       
            Otp::where('email', $email)->update(['is_verified' => true]);
            
            
            \DB::table('model_has_roles')->insert([
                'role_id' => $roleId->id,
                'model_type' => 'App\Models\User',
                'model_id' => $user->id,
            ]);

            // Clear temp token
            Cache::forget("temp_token:{$validated['temp_token']}");
            Auth::login($user, true);

            return response()->json([
                'success' => true,
                'message' => 'Account created successfully',
                'redirect' => route('dashboard', absolute: false),
            ]);
        } catch (\Exception $e) {
            \Log::error('User creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error creating account. Please try again.',
            ], 500);
        }
    }

    /**
     * Send OTP via email
     */
    private function sendOtpViaEmail($email, $otp)
    {
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'email' => $email, 'type' => 'registration'], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your OTP for Registration - Speech Publications');
            });
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email: " . $e->getMessage());
        }
    }
}
