{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ config('app.name') }} » {{ trans('cortex/foundation::common.adminarea') }} » {{ trans('cortex/console::common.console') }} » {{ trans('cortex/console::common.routes') }}
@stop

@push('styles')
    <link href="{{ mix('css/terminal.css') }}" rel="stylesheet">
@endpush

@push('scripts-vendor')
    <script src="{{ mix('js/terminal.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script>
        $('#terminal-shell').slimScroll({
            height: $('.content-wrapper').height() - 207 +'px'
        });

        (function() {
            new Terminal("#terminal-shell", {!! $options !!});
        })();
    </script>
@endpush

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
                                {{ trans('cortex/console::common.terminal') }}
                            </h3>
                        </div>
                        <!-- /.box-header -->
                        <div class="box-body">

                            <div id="terminal-shell"></div>

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

@endsection
