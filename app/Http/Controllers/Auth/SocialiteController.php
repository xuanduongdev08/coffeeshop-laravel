<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect đến Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name'           => $googleUser->getName(),
                'provider'       => 'google',
                'provider_id'    => $googleUser->getId(),
                'provider_token' => $googleUser->token,
                'avatar'         => $googleUser->getAvatar(),
            ]
        );

        if ($user->wasRecentlyCreated || $user->roles->isEmpty()) {
            $user->assignRole('customer');
        }

        Auth::login($user, true);

        if ($user->hasAnyRole(['admin', 'staff', 'cashier', 'warehouse'])) {
            return redirect()->intended(url('/admin'));
        }

        return redirect()->intended(route('home'));
    }
}
