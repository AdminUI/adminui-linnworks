<?php

namespace AdminUI\AdminUILinnworks\Listeners;

use Throwable;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

abstract class BaseLinnworksListener
{
    use InteractsWithQueue, Dispatchable, SerializesModels, Queueable;

    /**
     * The number of times the queued listener may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * Handle a job failure.
     */
    public function failed(Throwable $exception): void
    {
        Log::error("Order failed to push to Linnworks");
    }
}
