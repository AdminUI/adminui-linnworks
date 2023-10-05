<?php

namespace AdminUI\AdminUILinnworks;

use AdminUI\AdminUI\Facades\Seeder;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use AdminUI\AdminUILinnworks\Facades\Linnworks;
use AdminUI\AdminUILinnworks\Providers\ConfigProvider;
use AdminUI\AdminUILinnworks\Services\LinnworksService;
use AdminUI\AdminUILinnworks\Database\Seeders\NavigationSeeder;
use AdminUI\AdminUILinnworks\Database\Seeders\ConfigurationSeeder;

class LinnworksServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(ConfigProvider::class);
        $this->loadRoutesFrom(__DIR__ . '/Routes/admin.php');

        $this->app->singleton('linnworks', function () {
            return new LinnworksService;
        });
    }

    public function boot()
    {
        $baseDir = dirname(__DIR__);

        Seeder::add([NavigationSeeder::class, ConfigurationSeeder::class]);

        if (!$this->app->runningInConsole()) {
            $this->pushJavascript();
        }

        $this->publishes([
            $baseDir . '/publish/js' => public_path('vendor/adminui-linnworks')
        ], 'adminui-public');
    }

    public function pushJavascript()
    {
        $output = \Illuminate\Support\Facades\Vite::useHotFile(base_path('vendor/adminui/adminui-linnworks/publish/js/hot'))
            ->withEntryPoints(['resources/index.js'])
            ->useBuildDirectory('vendor/adminui-linnworks')
            ->toHtml();

        View::startPush('aui_packages', $output);
    }
}
