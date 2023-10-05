<?php

namespace AdminUI\AdminUILinnworks\Providers;

use Illuminate\Support\ServiceProvider;
use AdminUI\AdminUI\Models\Configuration;

class ConfigProvider extends ServiceProvider
{
    public function boot(): void
    {
        $settings = Configuration::where('section', 'linnworks')->get();

        $enabled = $settings->firstWhere('name', 'linnworks_enabled');
        $appId = $settings->firstWhere('name', 'linnworks_app_id');
        $appSecret = $settings->firstWhere('name', 'linnworks_app_secret');
        $refreshToken = $settings->firstWhere('name', 'linnworks_refresh_token');

        config([
            'linnworks.enabled' => !empty($enabled) ? $enabled->value : false,
            'linnworks.app_id' => !empty($appId) ? $appId->value : null,
            'linnworks.app_secret' => !empty($appSecret) ? $appSecret->value : null,
            'linnworks.refresh_token' => !empty($refreshToken) ? $refreshToken->value : null,
        ]);
    }
}
