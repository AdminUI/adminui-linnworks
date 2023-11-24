<?php

namespace AdminUI\AdminUILinnworks\Controllers;

use Inertia\Inertia;
use Illuminate\Database\Seeder;
use AdminUI\AdminUI\Models\Navigation;
use AdminUI\AdminUI\Models\Configuration;
use AdminUI\AdminUILinnworks\Facades\Linnworks;
use AdminUI\AdminUI\Facades\Navigation as FacadesNavigation;
use AdminUI\AdminUI\Controllers\AdminUI\Inertia\InertiaCoreController;

class SetupController extends InertiaCoreController
{
    public function __construct()
    {
        FacadesNavigation::setSubmenu('setup.integrations');
    }

    public function index()
    {
        $this->seo([
            'title' => 'Linnworks Integration Setup'
        ]);

        return Inertia::render('linnworks::Setup', [
            'linnworksAppId' => config('linnworks.app_id'),
            'linnworksSettings' => Configuration::where('section', 'linnworks')->get(),
            'linnworksActive' => Linnworks::isConnected()
        ]);
    }
}
