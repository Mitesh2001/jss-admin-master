<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>@yield('title') - {{ config('app.name') }}</title>
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
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <span class="navbar-brand-full">
                <i class="fa fa-car" aria-hidden="true"></i>
                JSS Admin
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
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="" role="button" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-th-large" aria-hidden="true"></i>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <div class="dropdown-header text-center">
                        <strong>Settings</strong>
                    </div>

                    <a class="dropdown-item" href="{{ route('admin.profile') }}">
                        <i class="fa fa-user"></i>
                        Profile
                    </a>

                    <a class="dropdown-item" href="{{ route('admin.logout') }}">
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
                    <img src="{{ asset('images/admin-avatar.png') }}" class="img-avatar" alt="Admin avatar" width="30">
                    {{ auth()->user()->individual->getName() }}
                </span>
            </a>

            <nav class="sidebar-nav">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'dashboard' ? ' active' : '' }}"
                            href="{{ route('admin.dashboard') }}"
                        >
                            <i class="fa fa-tachometer"></i>
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'individual' ? ' active' : '' }}"
                            href="{{ route('admin.individuals.index') }}"
                        >
                            <i class="fa fa-user"></i>
                            Individuals
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'family' ? ' active' : '' }}"
                            href="{{ route('admin.families.index') }}"
                        >
                            <i class="fa fa-users"></i>
                            Families
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'receipt' ? ' active' : '' }}"
                            href="{{ route('admin.receipts.index') }}"
                        >
                            <i class="fa fa-file-text"></i>
                            Receipts
                        </a>
                    </li>

                    <li class="nav-item nav-dropdown{{ in_array($page, ['renewal_runs', 'renewal_submissions']) ? ' open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa fa-refresh"></i>
                            Renewals
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('admin.renewal-runs.index') }}"
                                    class="nav-link{{ $page == 'renewal_runs' ? ' active' : '' }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Renewal Runs
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.individual_renewal_submissions') }}"
                                    class="nav-link{{ $page == 'renewal_submissions' ? ' active' : '' }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Renewal Submissions
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown{{ $page == 'id_card' || $page == 'printed_id_cards' ? ' open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa fa-id-badge"></i>
                            Identification
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link{{ $page == 'id_card' ? ' active' : '' }}"
                                    href="{{ route('admin.id_cards.index') }}"
                                >
                                    <i class="fa fa-id-card-o"></i>
                                    ID Cards
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link{{ $page == 'printed_id_cards' ? ' active' : '' }}"
                                    href="{{ route('admin.id_cards.printed') }}"
                                >
                                    <i class="fa fa-id-card"></i>
                                    Printed ID Cards
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'discipline' ? ' active' : '' }}"
                            href="{{ route('admin.disciplines.index') }}"
                        >
                            <i class="fa fa-gavel"></i>
                            Disciplines
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'captain' ? ' active' : '' }}"
                            href="{{ route('admin.users.index') }}"
                        >
                            <i class="fa fa-graduation-cap"></i>
                            Captains
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link{{ $page == 'attendance' ? ' active' : '' }}"
                            href="{{ route('admin.calendar-events.index') }}"
                        >
                            <i class="fa fa-thumbs-up"></i>
                            Events
                        </a>
                    </li>

                    <li class="nav-item nav-dropdown{{ in_array($page, ['firearms', 'keys', 'range_officers']) ? ' open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa fa-files-o"></i>
                            Registers
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a class="nav-link{{ $page == 'firearms' ? ' active' : '' }}"
                                    href="{{ route('admin.firearms.index') }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Firearms
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link{{ $page == 'keys' ? ' active' : '' }}"
                                    href="{{ route('admin.keys.index') }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Keys
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link{{ $page == 'range_officers' ? ' active' : '' }}"
                                    href="{{ route('admin.range_officers.index') }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Range Officers
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link">
                                    <i class="fa fa-circle-o"></i>
                                    Committee
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item nav-dropdown{{ in_array($page, ['payment_report', 'members_report', 'wwc_cards_report']) ? ' open' : '' }}">
                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i class="fa fa-file-text"></i>
                            Reports
                        </a>

                        <ul class="nav-dropdown-items">
                            <li class="nav-item">
                                <a href="{{ route('admin.reports.payments') }}"
                                    class="nav-link{{ $page == 'payment_report' ? ' active' : '' }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Payments
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.reports.members') }}"
                                    class="nav-link{{ $page == 'members_report' ? ' active' : '' }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    Members
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.reports.wwc_cards') }}"
                                    class="nav-link{{ $page == 'wwc_cards_report' ? ' active' : '' }}"
                                >
                                    <i class="fa fa-circle-o"></i>
                                    WWC Cards
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="fa fa-cog"></i>
                            Configurations
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.3.2/bootbox.min.js"></script>
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

        $(document).ready(function() {
            if ($.fn.dataTable) {
                $.extend($.fn.dataTable.defaults, {
                    pageLength: "{{ session('admin.datatables.pageLength', 10) }}",
                    language: {
                        search: ""
                    },
                });

                $(document).on('click', 'tbody tr', function (event) {
                    if ($(this).data('editUrl') && event.target.tagName == 'TD') {
                        window.location = $(this).data().editUrl;
                    }
                });
            }
        });
    </script>

    {{-- You can put page wise javascript in scripts section --}}
    @stack('scripts')

    <script>
        $(document).ready(function() {
            if ($('.dataTables_filter')) {
                $('.dataTables_filter label').append('<span class="fa fa-search form-control-feedback datatable-search-input"></span>');
            }
        });
    </script>
</body>
</html>
