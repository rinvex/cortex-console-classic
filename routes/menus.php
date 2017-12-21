<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Factories\MenuFactory;

Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->findByTitleOrAdd(trans('cortex/foundation::common.maintenance'), 999, 'fa fa-cogs', [], function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.console.routes.index'], trans('cortex/console::common.routes'), 10, 'fa fa-globe')->ifCan('list-routes');
        $dropdown->route(['adminarea.console.terminal.index'], trans('cortex/console::common.terminal'), 20, 'fa fa-terminal')->ifCan('run-terminal');
    });
});
