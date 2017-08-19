<?php

declare(strict_types=1);

Route::group(['domain' => domain()], function () {

    Route::name('backend.')
         ->namespace('Cortex\Console\Http\Controllers\Backend')
         ->middleware(['web', 'nohttpcache', 'can:access-dashboard'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/backend' : 'backend')->group(function () {

        // Categories Routes
        Route::name('console.')->prefix('console')->group(function () {
            Route::get('routes')->name('routes.index')->uses('RoutesController@index');
            Route::get('terminal')->name('terminal.index')->uses('TerminalController@index');
            Route::post('terminal')->name('terminal.execute')->uses('TerminalController@execute');
        });

    });

});
