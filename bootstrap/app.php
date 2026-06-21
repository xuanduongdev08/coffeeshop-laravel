<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Redirect guests based on request path
        $middleware->redirectGuestsTo(function ($request) {
            return $request->is('admin*') ? route('admin.login') : route('login');
        });

        // Bỏ qua CSRF cho webhook thanh toán và return callback từ PayPal/MoMo
        $middleware->validateCsrfTokens(except: [
            'webhook/*',
            'thanh-toan/paypal/*',
            'thanh-toan/momo/*',
        ]);

        // Đăng ký alias middleware
        $middleware->alias([
            'admin'           => \App\Http\Middleware\AdminMiddleware::class,
            'order.owner'     => \App\Http\Middleware\CheckOrderOwnership::class,
            'role'            => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'      => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
