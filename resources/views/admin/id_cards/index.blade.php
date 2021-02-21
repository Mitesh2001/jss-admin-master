@extends('admin.layouts.app', ['page' => 'id_card'])

@section('title', 'ID Cards')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-2 h3" style="color: #5a5b5d;">
            ID Cards
        </div>

        <div class="col-10 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <select name="filter" id="filter" class="form-control">
                    <option value="all">All</option>

                    <option value="non_queued_only"
                        {{ session('id_card_queue_filter') == 'non_queued_only' ? 'selected' : '' }}
                    >
                        Non-Queued Only
                    </option>

                    <option value="queued_only"
                        {{ session('id_card_queue_filter') == 'queued_only' ? 'selected' : '' }}
                    >
                        Queued Only
                    </option>
                </select>
            </div>

            <div class="dropdown d-inline-block">
                <button class="btn btn-success btn-sm dropdown-toggle"
                    type="button"
                    id="add-to-printrun"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Add to Printrun
                </button>

                <div class="dropdown-menu" aria-labelledby="add-to-printrun">
                    <button class="dropdown-item pointer"
                        id="send-all-to-print-run"
                    >Add all (count of all records)</button>

                    <button class="dropdown-item pointer"
                        id="send-to-print-run"
                        disabled
                    >Add selected</button>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button class="btn btn-warning btn-sm dropdown-toggle"
                    type="button"
                    id="add-to-printrun"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Remove from Printrun
                </button>

                <div class="dropdown-menu" aria-labelledby="add-to-printrun">
                    <button class="dropdown-item pointer"
                        id="remove-all-from-print-run"
                    >Remove all (count of all records)</button>

                    <button class="dropdown-item pointer"
                        id="remove-from-print-run"
                        disabled
                    >Remove selected</button>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button class="btn btn-dark btn-sm dropdown-toggle"
                    type="button"
                    id="add-to-printrun"
                    data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                >
                    Mark as Printed
                </button>

                <div class="dropdown-menu" aria-labelledby="add-to-printrun">
                    <button class="dropdown-item pointer"
                        id="mark-all-as-printed"
                    >Mark all (count of all records)</button>

                    <button class="dropdown-item pointer"
                        id="mark-as-printed"
                        disabled
                    >Mark selected</button>
                </div>
            </div>

            <div class="form-group d-inline-block">
                <button class="btn btn-danger btn-sm mb-2" id="clear-print-run">
                    Clear Printrun Queue
                </button>
            </div>

            <div class="form-group d-inline-block">
                <a class="btn btn-info btn-sm" href="{{ route('admin.id_cards.export_to_csv') }}" target="_blank">
                    Export Print Run
                </a>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="id-cards-table">
                <thead>
                    <tr>
                        <th>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                    id="select-all-checkbox"
                                    class="custom-control-input"
                                >
                                <label class="custom-control-label" for="select-all-checkbox">
                                    All
                                    <span id="count-of-selected-checkbox">(0)</span>
                                </label>
                            </div>
                        </th>
                        <th>Name</th>
                        <th>Branch Code</th>
                        <th>SSAA Number</th>
                        <th>Status</th>
                        <th>Membership</th>
                        <th>Expiry</th>
                        <th>Added to Printrun?</th>
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
        $(document).ready(function() {
            $('#id-cards-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.id_cards.datatables') }}",
                columns: [
                    { name: 'select_action', orderable: false, searchable: false },
                    { name: 'name' },
                    { name: 'branchCode.label', orderable: false },
                    { name: 'ssaa.ssaa_number', orderable: false },
                    { name: 'membership.status', orderable: false, searchable: false },
                    { name: 'membership.type', orderable: false, searchable: false },
                    { name: 'membership.expiry', orderable: false, searchable: false },
                    { name: 'print_run', orderable: false, searchable: false },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 1, "asc" ]]
            });

            $('#filter').change(function(e) {
                window.location.href = "{{ route('admin.id_cards.filter') }}/" + $(this).val();
            });

            $('#id-cards-table').on('click', 'a.remove-individual', function() {
                var individualId = $(this).attr('data-individual-id');
                var id = $(this).attr('data-id');
                var isPrintRun = $(this).attr('data-is-printrun');
                var message = 'Do you want to remove this individual from ID Cards print list?';

                if (isPrintRun > 0) {
                    message = 'Do you want to remove this individual from ID Cards print list? The entry is already added to the Printrun.'
                }

                bootbox.confirm({
                    size: "medium",
                    message: message,
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.remove') }}",
                                data: {
                                    id: id,
                                    individual_id: individualId
                                }
                            }).then(function(response) {
                                showNotice('success', 'Individual removed from the ID Cards print list successfully.');

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#select-all-checkbox').on('click', function() {
                if ($(this).prop('checked')) {
                    $('#id-cards-table .select-checkbox').prop('checked', true);
                    $('#send-to-print-run').prop('disabled', false);
                    $('#remove-from-print-run').prop('disabled', false);
                    $('#mark-as-printed').prop('disabled', false);
                    $('#count-of-selected-checkbox').html('('+$('#id-cards-table .select-checkbox:checked').length+')');
                    return;
                }

                $('#send-to-print-run').prop('disabled', true);
                $('#remove-from-print-run').prop('disabled', true);
                $('#mark-as-printed').prop('disabled', true);
                $('#id-cards-table .select-checkbox').prop('checked', false);
                $('#count-of-selected-checkbox').html('('+$('#id-cards-table .select-checkbox:checked').length+')');
            });

            $('#id-cards-table').on('click', '.select-checkbox', function() {
                if ($('.select-checkbox:checked').length) {
                    $('#send-to-print-run').prop('disabled', false);
                    $('#remove-from-print-run').prop('disabled', false);
                    $('#mark-as-printed').prop('disabled', false);
                    $('#count-of-selected-checkbox').html('('+$('#id-cards-table .select-checkbox:checked').length+')');
                } else {
                    $('#send-to-print-run').prop('disabled', true);
                    $('#remove-from-print-run').prop('disabled', true);
                    $('#mark-as-printed').prop('disabled', true);
                    $('#count-of-selected-checkbox').html('('+$('#id-cards-table .select-checkbox:checked').length+')');
                }

                var allSelected = true;
                $('.select-checkbox').each(function () {
                    if (! $(this).prop('checked')) {
                        allSelected = false;
                    }
                });

                if (allSelected) {
                    $('#select-all-checkbox').prop('checked', true);
                    return;
                }

                $('#select-all-checkbox').prop('checked', false);
            });

            $('#send-to-print-run').on('click', function() {
                var selectedIds = $('.select-checkbox').map(function() {
                    if ($(this).prop('checked')) {
                        return $(this).val();
                    }
                }).get();

                if (! selectedIds.length) {
                    showNotice('error', 'Please select at least one individual.')
                    return;
                }

                bootbox.confirm({
                    size: "medium",
                    message: 'Do you want to add these ' + selectedIds.length + ' selected individuals to the Printrun?',
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.send_to_print_run') }}",
                                data: {
                                    id_card_ids: selectedIds
                                }
                            }).then(function(response) {
                                showNotice('success', 'Selected ID Cards added to the Printrun successfully.');

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#send-all-to-print-run').on('click', function() {
                bootbox.confirm({
                    size: "medium",
                    message: 'Do you want to add all individuals to the Printrun?',
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.send_to_print_run') }}",
                            }).then(function(response) {
                                showNotice('success', 'All ID Cards added to the Printrun successfully.');

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#remove-from-print-run').on('click', function() {
                var selectedIds = $('.select-checkbox').map(function() {
                    if ($(this).prop('checked')) {
                        return $(this).val();
                    }
                }).get();

                if (! selectedIds.length) {
                    showNotice('error', 'Please select at least one individual.')
                    return;
                }

                bootbox.confirm({
                    size: "medium",
                    message: 'Do you want to remove these ' + selectedIds.length + ' selected individuals from the Printrun?',
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.remove_from_print_run') }}",
                                data: {
                                    id_card_ids: selectedIds
                                }
                            }).then(function(response) {
                                showNotice(response.data.status, response.data.message);

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#remove-all-from-print-run').on('click', function() {
                bootbox.confirm({
                    size: "medium",
                    message: 'Do you want to remove All individuals from the Printrun?',
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.remove_from_print_run') }}",
                            }).then(function(response) {
                                showNotice(response.data.status, response.data.message);

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#mark-as-printed').on('click', function() {
                var selectedIds = $('.select-checkbox').map(function() {
                    if ($(this).prop('checked')) {
                        return $(this).val();
                    }
                }).get();

                if (! selectedIds.length) {
                    showNotice('error', 'Please select at least one individual.')
                    return;
                }

                bootbox.confirm({
                    size: "medium",
                    message: 'Do you want to mark these ' + selectedIds.length + ' selected individuals\' ID Cards as printed?',
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.mark_as_printed') }}",
                                data: {
                                    id_card_ids: selectedIds
                                }
                            }).then(function(response) {
                                showNotice('success', 'Selected individuals\' ID Cards marked as printed successfully.');

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#mark-all-as-printed').on('click', function() {
                bootbox.confirm({
                    size: "medium",
                    message: 'Do you want to mark all individual(s) ID Cards as printed?',
                    callback: function(result) {
                        if (result === true) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.mark_as_printed') }}",
                            }).then(function(response) {
                                showNotice('success', 'All individual(s) ID Cards marked as printed successfully.');

                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
                            }).catch(function(response) {
                                showNotice('error', 'Something went wrong.');
                            });
                        }
                    }
                });
            });

            $('#clear-print-run').on('click', function() {
                bootbox.prompt({
                    title: 'Do you want to clear the Printrun?',
                    inputType: 'radio',
                    inputOptions: [
                        {
                            text: '<b>Mark all ID cards as printed and Clear Printrun Queue.</b>',
                            value: '1',
                        },
                        {
                            text: '<b>Clear Printrun Queue only.</b>',
                            value: '2',
                        },
                    ],
                    callback: function(result) {
                        if (result) {
                            axios({
                                method: 'post',
                                url: "{{ route('admin.id_cards.clear_print_run') }}",
                                data: {
                                    status: result
                                },
                            }).then(function(response) {
                                showNotice('success', 'Printrun cleared successfully.');
                                $('#id-cards-table').DataTable().ajax.reload();
                                $('#select-all-checkbox').prop('checked', false);
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
