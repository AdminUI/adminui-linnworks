<?php

namespace AdminUI\AdminUILinnworks\Facades;


use Illuminate\Support\Facades\Facade;


/**
 * @method static array fetch(string $method, string $endpoint, array $data)
 * @see \AdminUI\AdminUILinnworks\Services\LinnworksService
 */
class Linnworks extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'linnworks';
    }
}
