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
        $refreshToken = $settings->firstWhere('name', 'linnworks_refresh_token');
        $locality = $settings->firstWhere('name', 'linnworks_locality');

        config([
            'linnworks.enabled' => !empty($enabled) ? $enabled->value : false,
            'linnworks.refresh_token' => !empty($refreshToken) ? $refreshToken->value : null,
            'linnworks.locality' => !empty($locality) ? $locality->value : "eu",
        ]);
    }
}
