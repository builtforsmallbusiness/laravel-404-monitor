<?php

use Illuminate\Support\Facades\Route;
use BuiltForSmallBusiness\Laravel404Monitor\Http\Controllers\MonitorController;

Route::prefix(config('404monitor.route_prefix', '_404-monitor'))
    ->middleware([...config('404monitor.middleware'), 'can:view-404-monitor'])
    ->name('404monitor.')
    ->group(function () {

        Route::get('/', [MonitorController::class, 'index'])->name('index');
        Route::delete('/clear-all', [MonitorController::class, 'destroyAll'])->name('destroy-all');
        Route::delete('/{failedRequest}', [MonitorController::class, 'destroy'])->name('destroy');

    });
