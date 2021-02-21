@if (! $calendarEvent->is_attendance_tracked)
    N/A
@else
    <h5>
        @if ($calendarEvent->is_finalised)
            <span class="badge badge-success">Finalised</span>
        @else
            <span class="badge badge-danger">
                Not finalised
            </span>
        @endif
    </h5>
@endif
