<?php

namespace AdminUI\AdminUILinnworks\Providers;

use AdminUI\AdminUI\Facades\Hooks;
use AdminUI\AdminUI\Constants\Hook;
use Illuminate\Support\ServiceProvider;
use AdminUI\AdminUILinnworks\Actions\GetStockAction;

class LiveStockProvider extends ServiceProvider
{
    public function boot(GetStockAction $action): void
    {
        // if live stock is enabled
        config(['linnworks.live_stock' => true]);

        if (config('linnworks.live_stock')) {
            Hooks::add(Hook::PRODUCT_PRE_RENDER, function ($model) use ($action) {
                $action->execute($model);
            });
        }
    }
}
