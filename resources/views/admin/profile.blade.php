@extends('admin.layouts.app', ['page' => ''])

@section('title', 'Profile')

@push('styles')
    <style>
        .loader {
            border: 6px solid #f3f3f3;
            border-top: 6px solid #393f44;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
<div class="row mb-5">
    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 pt-2 h5">
                        <i class="fa fa-tint"></i>
                        Update Profile
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <form method="post">
                    @csrf

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text"
                            name="username"
                            class="form-control"
                            id="username"
                            placeholder="Username"
                            value="{{ old('username', $user->username) }}"
                        >
                    </div>

                    <button type="submit" class="btn btn-sm btn-primary">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-12 col-md-6">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6 pt-2 h5">
                        <i class="fa fa-tint"></i>
                        Two-Factor Authentication
                    </div>
                </div>
            </div>

            <div class="card-body p-3">
                <h5 class="{{ $user->google2fa_secret ? 'text-success' : 'text-danger' }}">
                    Two-Factor authentication is
                    {{ $user->google2fa_secret ? 'activated' : 'inactive' }}
                    for your account.
                </h5>

                @if($user->google2fa_secret)
                    <form method="post"
                        class="text-center"
                        action="{{ route('admin.disabled_google_two_factor') }}"
                    >
                        @csrf

                        <button type="submit" class="btn btn-outline-danger mt-3">
                            Disabled Two-Factor Authentication
                        </button>
                    </form>
                @else
                    <div class="text-center">
                        <button type="button" class="btn btn-outline-dark mt-3" id="two-factor-auth-button">
                            Setup Two-Factor Authentication
                        </button>

                        <div id="loading" class="d-none">
                            <div class="loader d-inline-block mt-3"></div>
                        </div>

                        <div id="google-two-factor" class="d-none">
                            <form method="post" action="{{ route('admin.verify_google_two_factor') }}">
                                @csrf

                                <img src="" class="mb-2" alt="Google two factor authentication">

                                <div class="form-group">
                                    <label for="qr-code">Enter Verification Code</label>

                                    <input type="text"
                                        id="qr-code"
                                        name="qr_code"
                                        class="form-control"
                                        placeholder="Code"
                                        required
                                        autofocus
                                    >
                                </div>

                                <button type="submit" class="btn btn-sm btn-primary px-4">
                                    Verify Code And Activate
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Password update --}}
<div class="card">
    <div class="card-header">
        <div class="row">
            <div class="col-6 pt-2 h5">
                <i class="fa fa-tint"></i>
                Change Password
            </div>
        </div>
    </div>

    <div class="card-body p-3">
        <form method="post" action="{{ route('admin.password_update') }}">
            @csrf

            <div class="form-group">
                <label for="current-password">Current Password</label>
                <input type="password"
                    name="current_password"
                    class="form-control"
                    id="current-password"
                    placeholder="Current Password"
                    pattern=".{6,}"
                    title="6 characters minimum"
                >
            </div>

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password"
                    name="password"
                    class="form-control"
                    id="password"
                    placeholder="New Password"
                    pattern=".{6,}"
                    title="6 characters minimum"
                >
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input type="password"
                    name="password_confirmation"
                    class="form-control"
                    id="confirm-password"
                    placeholder="Confirm Password"
                    pattern=".{6,}"
                    title="6 characters minimum"
                >
            </div>

            <button type="submit" class="btn btn-sm btn-primary">
                Change Password
            </button>
        </form>
    </div>
</div>
@endsection


@push('scripts')
    <script>
        $('#two-factor-auth-button').click(function () {
            $('#loading').removeClass('d-none');

            axios.get("{{ route('admin.google_two_factor') }}")
            .then(function (response) {
                $('#google-two-factor img').attr('src', response.data);
                $('#loading').addClass('d-none');
                $('#google-two-factor').removeClass('d-none');
            });
        })
    </script>
@endpush
