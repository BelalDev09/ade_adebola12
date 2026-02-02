<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            return (new MailMessage)
                ->subject('Reset Your Password')
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('You are receiving this email because we received a password reset request for your account.')
                ->action('Reset Password', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.users.expire')])
                ->line('If you did not request a password reset, no further action is required.')
                ->salutation('Regards, ' . config('app.name'));
        });
        Paginator::useBootstrap();
        View::composer('backend.partials.footer', function ($view) {
            $view->with('settings', Setting::first());
        });
    }
}
