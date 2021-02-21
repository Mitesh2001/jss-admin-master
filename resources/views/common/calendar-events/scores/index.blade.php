@push('styles')
    <style>
        .dropdown.bootstrap-select {
            width: 500px !important;
        }
        .bootstrap-select .btn.dropdown-toggle.btn-light {
            background-color: #d1dbe1 !important;
        }

        @if ($calendarEvent->is_finalised)
            .is-finalised {
                display: none;
            }
        @endif
    </style>
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            <span>
                Attendance
            </span>
            | Scores - {{ $calendarEvent->discipline->label }} ({{ $calendarEvent->getFormattedDate() }})
        </div>
    </div>

    <div class="dual-list list-right col-md-12 mt-3">
        <div class="well">
            <form method="post"
                action="{{ route($storeUrl, ['calendar_event' => $calendarEvent->id]) }}"
                class="is-finalised"
            >
                @csrf

                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group">
                            <select class="selectpicker"
                                data-live-search="true"
                                name="individual_id"
                                required
                            >
                                @foreach($individuals as $individual)
                                    <option value="{{ $individual->id }}">
                                        {{ $individual->getFullName() }} -
                                        ({{ $individual->membership->membership_number }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 pl-0">
                        <div class="input-group">
                            <input type="text"
                                name="score"
                                class="form-control"
                                placeholder="Score"
                                onkeypress="return isValidScore(event, this)"
                                min="0"
                                required
                            >

                            @if($calendarEvent->score_type == 2)
                                <div class="pt-1 ml-3">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"
                                            id="score-unit-mm"
                                            name="score_unit"
                                            class="custom-control-input"
                                            value="1"
                                            checked
                                        >

                                        <label class="custom-control-label" for="score-unit-mm">
                                            MM
                                        </label>
                                    </div>

                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio"
                                            id="score-unit-inch"
                                            name="score_unit"
                                            class="custom-control-input"
                                            value="2"
                                            {{ old('score_unit') == 2 ? 'checked' : null }}
                                        >

                                        <label class="custom-control-label" for="score-unit-inch">
                                            Inch
                                        </label>
                                    </div>
                                </div>
                            @endif

                            <button type="submit" class="btn btn-success btn-sm ml-3">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="bg-white mt-5">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Individual Name</th>
                            <th>Membership Number</th>
                            <th>Score</th>
                            <th class="text-right">
                                @if ($calendarEvent->is_finalised)
                                    @if (auth()->user()->type == 1)
                                        <a class="btn btn-danger btn-sm"
                                            id="finalise-status-button"
                                            data-type="unfinalise"
                                            data-url="{{ route($unfinaliseUrl, ['calendar_event' => $calendarEvent->id]) }}"
                                        >
                                            Unfinalise
                                        </a>
                                    @else
                                        <h5 class="m-0">
                                            <span class="badge badge-success">
                                                Finalised
                                            </span>
                                        </h5>
                                    @endif
                                @else
                                    <a class="btn btn-warning btn-sm"
                                        id="finalise-status-button"
                                        data-type="finalise"
                                        data-url="{{ route($finaliseUrl, ['calendar_event' => $calendarEvent->id]) }}"
                                    >
                                        Finalise
                                    </a>
                                @endif
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($calendarEvent->scores as $score)
                            <tr>
                                <td>{{ $score->individual->getFullName() }}</td>

                                <td>{{ $score->individual->getMembershipNumber() }}</td>

                                <td>
                                    <strong>
                                        {{ $score->getFormattedScore($calendarEvent->score_type) }}
                                    </strong>
                                </td>

                                <td class="text-right">
                                    <form method="post"
                                        action="{{ route($deleteUrl, ['calendar_event' => $calendarEvent->id, 'score' => $score->id]) }}"
                                        class="inline pointer is-finalised"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <a class="dropdown-item d-inline delete-button pl-4">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).on('click', '#finalise-status-button', function (event) {
            event.preventDefault();
            var type = $(this).data('type');
            var url = $(this).data('url');

            var promptMessage = 'Are you sure you want to ' + type + ' these scores?';

            bootbox.confirm({
                size: "medium",
                message: promptMessage,
                callback: function(result) {
                    if (result === true) {
                        window.location = url;
                    }
                }
            });
        });

        function isValidScore(evt, element) {
            var charCode = (evt.which) ? evt.which : evt.keyCode;

            if (charCode == 46) {
                return true;
            }

            return isNumber(evt, element);
        }
    </script>
@endpush
