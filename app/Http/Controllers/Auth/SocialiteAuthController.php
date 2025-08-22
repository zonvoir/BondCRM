<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class SocialiteAuthController extends Controller
{
    public function redirectToOAuth(Request $request): RedirectResponse
    {
        if ($request->type === 'outlook') {
            return Socialite::driver('microsoft')
                ->scopes([
                    'openid',
                    'profile',
                    'offline_access',
                    'https://graph.microsoft.com/Mail.Read',
                    'https://graph.microsoft.com/Mail.Send',
                ])
                ->redirect();
        }

        if ($request->type === 'google') {
            return Socialite::driver('google')
                ->scopes([
                    'https://mail.google.com/',
                    'https://www.googleapis.com/auth/gmail.readonly',
                    'https://www.googleapis.com/auth/gmail.modify',
                    'https://www.googleapis.com/auth/gmail.labels',
                ])
                ->with(['prompt' => 'consent', 'access_type' => 'offline'])
                ->redirect();
        }

        return back();
    }

    public function handleGoogleCallback(Request $request): RedirectResponse
    {
        dd($request->all());

    }

    public function handleMicrosoftCallback(Request $request)
    {
        dd($request->all());
    }
}
