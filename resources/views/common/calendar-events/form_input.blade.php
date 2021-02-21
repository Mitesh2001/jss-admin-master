<div class="col-12 mb-5">
    <div class="card">
        <div class="card-body p-3">
            <h5>Event Details</h5>

            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="discipline">Discipline:</label>

                        <select id="discipline" name="discipline_id" class="form-control" required>
                            <option value="" selected>Please select Discipline</option>

                            @foreach($disciplines as $discipline)
                                <option
                                    value="{{ $discipline->id }}"
                                    {{
                                        old('discipline_id', $calendarEvent->discipline_id) == $discipline->id ? 'selected' : ''
                                    }}
                                >
                                    {{ $discipline->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="input-group">
                        <label for="event-time">Event Date:</label>

                        <input id="event-time"
                            type="date"
                            name="event_date"
                            value="{{ old('event_date', $calendarEvent->event_date) }}"
                            class="form-control"
                            data-input
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" id="btnGroupAddon" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 mt-4">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="is_attendance_tracked" value="0">
                        <input type="checkbox"
                            name="is_attendance_tracked"
                            class="custom-control-input"
                            id="attendance-tracked"
                            value="1"
                            {{ old('is_attendance_tracked', $calendarEvent->is_attendance_tracked) ? 'checked' : '' }}
                        >
                        <label class="custom-control-label" for="attendance-tracked">Attendance tracked</label>
                    </div>

                    <div class="pl-4" id="score-types-container">
                        <label>Score Type:</label>

                        <div class="form-group">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio"
                                    id="point-based"
                                    name="score_type"
                                    class="custom-control-input"
                                    value="1"
                                    checked
                                >

                                <label class="custom-control-label" for="point-based">
                                    Point based
                                </label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio"
                                    id="deviation-based"
                                    name="score_type"
                                    class="custom-control-input"
                                    value="2"
                                    {{ old('score_type', $calendarEvent->score_type) == 2 ? 'checked' : null }}
                                >

                                <label class="custom-control-label" for="deviation-based">
                                    Deviation based
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-3">
                    <div class="custom-control custom-checkbox">
                        <input type="hidden" name="is_public" value="0">
                        <input type="checkbox"
                            name="is_public"
                            class="custom-control-input"
                            id="is-public"
                            value="1"
                            {{ old('is_public', $calendarEvent->is_public) ? 'checked' : '' }}
                        >

                        <label class="custom-control-label" for="is-public">Show on public calendar</label>
                    </div>
                </div>

                <div class="col-12" id="public-details-container">
                    <div class="row mt-3">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="title">Title:</label>

                                <input type="text"
                                    name="title"
                                    id="title"
                                    class="form-control"
                                    maxlength="25"
                                    value="{{ old('title', $calendarEvent->title) }}"
                                    required
                                >
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="start-time">Start Time:</label>

                                <input type="time"
                                    name="start_time"
                                    class="form-control"
                                    id="start-time"
                                    value="{{ old('start_time', $calendarEvent->start_time) }}"
                                    required
                                >
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>

                        <input type="text"
                            name="description"
                            id="description"
                            class="form-control"
                            maxlength="255"
                            value="{{ old('description', $calendarEvent->description) }}"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            updateScoreType();
            updatePublicDetails();

            $("#start-time").flatpickr({
                enableTime: true,
                noCalendar: true,
                dateFormat: "h:i",
            });

            $('#attendance-tracked').change(function () {
                updateScoreType();
            });
            $('#is-public').change(function () {
                updatePublicDetails();
            });
        });

        function updateScoreType() {
            if ($('#attendance-tracked:checked').val()) {
                $('#score-types-container').slideDown();
                return;
            }

            $('#score-types-container').slideUp();
        }

        function updatePublicDetails() {
            if ($('#is-public:checked').val()) {
                $('#public-details-container').slideDown();
                $('#start-time').prop('required', true);
                $('#title').prop('required', true);
                return;
            }

            $('#start-time').prop('required', false);
            $('#title').prop('required', false);
            $('#public-details-container').slideUp();
        }
    </script>
@endpush
