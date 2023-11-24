<?php

namespace AdminUI\AdminUILinnworks\Database\Seeders;

use Illuminate\Database\Seeder;
use AdminUI\AdminUI\Facades\Config;


class ConfigurationSeeder extends Seeder
{
    public function run()
    {

        Config::create('linnworks_enabled', [
            'label' => 'Enabled',
            'value_cast' => 'boolean',
            'section' => 'linnworks',
            'type' => 'switch',
            'is_private' => true,
            'is_active' => true
        ], false);

        Config::create('linnworks_refresh_token', [
            'label' => 'Refresh Token',
            'value_cast' => null,
            'section' => 'linnworks',
            'type' => 'password',
            'is_private' => true,
            'is_active' => true
        ]);

        Config::create('linnworks_locality', [
            'label' => 'Locality',
            'value_cast' => 'string',
            'section' => 'linnworks',
            'type' => 'text',
            'is_private' => true,
            'is_active' => true
        ]);

        Config::create('linnworks_sync_orders', [
            'label' => 'Enabled',
            'value_cast' => 'boolean',
            'section' => 'linnworks',
            'type' => 'switch',
            'is_private' => true,
            'is_active' => true
        ], false);
    }
}
