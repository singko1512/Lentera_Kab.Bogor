<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Mail;
use App\Mail\Transport\BrevoTransport;

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
        Mail::extend('brevo', function (array $config = []) {
            $key = $config['key'] ?? config('services.brevo.key') ?? env('BREVO_API_KEY');
            return new BrevoTransport((string) $key);
        });
    }
}
