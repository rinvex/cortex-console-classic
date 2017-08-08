<?php

declare(strict_types=1);

Route::group(['domain' => domain()], function () {

    Route::name('backend.')
         ->namespace('Cortex\Console\Http\Controllers\Backend')
         ->middleware(['web', 'nohttpcache', 'can:access-dashboard'])
         ->prefix(config('rinvex.cortex.route.locale_prefix') ? '{locale}/backend' : 'backend')->group(function () {

        // Categories Routes
        Route::name('console.')->prefix('console')->group(function () {
            Route::get('/')->name('index')->uses('ConsoleController@index');
            Route::get('routes')->name('routes.index')->uses('ConsoleController@routes');
            Route::get('terminal')->name('terminal.form')->uses('TerminalController@form');
            Route::post('terminal')->name('terminal.execute')->uses('TerminalController@execute');
        });

    });

});
