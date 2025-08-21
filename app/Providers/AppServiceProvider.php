<?php

namespace App\Providers;

use App\Models\Settings\SocialiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

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
        Vite::prefetch(concurrency: 3);

        if (Schema::hasTable('socialite_settings')) {
            $google = SocialiteSetting::whereProvider('google')->first();
            $linkedin = SocialiteSetting::whereProvider('linkedin')->first();

            if ($google && $linkedin) {
                config([
                    'services.google' => [
                        'client_id' => $google->client_id,
                        'client_secret' => $google->client_secret,
                        'redirect' => $google->redirect_url,
                    ],
                    'services.linkedin-openid' => [
                        'client_id' => $linkedin->client_id,
                        'client_secret' => $linkedin->client_secret,
                        'redirect' => $linkedin->redirect_url,
                    ],
                ]);
            }
        }
    }
}
