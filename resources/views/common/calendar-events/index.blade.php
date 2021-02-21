@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Events
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="custom-control custom-checkbox d-inline-block mr-3">
                <input type="hidden" name="historical_finalised_events" value="0">

                <input type="checkbox"
                    class="custom-control-input"
                    value="1"
                    id="historical-finalised-events"
                    name="historical_finalised_events"
                    {{ session('historical_finalised_events') == 'true' ? 'checked' : '' }}
                >

                <label class="custom-control-label" for="historical-finalised-events">
                    Hide historical finalised events
                </label>
            </div>

            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" onclick="window.location = '{{ route($createUrl) }}'">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="attendance-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Discipline</th>
                        <th>Attendance tracked</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                {{-- Table data will be fetched by datatables ajax --}}
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $( document ).ready(function() {
            $('#historical-finalised-events').change(function() {
                var status = $(this).is(":checked") ? 'true' : 'false';
                $.ajax({
                    method: "post",
                    url: "{{ route($filterUrl) }}",
                    data: {
                        'historical_finalised_events': status,
                        _token: "{{ csrf_token() }}",
                    }
                }).done(function( receiptTotalAmount ) {
                    document.location.reload(true);
                    showNotice("success", "Filter updates successfully.");
                });
            });

            $('#attendance-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route($datatableUrl) }}",
                columns: [
                    { name: 'event_date' },
                    { name: 'discipline.label', orderable: false },
                    { name: 'attendance_tracked', orderable: false, searchable: false },
                    { name: 'status', orderable: false, searchable: false },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });
        });
    </script>
@endpush
