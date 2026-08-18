<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Contact;
use Mail;
use validated;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    
    public function submitContact(Request $request){
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email'    => 'required|email',
            'phone'    => 'required|regex:/^[0-9]{10}$/',
            'subject'  => 'required|string|max:255',
            'message'  => 'required|string|max:2000',
        ]);

        // Save to DB (if you have a Contact model + migration)
        Contact::create([
            'name' => $request->fullname,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'subject'  => $request->subject,
            'message'  => $request->message,
        ]);

        // Or send email
           Mail::send([], [], function($mail) use ($request) {
        $mail->to('speechpublications@gmail.com') // ← Change this to your admin email
             ->subject('New Contact Message: ' . $request->subject)
             ->from($request->email, $request->fullname)
             ->html("
                <h3>New Contact Message Received</h3>
                <p><strong>Name:</strong> {$request->fullname}</p>
                <p><strong>Email:</strong> {$request->email}</p>
                <p><strong>Phone:</strong> {$request->phone}</p>
                <p><strong>Subject:</strong> {$request->subject}</p>
                <p><strong>Message:</strong><br>{$request->message}</p>
            ");
    });

    // 📩 User Confirmation Email
    Mail::send([], [], function($mail) use ($request) {
        $mail->to($request->email)
             ->subject('Thank You for Contacting Speech Publications')
             ->from('speechpublications@gmail.com', 'Speech Publications') // ← use your official mail here
             ->html("
                <h3>Dear {$request->fullname},</h3>
                <p>Thank you for contacting <strong>Speech Publications</strong>.</p>
                <p>We have received your message and our team will get back to you soon.</p>
                <hr>
                <p><strong>Your Message:</strong><br>{$request->message}</p>
                <p>Warm regards,<br>Speech Publications Team</p>
            ");
    });

        return back()->with('success', 'Thank you! Your message has been sent.');
    
    }
}
