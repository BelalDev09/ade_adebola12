<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\User;
use App\Policies\CategoryPolicy;
use App\Policies\ProductPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Apple\Provider as AppleProvider;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Product::class => ProductPolicy::class,
        Category::class => CategoryPolicy::class,
    ];
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
        // superadmin
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('superadmin')) {
                return true; // (web + api)
            }
        });

        //socialite login
        Socialite::extend('apple', static function ($app) {
            $config = $app['config']['services.apple'];

            return new AppleProvider(
                $app['request'],
                $config['client_id'],
                $config['client_secret'],
                $config['redirect']
            );
        });
    }
}
