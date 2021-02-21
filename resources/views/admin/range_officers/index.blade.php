@extends('admin.layouts.app', ['page' => 'range_officers'])

@section('title', 'Range Officers')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    @include('admin.range_officers.filters')

    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Range Officers
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block mr-2">
                <button class="btn btn-danger btn-sm" id="filters-button">
                    Filters
                </button>
            </div>

            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" id="add-range-officer-button">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="range-officers-table">
                <thead>
                    <tr>
                        <th>Accreditation Date</th>
                        <th>Member Name</th>
                        <th>Discipline</th>
                        <th></th>
                    </tr>
                </thead>

                {{-- Table data will be fetched by datatables ajax --}}
            </table>
        </div>
    </div>

    @include('admin.range_officers.manage_range_officer_modal')
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $(document).ready(function() {
            $('#individual-id').selectpicker({ liveSearch: true });
            $('#filters-container').slideUp();

            $('#range-officers-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.range_officers.datatables') }}",
                columns: [
                    { name: 'added_date' },
                    { name: 'individual.first_name', orderable: false },
                    { name: 'discipline.label', orderable: false },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });

            $('#add-range-officer-button').click(function () {
                $('#manage-range-officer-form input[name=_method]').remove();
                $('#manage-range-officer-form').attr('action', $('#add-new-url').html());
                $('#manage-range-officer-form')[0].reset();
                $('#individual-id').selectpicker('refresh');
                $('#manage-range-officer-modal-label').html('Add New Range Officer');

                $('#manage-range-officer-modal').modal('show');
            });

            $('#range-officers-table').on('click', 'a.edit-range-officer-link', function () {
                var id = $(this).attr('data-id');
                $('#manage-range-officer-form input[name=_method]').remove();
                $('#manage-range-officer-form').attr('action', $('#add-new-url').html()+'/'+id);

                $('#manage-range-officer-form').prepend($('#manage-range-officer-modal #edit-method-input').html());
                $('#manage-range-officer-modal-label').html('Edit Range Officer');

                $('#manage-range-officer-form #individual-id option[value="'+$(this).attr('data-individual-id')+'"]').prop('selected', true)
                $('#manage-range-officer-form #discipline-id option[value="'+$(this).attr('data-discipline-id')+'"]').prop('selected', true)

                var addedDate = document.getElementById('added-date');
                addedDate._flatpickr.setDate($(this).attr('data-added-date'));

                $('#manage-range-officer-form #individual-id').selectpicker('refresh');

                $('#manage-range-officer-modal').modal('show');
            });

            $('#filters-button').click(function () {
                $('#filters-container').slideToggle();
            });
        });
    </script>
@endpush
