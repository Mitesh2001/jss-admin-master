<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Captain - @yield('title') | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="{{ mix('/css/admin/coreui.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/pace.min.css') }}" rel="stylesheet">
    <link href="{{ mix('/css/admin/alertifyjs.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">

    <link href="{{ mix('/css/admin/app.css') }}" rel="stylesheet">

    {{-- You can put page wise internal css style in styles section --}}
    @stack('styles')
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
    {{-- Header --}}
    <header class="app-header navbar">
        <button class="navbar-toggler sidebar-toggler d-lg-none mr-auto" type="button" data-toggle="sidebar-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{--  Logo  --}}
        <a href="{{ route('captain.dashboard') }}" class="navbar-brand">
            <span class="navbar-brand-full">
                <i class="fa fa-car" aria-hidden="true"></i>
                JSS Captain
            </span>

            <span class="navbar-brand-minimized">
                <img src="{{ asset('images/logo.png') }}" class="img-avatar" alt="logo" width="30">
            </span>
        </a>

        <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button" data-toggle="sidebar-lg-show">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{--  Header Navbar  --}}
        <ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
                <i class="fa fa-comments-o" aria-hidden="true"></i>
                <span class="badge badge-danger">5</span>
            </li>

            <li class="nav-item">
                <i class="fa fa-bell-o" aria-hidden="true"></i>
                <span class="badge badge-warning">3</span>
            </li>

            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-th-large" aria-hidden="true"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Settings</strong>
                    </div>

                    <a class="dropdown-item" href="{{ route('captain.profile') }}">
                        <i class="fa fa-user"></i>
                        Profile
                    </a>

                    <a class="dropdown-item" href="{{ route('captain.logout') }}">
                        <i class="fa fa-lock"></i>
                        Logout
                    </a>
                </div>
            </li>
        </ul>
    </header>

    <div class="app-body">
        {{--  Sidebar  --}}
        <div class="sidebar">
            <a class="navbar-brand">
                <span class="navbar-brand-full">
                    <img src="{{ asset('images/admin-avatar.png') }}" class="img-avatar" alt="Captain avatar" width="30">
                    {{ auth()->user()->individual->getName() }}
                </span>
            </a>

            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'dashboard' ? ' active' : '' }}"
                            href="{{ route('captain.dashboard') }}"
                        >
                            <i class="fa fa-tachometer"></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'attendance' ? ' active' : '' }}"
                            href="{{ route('captain.calendar-events.index') }}"
                        >
                            <i class="fa fa-thumbs-up"></i>
                            Events
                        </a>
                    </li>
                </ul>
            </nav>
        </div>

        <main class="main mt-4">
            <div class="container-fluid">
                <div class="animated fadeIn">
                    @if ($errors->all())
                        <ul class="alert alert-danger">
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    @endif

                    {{--  Page Content  --}}
                    @yield('content')
                </div>
            </div>
        </main>
    </div>

    <script src="{{ mix('/js/admin/jquery.min.js') }}"></script>
    <script src="{{ mix('/js/admin/popper.min.js') }}"></script>
    <script src="{{ mix('/js/admin/bootstrap.min.js') }}"></script>
    <script src="{{ mix('/js/admin/pace.min.js') }}"></script>
    <script src="{{ mix('/js/admin/coreui.min.js') }}"></script>
    <script src="{{ mix('/js/admin/alertifyjs.js') }}"></script>
    <script src="{{ mix('/js/admin/axios.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <script src="{{ mix('/js/admin/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    @if (session('message'))
        <script>
            showNotice("{{ session('type') }}", "{{ session('message') }}");
        </script>
    @endif

    <script>
        var datedAt = $("input[type=date]").flatpickr({
            altInput: true,
            altFormat: "j F Y",
            dateFormat: "Y-m-d",
        });
    </script>

    {{-- You can put page wise javascript in scripts section --}}
    @stack('scripts')
</body>
</html>
