@extends('admin.layouts.app', ['page' => 'individual'])

@section('title', 'Individuals')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Individuals
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" onclick="window.location = '{{ route('admin.individuals.create') }}'">
                    Create
                </button>
            </div>

            <div class="form-group d-inline-block">
                <select name="filter" id="filter" class="form-control">
                    <option value="active_only">Active Only</option>
                    <option value="inactive_only"
                        {{ session('individuals_filter') == 'inactive_only' ? 'selected' : '' }}
                    >
                        Inactive Only
                    </option>

                    <option value="all"
                        {{ session('individuals_filter') == 'all' ? 'selected' : '' }}
                    >
                        All
                    </option>
                </select>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="individuals-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Branch Code</th>
                        <th>SSAA Number</th>
                        <th>Status</th>
                        <th>Membership</th>
                        <th>Email</th>
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
            $('#individuals-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.individuals.datatables') }}",
                columns: [
                    { name: 'name' },
                    { name: 'branchCode.label', orderable: false },
                    { name: 'ssaa.ssaa_number', orderable: false },
                    { name: 'membership.status', orderable: false, searchable: false },
                    { name: 'membership.type', orderable: false, searchable: false },
                    { name: 'email_address' },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });

            $('#filter').change(function(e) {
                window.location.href = "{{ route('admin.individuals.filter') }}/" + $(this).val();
            });

            $('#individuals-table').on('click', 'a.add-to-renewal-run', function() {
                var individualId = $(this).data('individualId');

                bootbox.confirm({
                    size: "medium",
                    message: "Do you want to this individual to current renewal run?",
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'get',
                                url: "{{ route('admin.add_to_renewal_run') }}/" + individualId
                            }).then(function(response) {
                                showNotice('success', 'Individual added to the current renewal run successfully.');
                            }).catch(function(response) {
                                showNotice('error', 'An error occurred. Have you added this individual to the list already?');
                            });
                        }
                    }
                });
            });

            $('#individuals-table').on('click', 'a.change-id-card-status', function() {
                var message = 'Do you want to add this individual to the list of ID Cards to be printed?'
                var individualId = $(this).attr('data-individual-id');
                var status = $(this).attr('data-status');
                var isPrintRun = $(this).attr('data-is-printrun');

                if (status == 1) {
                    message = 'Do you want to remove this individual from the list of ID Cards to be printed?'
                }

                if (isPrintRun > 0) {
                    message = 'Do you want to remove this individual from the list of ID Cards to be printed and Printrun?'
                }

                bootbox.confirm({
                    size: "medium",
                    message: message,
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.individuals.update_id_card') }}",
                                data: {
                                    individual_id: individualId,
                                    status: status,
                                }
                            }).then(function(response) {
                                var message = status == 1 ? 'Individual removed from the ID Card print list successfully.' : 'Individual added to the ID Card print list successfully.';
                                showNotice('success', message);
                                $('#individuals-table').DataTable().ajax.reload();
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
