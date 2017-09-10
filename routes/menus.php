<?php

declare(strict_types=1);

Menu::adminareaSidebar('tools')->routeIfCan('run-terminal', 'adminarea.console.routes.index', '<i class="fa fa-globe"></i> <span>'.trans('cortex/console::common.routes').'</span>');
Menu::adminareaSidebar('tools')->routeIfCan('list-routes', 'adminarea.console.terminal.index', '<i class="fa fa-terminal"></i> <span>'.trans('cortex/console::common.terminal').'</span>');
