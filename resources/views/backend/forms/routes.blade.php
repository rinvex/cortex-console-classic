{{-- Master Layout --}}
@extends('cortex/foundation::backend.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.backend') }} » {{ trans('cortex/console::common.console') }} » {{ trans('cortex/console::common.routes') }}
@stop

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ trans('cortex/console::common.console') }}</h1>
            <!-- Breadcrumbs -->
            {{ Breadcrumbs::render() }}
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">
                                {{ trans('cortex/console::common.routes') }}
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <table class="table table-sm table-hover" style="visibility: hidden;">
                                <thead>
                                    <tr>
                                        <th>Methods</th>
                                        <th class="domain">Domain</td>
                                        <th>Path</td>
                                        <th>Name</th>
                                        <th>Action</th>
                                        <th>Middleware</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $methodColours = ['GET' => 'success', 'HEAD' => 'default', 'POST' => 'primary', 'PUT' => 'warning', 'PATCH' => 'info', 'DELETE' => 'danger']; ?>
                                    @foreach ($routes as $route)
                                        <tr>
                                            <td>
                                                @foreach ($route->methods() as $method)
                                                    <span class="tag tag-{{ array_get($methodColours, $method) }}">{{ $method }}</span>
                                                @endforeach
                                            </td>
                                            <td class="domain{{ strlen($route->domain()) == 0 ? ' domain-empty' : '' }}">{{ $route->domain() }}</td>
                                            <td>{!! preg_replace('#({[^}]+})#', '<span class="text-warning">$1</span>', $route->uri()) !!}</td>
                                            <td>{{ $route->getName() }}</td>
                                            <td>{!! preg_replace('#(@.*)$#', '<span class="text-warning">$1</span>', $route->getActionName()) !!}</td>
                                            <td>
                                                @if (is_callable([$route, 'controllerMiddleware']))
                                                    {{ implode(', ', array_map($middlewareClosure, array_merge($route->middleware(), $route->controllerMiddleware()))) }}
                                                @else
                                                    {{ implode(', ', $route->middleware()) }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        <!-- /.box-body -->
                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </section>
        <!-- /.content -->
    </div>





    <script type="text/javascript">
        function hideEmptyDomainColumn() {
            var table = document.querySelector('.table');
            var domains = table.querySelectorAll('tbody .domain');
            var emptyDomains = table.querySelectorAll('tbody .domain-empty');
            if (domains.length == emptyDomains.length) {
                table.className += ' hide-domains';
            }

            table.style.visibility = 'visible';
        }

        hideEmptyDomainColumn();
    </script>

@endsection
