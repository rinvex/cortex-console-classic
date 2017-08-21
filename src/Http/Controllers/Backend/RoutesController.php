<?php

declare(strict_types=1);

namespace Cortex\Console\Http\Controllers\Backend;

use Closure;
use Cortex\Console\DataTables\Backend\RoutesDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RoutesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'routes';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $middlewareClosure = function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        };

        return app(RoutesDataTable::class)->with([
            'id' => 'cortex-console-routes',
            'middlewareClosure' => $middlewareClosure,
            'phrase' => trans('cortex/console::common.routes'),
        ])->render('cortex/foundation::backend.pages.datatable');
    }
}
