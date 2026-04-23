<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

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
    // Verifikasi Email
    VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
        return (new MailMessage)
            ->subject('Verifikasi Akun - Inventaris Ibadah')
            ->markdown('emails.verify', [
                'url' => $url, 
                'user' => $notifiable
            ]);
    });

    // Atur Ulang Kata Sandi
    ResetPassword::toMailUsing(function (object $notifiable, string $token) {
        $url = url(route('password.reset', [
            'token' => $token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Atur Ulang Kata Sandi - Inventaris Ibadah')
            ->markdown('emails.reset-password', [
                'url' => $url, 
                'user' => $notifiable
            ]);
    });
}
}