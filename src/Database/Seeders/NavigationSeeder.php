<?php

namespace AdminUI\AdminUILinnworks\Database\Seeders;

use Illuminate\Database\Seeder;
use AdminUI\AdminUI\Models\Navigation;

class NavigationSeeder extends Seeder
{

    public function run()
    {
        $setup = Navigation::firstWhere('ref', 'setup');

        Navigation::where('ref', 'setup.linnworks')->delete();

        $integrations = Navigation::updateOrCreate(
            ['ref' => 'setup.integrations'],
            [
                'title' => 'Integrations',
                'route' => 'admin.setup.integrations.index',
                'icon' => null,
                'parent_id' => $setup->id,
                'permissions' => null,
                'package' => 'Ecommerce',
                'is_active' => true,
                'sort_order' => 40,
            ]
        );

        Navigation::updateOrCreate(
            ['ref' => 'setup.integrations.linnworks'],
            [
                'title' => 'Linnworks',
                'route' => 'admin.setup.linnworks.index',
                'icon' => null,
                'parent_id' => $integrations->id,
                'permissions' => null,
                'package' => 'Ecommerce',
                'is_active' => true,
                'sort_order' => 41,
            ]
        );
    }
}
