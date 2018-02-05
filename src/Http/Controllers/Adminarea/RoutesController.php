<?php

declare(strict_types=1);

namespace Cortex\Console\Http\Controllers\Adminarea;

use Closure;
use Illuminate\Support\Str;
use Cortex\Console\DataTables\Adminarea\RoutesDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class RoutesController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'routes';

    /**
     * {@inheritdoc}
     */
    public function authorizeResource($model, $parameter = null, array $options = [], $request = null): void
    {
        $middleware = [];
        $parameter = $parameter ?: Str::snake(class_basename($model));

        foreach ($this->mapResourceAbilities() as $method => $ability) {
            $modelName = in_array($method, $this->resourceMethodsWithoutModels()) ? $model : $parameter;

            $middleware["can:{$ability}-{$modelName},{$modelName}"][] = $method;
        }

        foreach ($middleware as $middlewareName => $methods) {
            $this->middleware($middlewareName, $options)->only($methods);
        }
    }

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
            'id' => 'adminarea-routes-index-table',
            'middlewareClosure' => $middlewareClosure,
            'phrase' => trans('cortex/console::common.routes'),
        ])->render('cortex/foundation::adminarea.pages.datatable');
    }
}
