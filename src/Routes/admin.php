<?php

use Illuminate\Support\Facades\Route;
use AdminUI\AdminUILinnworks\Controllers\SetupController;

Route::prefix(config('adminui.prefix'))->middleware(['adminui', 'auth:admin'])->group(function () {
    Route::get('setup/linnworks', [SetupController::class, 'index'])->name('admin.setup.linnworks.index');
});
