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
        $syncOrders = $settings->firstWhere('name', 'linnworks_sync_orders');
        $liveStock = $settings->firstWhere('name', 'linnworks_live_stock');
        $liveStockLocation = $settings->firstWhere('name', 'linnworks_live_stock_location');
        $livePricing = $settings->firstWhere('name', 'linnworks_live_pricing');


        config([
            'linnworks.refresh_token' => !empty($refreshToken) ? $refreshToken->value : null,
            'linnworks.locality' => !empty($locality) ? $locality->value : "eu",
            'linnworks.enabled' => !empty($enabled) ? $enabled->value : false,
            'linnworks.sync_orders' => !empty($syncOrders) ? $syncOrders->value : false,
            'linnworks.live_stock' => !empty($liveStock) ? $liveStock->value : false,
            'linnworks.live_stock_location' => !empty($liveStockLocation) ? $liveStockLocation->value : null,
            'linnworks.live_pricing' => !empty($livePricing) ? $livePricing->value : false
        ]);
    }
}
