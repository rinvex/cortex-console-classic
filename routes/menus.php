<?php

declare(strict_types=1);

use Rinvex\Menus\Models\MenuItem;
use Rinvex\Menus\Factories\MenuFactory;

Menu::modify('adminarea.sidebar', function (MenuFactory $menu) {
    $menu->findBy('title', trans('cortex/foundation::common.maintenance'), function (MenuItem $dropdown) {
        $dropdown->route(['adminarea.console.routes.index'], trans('cortex/console::common.routes'), 10, 'fa fa-globe')->can('list-routes');
        $dropdown->route(['adminarea.console.terminal.index'], trans('cortex/console::common.terminal'), 20, 'fa fa-terminal')->can('run-terminal');
    });
});
