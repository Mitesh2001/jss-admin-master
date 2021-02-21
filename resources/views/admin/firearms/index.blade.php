@extends('admin.layouts.app', ['page' => 'firearms'])

@section('title', 'Firearms')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    @include('admin.firearms.filters')

    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Firearms

            @if (request('individual_name'))
                <span class="text-danger">
                    (Of {{ request('individual_name') }} Only)
                </span>
            @endif
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block mr-2">
                <button class="btn btn-danger btn-sm" id="filters-button">
                    Filters
                </button>
            </div>

            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" id="add-firearm-button">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="firearms-table">
                <thead>
                    <tr>
                        <th>Firearm</th>
                        <th>Firearm Type</th>
                        <th>Serial #</th>
                        <th>Discipline</th>
                        <th>Licence(s)</th>
                        <th>Support Granted</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>

                {{-- Table data will be fetched by datatables ajax --}}
            </table>
        </div>
    </div>

    @include('admin.firearms.manage_firearm_modal')
    @include('admin.firearms.remove_support_modal')
    @include('admin.firearms.mark_as_disposed_modal')
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $(document).ready(function() {
            $('#individual-ids').selectpicker({ liveSearch: true });
            $('#firearm-type').selectpicker({ liveSearch: true });

            $('#filters-container').slideUp();

            $('#firearms-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.firearms.datatables', [ 'individual_id' => request('individual_id') ]) }}",
                columns: [
                    { name: 'make' },
                    { name: 'type.label', orderable: false },
                    { name: 'serial' },
                    { name: 'discipline.label', orderable: false },
                    { name: 'individuals', orderable: false },
                    { name: 'support_granted_at' },
                    { name: 'status', orderable: false, searchable: false },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 2, "asc" ]]
            });

            $('#add-firearm-button').click(function () {
                $('#manage-firearm-form input[name=_method]').remove();
                $('#manage-firearm-form').attr('action', $('#add-new-url').html());
                $('#manage-firearm-form')[0].reset();
                $('#individual-ids').selectpicker('refresh');
                $('#firearm-type').selectpicker('refresh');
                $('#manage-firearm-modal-label').html('Add New Firearm');

                $('#manage-firearm-modal').modal('show');
            });

            $('#firearms-table').on('click', 'a.edit-firearm-link', function () {
                var id = $(this).attr('data-id');
                $('#manage-firearm-form input[name=_method]').remove();
                $('#manage-firearm-form').attr('action', $('#add-new-url').html()+'/'+id);

                $('#manage-firearm-form').prepend($('#manage-firearm-modal #edit-method-input').html());
                $('#manage-firearm-modal-label').html('Edit Firearm');
                var individualIds = JSON.parse($(this).attr('data-individual-ids'));

                for (const key in individualIds) {
                    $('#manage-firearm-form #individual-ids option[value="'+individualIds[key]+'"]').prop('selected', true)
                }

                $('#manage-firearm-form #make').val($(this).attr('data-make'));
                $('#manage-firearm-form #model').val($(this).attr('data-model'));
                $('#manage-firearm-form #calibre').val($(this).attr('data-calibre'));
                $('#manage-firearm-form #serial').val($(this).attr('data-serial'));
                $('#manage-firearm-form #discipline-id option[value="'+$(this).attr('data-discipline-id')+'"]').prop('selected', true)
                $('#manage-firearm-form #firearm-type option[value="'+$(this).attr('data-firearm-type')+'"]').prop('selected', true)

                var supportGrantedAt = document.getElementById('support-granted-at');
                supportGrantedAt._flatpickr.setDate($(this).attr('data-support-granted-at'));

                $('#individual-ids').selectpicker('refresh');
                $('#firearm-type').selectpicker('refresh');

                $('#manage-firearm-modal').modal('show');
            });

            $('#firearms-table').on('click', 'a.remove-Support', function () {
                $('#firearm-id').val($(this).attr('data-id'));
                $('#support-reason').text('');
                var supportGrantedAt = document.getElementById('support-removed-at');
                supportGrantedAt._flatpickr.setDate("{{ now()->toDateString() }}");

                $('#remove-support-modal').modal('show');
            });

            $('#firearms-table').on('click', 'a.mark-as-disposed', function () {
                $('#mark-as-disposed-firearm-id').val($(this).attr('data-id'));
                $('#disposed-reason').text('');
                var supportGrantedAt = document.getElementById('mark-as-disposed-at');
                supportGrantedAt._flatpickr.setDate("{{ now()->toDateString() }}");

                $('#mark-as-disposed-modal').modal('show');
            });

            $('#filters-button').click(function () {
                $('#filters-container').slideToggle();
            });
        });
    </script>
@endpush
