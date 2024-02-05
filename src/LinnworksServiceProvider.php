<?php

namespace AdminUI\AdminUILinnworks;

use AdminUI\AdminUI\Facades\Seeder;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use AdminUI\AdminUILinnworks\Facades\Linnworks;
use AdminUI\AdminUILinnworks\Commands\ImportStockIds;
use AdminUI\AdminUILinnworks\Providers\ConfigProvider;
use AdminUI\AdminUILinnworks\Services\LinnworksService;
use AdminUI\AdminUILinnworks\Commands\ImportStockLevels;
use AdminUI\AdminUILinnworks\Providers\LiveStockProvider;
use AdminUI\AdminUILinnworks\Database\Seeders\NavigationSeeder;
use AdminUI\AdminUILinnworks\Database\Seeders\ConfigurationSeeder;
use AdminUI\AdminUILinnworks\Providers\EventServiceProvider;

class LinnworksServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->register(ConfigProvider::class);
        $this->app->register(LiveStockProvider::class);
        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__ . '/Routes/admin.php');

        $this->app->singleton('linnworks', function () {
            return new LinnworksService;
        });
        $this->commands([ImportStockIds::class, ImportStockLevels::class]);
    }

    public function boot()
    {
        $baseDir = dirname(__DIR__);

        $this->setupSchedule();
        Seeder::add([NavigationSeeder::class, ConfigurationSeeder::class]);

        if (!$this->app->runningInConsole()) {
            $this->pushJavascript();
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                $baseDir . '/publish/js' => public_path('vendor/adminui-linnworks')
            ], 'adminui-public');


            $this->publishes([
                $baseDir . '/publish/config/adminui-linnworks.php' => config_path('linnworks.php'),
            ], 'adminui-public');
        }

        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
    }

    private function setupSchedule()
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('adminui:linnworks-stock')->daily();
            $schedule->command('adminui:linnworks-stock-levels')->everyThirtyMinutes();
        });
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
