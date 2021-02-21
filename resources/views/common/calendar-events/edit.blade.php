@section('content')
<form method="post"
    class="form"
    action="{{ route($updateUrl, ['calendar_event' => $calendarEvent->id]) }}"
>
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-9 h3">
            <span class="text-muted">
                Events
            </span>
            | Edit
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
        @include('common.calendar-events.form_input')
    </div>
</form>
@endsection
