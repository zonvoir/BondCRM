<?php

namespace App\Providers;

use App\Models\Setup\SocialiteSetting;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

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

        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('microsoft', \SocialiteProviders\Microsoft\Provider::class);
        });

        if (Schema::hasTable('socialite_settings')) {
            $google = SocialiteSetting::query()->whereProvider('google')->first();
            $microsoft = SocialiteSetting::query()->whereProvider('microsoft')->first();

            if ($google && $microsoft) {
                config([
                    'services.google' => [
                        'client_id' => $google->client_id,
                        'client_secret' => $google->client_secret,
                        'redirect' => $google->redirect_url,
                    ],
                    'services.microsoft' => [
                        'client_id' => $microsoft->client_id,
                        'client_secret' => $microsoft->client_secret,
                        'redirect' => $microsoft->redirect_url,
                    ],
                ]);
            }
        }
    }
}
