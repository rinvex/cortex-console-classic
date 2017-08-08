<?php

declare(strict_types=1);

namespace Cortex\Console\DataTables\Backend;

use Illuminate\Routing\Route;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Illuminate\Support\Facades\Route as RouteFacade;

class RoutesDataTable extends AbstractDataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
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

        return $this->datatables
            ->collection($routes)
            ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
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
    protected function filename()
    {
        return 'routes_export_'.date('Y-m-d').'_'.time();
    }

    /**
     * Get parameters.
     *
     * @return array
     */
    protected function getParameters()
    {
        return [
            'keys' => true,
            'autoWidth' => false,
            'dom' => "<'row'<'col-sm-6'B><'col-sm-6'f>> <'row'r><'row'<'col-sm-12't>> <'row'<'col-sm-5'i><'col-sm-7'p>>",
            'buttons' => [
                'print', 'reset', 'reload', 'export',
                ['extend' => 'colvis', 'text' => '<i class="fa fa-columns"></i> '.trans('cortex/foundation::common.columns').' <span class="caret"/>'],
            ],
        ];
    }
}

