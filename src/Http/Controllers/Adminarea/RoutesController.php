<?php

declare(strict_types=1);

namespace Cortex\Console\Http\Controllers\Adminarea;

use Closure;
use Cortex\Console\DataTables\Adminarea\RoutesDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RoutesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'list-routes';

    /**
     * List all routes.
     *
     * @param \Cortex\Console\DataTables\Adminarea\RoutesDataTable $routesDataTable
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index(RoutesDataTable $routesDataTable)
    {
        $middlewareClosure = function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        };

        return $routesDataTable->with([
            'id' => 'adminarea-routes-index',
            'middlewareClosure' => $middlewareClosure,
        ])->render('cortex/foundation::adminarea.pages.datatable-index');
    }
}
