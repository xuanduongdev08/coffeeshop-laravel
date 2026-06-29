<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect đến Google OAuth
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->redirectUrl(route('auth.google.callback'))
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Xử lý callback từ Google
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')
                ->redirectUrl(route('auth.google.callback'))
                ->user();
        } catch (\Exception $e) {
            return redirect()->route('login')
                ->with('error', 'Đăng nhập Google thất bại. Vui lòng thử lại.');
        }

        $avatarPath = null;
        $googleAvatarUrl = $googleUser->getAvatar();
        if ($googleAvatarUrl) {
            try {
                $response = Http::timeout(10)->get($googleAvatarUrl);
                if ($response->successful()) {
                    $avatarContent = $response->body();
                    $filename = 'avatars/google_' . $googleUser->getId() . '.jpg';
                    Storage::disk('public')->put($filename, $avatarContent);
                    $avatarPath = $filename;
                } else {
                    Log::warning('Failed to fetch Google avatar. Status code: ' . $response->status());
                }
            } catch (\Exception $e) {
                Log::error('Error downloading Google avatar: ' . $e->getMessage());
            }
        }

        $userData = [
            'name'           => $googleUser->getName(),
            'provider'       => 'google',
            'provider_id'    => $googleUser->getId(),
            'provider_token' => $googleUser->token,
        ];

        if ($avatarPath) {
            $userData['avatar'] = $avatarPath;
        } else {
            $existingUser = User::where('email', $googleUser->getEmail())->first();
            if (!$existingUser || !$existingUser->avatar) {
                $userData['avatar'] = $googleAvatarUrl;
            }
        }

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            $userData
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
