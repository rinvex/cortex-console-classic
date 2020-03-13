{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('head-elements')
    <meta name="turbolinks-cache-control" content="no-cache">
@endpush

@push('styles')
    <link href="{{ mix('css/cortex-console.css') }}" rel="stylesheet">
@endpush

@push('vendor-scripts')
    <script src="{{ mix('js/cortex-console.js') }}" defer></script>
@endpush

@push('inline-scripts')
    <script>
        window.addEventListener('turbolinks:load', function() {
            $('#terminal-shell').slimScroll({
                height: $('.content-wrapper').height() - 207 +'px'
            });

            (function() {
                new Terminal("#terminal-shell", {!! $options !!});
            })();
        });
    </script>
@endpush

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="row">

                <div class="col-md-12">

                    <div class="box">

                        <div class="box-header">
                            <h3 class="box-title">
                                {{ trans('cortex/console::common.terminal') }}
                            </h3>
                        </div>

                        <div class="box-body">
                            <div id="terminal-shell"></div>
                        </div>

                    </div>

                </div>

            </div>

        </section>

    </div>


@endsection
