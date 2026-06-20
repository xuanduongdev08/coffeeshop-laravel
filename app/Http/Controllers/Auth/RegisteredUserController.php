<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Gán role customer mặc định
        $user->assignRole('customer');

        // Gửi email chào mừng đăng ký thành công
        try {
            $template = \App\Models\EmailTemplate::where('template_key', 'register_success')->first();
            if ($template) {
                $placeholders = [
                    '{customer_name}'  => $user->name,
                    '{customer_email}' => $user->email,
                    '{website_link}'   => route('home'),
                ];
                \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\DynamicTemplateMail($template, $placeholders));
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to send registration welcome email: ' . $e->getMessage());
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home'));
    }
}
