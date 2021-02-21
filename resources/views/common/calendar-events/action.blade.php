<div class="dropdown">
    <button class="btn btn-secondary dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
        <i class="fa fa-ellipsis-h"></i>
    </button>

    <div class="dropdown-menu">
        @if ($calendarEvent->is_attendance_tracked)
            <a class="dropdown-item"
                href="{{ route($viewUrl, ['calendar_event' => $calendarEvent->id]) }}"
            >
                <i class="fa fa-file"></i>
                Attendance
            </a>
        @endif

        <a class="dropdown-item"
            href="{{ route($editUrl, ['calendar_event' => $calendarEvent->id]) }}"
        >
            <i class="fa fa-edit"></i>
            Edit
        </a>

        <form method="post"
            action="{{ route($deleteUrl, ['calendar_event' => $calendarEvent->id]) }}"
            class="inline pointer"
        >
            @csrf
            @method('DELETE')

            <a class="dropdown-item delete-button">
                <i class="fa fa-trash"></i> Delete
            </a>
        </form>
    </div>
</div>
