<?php

declare(strict_types=1);

Menu::backendSidebar('tools')->routeIfCan('run-terminal', 'backend.console.routes.index', '<i class="fa fa-globe"></i> <span>'.trans('cortex/console::common.routes').'</span>');
Menu::backendSidebar('tools')->routeIfCan('list-routes', 'backend.console.terminal.index', '<i class="fa fa-terminal"></i> <span>'.trans('cortex/console::common.terminal').'</span>');
