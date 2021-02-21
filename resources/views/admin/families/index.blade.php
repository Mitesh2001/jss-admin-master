@extends('admin.layouts.app', ['page' => 'family'])

@section('title', 'Families')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Families
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" onclick="window.location = '{{ route('admin.families.create') }}'">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="families-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Individuals</th>
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
            $('#families-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.families.datatables') }}",
                columns: [
                    { name: 'id' },
                    { name: 'individuals', orderable: false },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });
        });
    </script>
@endpush
