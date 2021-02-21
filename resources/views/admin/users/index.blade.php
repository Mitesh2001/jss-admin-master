@extends('admin.layouts.app', ['page' => 'captain'])

@section('title', 'Captains')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Captains
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" onclick="window.location = '{{ route('admin.users.create') }}'">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="users-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Username</th>
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
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.users.datatables') }}",
                columns: [
                    { name: 'id' },
                    { name: 'individualName', orderable: false, searchable: false },
                    { name: 'individual.email_address', orderable: false },
                    { name: 'username' },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });
        });
    </script>
@endpush
