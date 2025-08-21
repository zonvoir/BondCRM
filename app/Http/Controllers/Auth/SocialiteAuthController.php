<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Throwable;

class SocialiteAuthController extends Controller
{
    public function redirectToOAuth(Request $request): RedirectResponse
    {
        if ($request->type) {
            return Socialite::driver($request->type)->redirect();
        }

        return redirect(route('login'));
    }

    /**
     * @throws Throwable
     */
    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        $provider = 'google';
        try {
            $authUser = Socialite::driver($provider)->user();
            $user = User::where('email', $authUser->email)->first();

            if ($user) {
                Auth::login($user);

                return redirect()->route('dashboard');
            }

            return redirect()->route('login')->with([
                'message' => 'No account found with this email. Please register first.',
                'type' => 'error',
            ]);
        } catch (Exception $e) {
            return redirect()->route('login')->with([
                'message' => 'Google authentication failed. Please try again.',
                'type' => 'error',
            ]);
        }
    }

    public function handleLinkedinCallback(Request $request)
    {
        $provider = 'linkedin-openid';

        try {
            $authUser = Socialite::driver($provider)->user();
            $user = User::where('email', $authUser->email)->first();

            if ($user) {
                Auth::login($user);

                return redirect()->route('dashboard');
            }

            return redirect()->route('login')->with([
                'message' => 'No account found with this email. Please register first.',
                'type' => 'error',
            ]);
        } catch (Exception $e) {
            return redirect()->route('login')->with([
                'message' => 'LinkedIn authentication failed. Please try again.',
                'type' => 'error',
            ]);
        }
    }
}
