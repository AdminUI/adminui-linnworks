<?php

namespace AdminUI\AdminUILinnworks\Listeners;

use Illuminate\Support\Facades\Log;
use AdminUI\AdminUI\Events\Public\NewOrder;
use AdminUI\AdminUILinnworks\Actions\PushOrderAction;
use AdminUI\AdminUILinnworks\Listeners\BaseLinnworksListener;

class SendOrderToLinnworks extends BaseLinnworksListener
{

    /**
     * Create a new job instance.
     */
    public function __construct(public NewOrder $event)
    {
    }

    /**
     * Handle the event to push an order to xero
     */
    public function handle(PushOrderAction $action): void
    {
        if (!config('linnworks.sync_orders')) return;

        $action->execute($this->event->order);
    }
}
