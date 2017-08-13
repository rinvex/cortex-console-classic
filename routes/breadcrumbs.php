<?php

declare(strict_types=1);

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;

Breadcrumbs::register('backend.console.terminal.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.backend'), route('backend.home'));
    $breadcrumbs->push(trans('cortex/console::common.terminal'), route('backend.console.terminal.index'));
});

Breadcrumbs::register('backend.console.routes.index', function (BreadcrumbsGenerator $breadcrumbs) {
    $breadcrumbs->push('<i class="fa fa-dashboard"></i> '.trans('cortex/foundation::common.backend'), route('backend.home'));
    $breadcrumbs->push(trans('cortex/console::common.routes'), route('backend.console.routes.index'));
});
