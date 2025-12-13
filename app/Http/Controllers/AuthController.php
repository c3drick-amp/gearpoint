<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'password' => ['required'],
        ], [
            'name.regex' => 'Name must not contain spaces or special characters. Use letters, numbers, or underscores only (e.g., AdminUser).',
        ]);

        $remember = false;
        if (Auth::attempt(['name' => $data['name'], 'password' => $data['password']], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        return back()->withErrors([
            'name' => 'Invalid credentials.',
        ])->withInput();
    }

    public function showForgot()
    {
        return view('auth.forgot');
    }

    public function sendForgot(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
        ], [
            'name.regex' => 'Name must not contain spaces or special characters. Use letters, numbers, or underscores only (e.g., AdminUser).',
        ]);

        $user = User::where('name', $data['name'])->first();
        if (!$user) {
            return back()->withErrors(['name' => 'User not found with that name.']);
        }

        $status = Password::sendResetLink(['email' => $user->email]);
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Password reset link sent to the user email.');
        }
        return back()->withErrors(['name' => 'Unable to send password reset link.']);
    }

    public function showResetForm(\Illuminate\Http\Request $request, $token)
    {
        $email = $request->query('email');
        return view('auth.reset', compact('token', 'email'));
    }

    public function reset(Request $request)
    {
        $data = $request->validate([
            'token' => 'required',
            'email' => 'sometimes|nullable|email',
            'name' => ['sometimes', 'nullable', 'string', 'regex:/^[A-Za-z0-9_]+$/'],
            'password' => 'required|confirmed|min:6',
        ], [
            'name.regex' => 'Name must not contain spaces or special characters.',
        ]);

        // If name provided instead of email, resolve to user's email
        if (empty($data['email']) && !empty($data['name'])) {
            $user = User::where('name', $data['name'])->first();
            if ($user) {
                $data['email'] = $user->email;
            }
        }

        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill([
                'password' => bcrypt($password)
            ])->save();
        });

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password reset successful. Please login.');
        }
        return back()->withErrors(['email' => __($status)]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
