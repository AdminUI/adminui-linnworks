<?php

namespace AdminUI\AdminUILinnworks\Providers;

use AdminUI\AdminUI\Events\Public\NewOrder;
use AdminUI\AdminUILinnworks\Listeners\SendOrderToLinnworks;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        NewOrder::class => [
            SendOrderToLinnworks::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}
