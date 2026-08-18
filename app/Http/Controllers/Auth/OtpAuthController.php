<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Mail;

class OtpAuthController extends Controller
{
    /**
     * Display the OTP login view
     */
    public function create()
    {
        return view('auth.otp-login');
    }

    /**
     * Send OTP to email
     */
    public function sendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $expiresAt = Carbon::now()->addMinutes(config('app.otp_expiry_minutes', 5));

        // Check if OTP record exists for this email
        $otpRecord = Otp::where('email', $validated['email'])->first();

        if ($otpRecord) {
            // Update existing OTP
            $otpRecord->update([
                'otp' => $otp,
                'expires_at' => $expiresAt,
                'is_verified' => false,
                'attempts' => 0,
            ]);
        } else {
            // Create new OTP
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
            \Log::info("OTP for {$validated['email']}: $otp");
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully to your email',
        ]);
    }

    /**
     * Verify OTP and login user
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

        // Find or create user
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'email' => $validated['email'],
                'name' => explode('@', $validated['email'])[0],
                'password' => bcrypt(Str::random(16)),
                'is_verified' => true,
            ]);
        } else {
            // Update existing user as verified
            $user->update(['is_verified' => true]);
        }

        // Login the user
        Auth::login($user, true);

        // Delete OTP record after successful verification
        $otpRecord->delete();

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'redirect' => route('dashboard', absolute: false),
        ]);
    }

    /**
     * Send OTP via email
     */
    private function sendOtpViaEmail($email, $otp)
    {
        try {
            Mail::send('emails.otp', ['otp' => $otp, 'email' => $email], function ($message) use ($email) {
                $message->to($email)
                    ->subject('Your OTP for Login');
            });
        } catch (\Exception $e) {
            \Log::error("Failed to send OTP email: " . $e->getMessage());
        }
    }
}
