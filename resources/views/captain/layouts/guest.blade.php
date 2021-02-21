<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title') - {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <link href="{{ mix('/css/admin/coreui.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/pace.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/alertifyjs.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/app.css') }}" rel="stylesheet">

    {{-- You can put page wise internal css style in styles section --}}
    @yield('styles')
</head>

<body class="app flex-row align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-group">
                    <div class="card p-4">

                        @if ($errors->all())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $message)
                                    <li>{{ $message }}</li>
                                @endforeach
                            </ul>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ mix('/js/admin/jquery.min.js') }}"></script>
    <script src="{{ mix('/js/admin/popper.min.js') }}"></script>
    <script src="{{ mix('/js/admin/bootstrap.min.js') }}"></script>
    <script src="{{ mix('/js/admin/pace.min.js') }}"></script>
    <script src="{{ mix('/js/admin/coreui.min.js') }}"></script>
    <script src="{{ mix('/js/admin/alertifyjs.js') }}"></script>
    <script src="{{ mix('/js/admin/app.js') }}"></script>

    {{-- You can put page wise javascript in scripts section --}}
    @stack('scripts')
</body>
</html>
