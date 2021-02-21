<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>{{ config('app.name') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport'>
    <meta name="viewport" content="width=device-width">

    <link rel="stylesheet"
        href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T"
        crossorigin="anonymous"
    >
    <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/css/alertify.min.css">
    <link rel="stylesheet" href="https://unpkg.com/vue-select@latest/dist/vue-select.css">

    <style>
        body {
            background-color: #f1f1f2;
        }
        #wrapper-box {
            height: 800px;
            display: table;
            width: 100%;
        }
        .wrapper-child-box {
            display: table-cell;
            vertical-align: middle;
            border: 1px solid #cecaca;
            border-radius: 6px;
            background: white;
        }
        .page-title {
            font-size: 20px;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 5px;
            color: #5b5b5b;
        }
        .login-footer-text {
            font-size: 15px;
            font-style: italic;
            color: #a7a8a8;
        }
        .link {
            color: #338bc8;
        }
        .button-color {
            background-color: #338bc8;
        }
        .member-form input {
            height: 55px;
            border: 2px solid cecaca;
        }
        .member-form label {
            font-weight: 600;
            font-size: 18px;
            color: #5b5b5b;
            margin: 0;
        }

        .input-label {
            background-color: #ededee !important;
        }
        .input-label > div {
            color: #989898 !important;
            font-size: 20px;
        }
        .font-md {
            font-size: 19px;
        }
        .member-details-form input {
            margin-bottom: 10px !important;
            border-radius: 2px !important;
        }
        .v-datepicker-input {
            border: 0;
            padding: 0;
        }
        .basic-calender-addon {
            height: calc(1.5em + .75rem + 2px);
        }
        .member-details-form input[type=search] {
            margin-bottom: 0px !important;
        }
        #suburb-selection .dropdown-toggle::after {
            display: none !important;
        }
        #ssaa-expiry {
            background-color: #fff !important;
        }
        .bold {
            font-weight: 600;
        }
        .bg-striped {
            background-color: rgba(0,0,0,.05);
        }
        [v-cloak] {
            display: none;
        }
        .grecaptcha-badge {
            visibility: hidden;
        }
        .number-input::-webkit-inner-spin-button,
        .number-input::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        #attendances-container .wrapper-child-box {
            vertical-align: top;
        }
        .pointer {
            cursor: pointer;
        }
        .pointer.disabled {
            cursor: not-allowed;
        }
        .pointer.active {
            cursor: auto;
        }
        .responsive-attendances {
            overflow-x: scroll;
        }
    </style>
</head>

<body>
    <div class="container" id="application">
        <div v-if="isLoggedIn" class="mb-3" v-cloak>
            <div class="text-center mt-md-0 mt-lg-0">
                <button class="btn d-none d-sm-inline-block float-md-right py-0 text-muted" @click="logout">
                    LOGOUT
                </button>

                <button class="btn float-md-right py-0 bold"
                    v-show="currentPage == 'attendance'"
                    @click="changePage('member-details')"
                >
                    MEMBERSHIP
                </button>

                <button class="btn float-md-right py-0 bold"
                    v-show="currentPage == 'member-details'"
                    @click="changePage('attendance')"
                >
                    ATTENDANCE
                </button>

                <button class="btn d-inline-block d-sm-none float-md-right py-0 text-muted" @click="logout">
                    LOGOUT
                </button>
            </div>
        </div>

        @include('front.auth.register')

        @include('front.auth.verify_email')

        @include('front.auth.login')

        @include('front.auth.forgot_password')

        @include('front.auth.choose_password')

        @include('front.auth.member_details')

        @include('front.auth.attendance')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.8/dist/vue.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/vue-select@latest"></script>
    <script src="https://unpkg.com/vuejs-datepicker"></script>
    <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.2/build/alertify.min.js"></script>
    <script src="https://rawgit.com/ratiw/vuetable-2/master/dist/vuetable-2-full.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.4/lodash.min.js"></script>
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.google.recaptcha_site_key') }}"></script>

    @include('front.auth.vue_scripts')

	@stack('scripts')
</body>
</html>
