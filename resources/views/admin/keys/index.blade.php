@extends('admin.layouts.app', ['page' => 'keys'])

@section('title', 'Keys')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    @include('admin.keys.filters')

    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Keys
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block mr-2">
                <button class="btn btn-danger btn-sm" id="filters-button">
                    Filters
                </button>
            </div>

            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" id="add-key-button">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="keys-table">
                <thead>
                    <tr>
                        <th>Key Type</th>
                        <th>Key Number</th>
                        <th>Member Name</th>
                        <th>Date Issued</th>
                        <th>Date Returned</th>
                        <th>Deposit Held</th>
                        <th></th>
                    </tr>
                </thead>

                {{-- Table data will be fetched by datatables ajax --}}
            </table>
        </div>
    </div>

    @include('admin.keys.manage_key_modal')
    @include('admin.keys.mark_as_lost_modal')
    @include('admin.keys.mark_as_returned_modal')
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $(document).ready(function() {
            $('#individual-id').selectpicker({ liveSearch: true });
            $('#filters-container').slideUp();

            $('#keys-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.keys.datatables') }}",
                columns: [
                    { name: 'key_type' },
                    { name: 'key_number' },
                    { name: 'individual.first_name', orderable: false },
                    { name: 'issued_at' },
                    { name: 'returned_at' },
                    { name: 'deposit_amount' },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });

            $('#add-key-button').click(function () {
                $('#manage-key-form input[name=_method]').remove();
                $('#manage-key-form').attr('action', $('#add-new-url').html());
                $('#manage-key-form')[0].reset();
                $('#individual-id').selectpicker('refresh');
                $('#manage-key-modal-label').html('Add New Key');

                $('#manage-key-modal').modal('show');
            });

            $('#keys-table').on('click', 'a.edit-key-link', function () {
                var id = $(this).attr('data-id');
                $('#manage-key-form input[name=_method]').remove();
                $('#manage-key-form').attr('action', $('#add-new-url').html()+'/'+id);

                $('#manage-key-form').prepend($('#manage-key-modal #edit-method-input').html());
                $('#manage-key-modal-label').html('Edit Key');

                $('#manage-key-form #individual-id option[value="'+$(this).attr('data-individual-id')+'"]').prop('selected', true)
                $('#manage-key-form #key-type option[value="'+$(this).attr('data-key-type')+'"]').prop('selected', true)
                $('#manage-key-form #key-number').val($(this).attr('data-key-number'));
                $('#manage-key-form #deposit-amount').val($(this).attr('data-deposit-amount'));

                var issuedAt = document.getElementById('issued-at');
                issuedAt._flatpickr.setDate($(this).attr('data-issued-at'));

                $('#manage-key-form #individual-id').selectpicker('refresh');

                $('#manage-key-modal').modal('show');
            });

            $('#keys-table').on('click', 'a.mark-as-lost', function () {
                var loosedAt = document.getElementById('loosed-at');
                loosedAt._flatpickr.setDate("{{ now()->toDateString() }}");

                $('#mark-as-lost-form').attr('action', $(this).attr('data-url'));

                $('#mark-as-lost-modal').modal('show');
            });

            $('#keys-table').on('click', 'a.mark-as-returned', function () {
                var returnedAt = document.getElementById('returned-at');
                returnedAt._flatpickr.setDate("{{ now()->toDateString() }}");

                $('#mark-as-returned-form').attr('action', $(this).attr('data-url'));

                $('#mark-as-returned-modal').modal('show');
            });

            $('#filters-button').click(function () {
                $('#filters-container').slideToggle();
            });
        });
    </script>
@endpush
