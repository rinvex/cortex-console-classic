<?php

declare(strict_types=1);

namespace Cortex\Console\Http\Controllers\Backend;

use Closure;
use Exception;
use Cortex\Console\DataTables\Backend\RoutesDataTable;
use Cortex\Foundation\Http\Controllers\AuthorizedController;

class ConsoleController extends AuthorizedController
{
    /**
     * {@inheritdoc}
     */
    protected $resource = 'console';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // @TODO: Replace with console dashbord with available tools
        return 'Console!';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function routes()
    {
        $middlewareClosure = function ($middleware) {
            return $middleware instanceof Closure ? 'Closure' : $middleware;
        };

        return app(RoutesDataTable::class)->with([
            'id' => 'cortex-console-routes',
            'middlewareClosure' => $middlewareClosure,
            'phrase' => trans('cortex/console::common.routes'),
        ])->render('cortex/foundation::backend.partials.datatable');
    }

    /**
     * Render exception.
     *
     * @param \Exception $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function renderException(Exception $exception)
    {
        return view('cortex/console::backend.forms.error', ['message' => $exception->getMessage()]);
    }
}
