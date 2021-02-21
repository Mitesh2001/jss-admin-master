@section('title', 'Google Two Factor Authentication')

@section('content')
    <div class="card-body">
        <h1 class="text-center">Two-Factor Authentication</h1>

        <p class="text-muted text-center">Scan QR code to your mobile and type the code below to login.</p>

        <form method="post">
            @csrf

            <div class="input-group mb-3 has-feedback">
                <input type="text"
                    name="qr_code"
                    class="form-control"
                    placeholder="Code"
                    required
                    autofocus
                >
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">
                    Sign In
                </button>
            </div>
        </form>
    </div>
@endsection
