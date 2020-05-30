<?php

declare(strict_types=1);

namespace Cortex\Console\DataTables\Adminarea;

use Illuminate\Routing\Route;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Illuminate\Support\Facades\Route as RouteFacade;

class RoutesDataTable extends AbstractDataTable
{
    /**
     * Set action buttons.
     *
     * @var mixed
     */
    protected $buttons = [
        'create' => false,
        'import' => false,

        'reset' => true,
        'reload' => true,
        'showSelected' => true,

        'print' => true,
        'export' => true,

        'bulkDelete' => false,
        'bulkActivate' => false,
        'bulkDeactivate' => false,

        'colvis' => true,
        'pageLength' => true,
    ];

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $routes = collect(RouteFacade::getRoutes());

        $routes->transform(function (Route $route, $key) {
            return [
                'domain' => (string) $route->getDomain(),
                'uri' => (string) $route->uri() === '/' ? '/' : '/'.$route->uri(),
                'name' => (string) $route->getName(),
                'methods' => (string) implode(', ', $route->methods()),
                'action' => (string) $route->getActionName(),
                'middleware' => (string) implode(', ', $route->gatherMiddleware()),
            ];
        });

        return $routes;
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        return datatables($this->query())
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns(): array
    {
        return [
            'domain' => ['title' => trans('cortex/console::common.domain')],
            'uri' => ['title' => trans('cortex/console::common.uri')],
            'name' => ['title' => trans('cortex/console::common.name')],
            'methods' => ['title' => trans('cortex/console::common.methods')],
            'action' => ['title' => trans('cortex/console::common.action')],
            'middleware' => ['title' => trans('cortex/console::common.middleware')],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename(): string
    {
        return 'routes-export-'.date('Y-m-d').'-'.time();
    }
}
