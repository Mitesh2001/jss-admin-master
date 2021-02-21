@section('content')
<form method="post" class="form" action="{{ route($storeUrl) }}">
    @csrf

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Events
            </span>
            | Add new
        </div>

        <div class="col-3 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm">
                    Save
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        @include('common.calendar-events.form_input', [
            'calendarEvent' => optional()
        ])
    </div>
</form>
@endsection
