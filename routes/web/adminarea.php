<?php

declare(strict_types=1);

Route::domain(domain())->group(function () {
    Route::name('adminarea.')
         ->namespace('Cortex\Console\Http\Controllers\Adminarea')
         ->middleware(['web', 'nohttpcache', 'can:access-adminarea'])
         ->prefix(config('cortex.foundation.route.locale_prefix') ? '{locale}/'.config('cortex.foundation.route.prefix.adminarea') : config('cortex.foundation.route.prefix.adminarea'))->group(function () {

        // Categories Routes
             Route::name('cortex.console.')->prefix('console')->group(function () {
                 Route::get('routes')->name('routes.index')->uses('RoutesController@index');
                 Route::get('terminal')->name('terminal.index')->uses('TerminalController@index');
                 Route::post('terminal')->name('terminal.execute')->uses('TerminalController@execute');
             });
         });
});
