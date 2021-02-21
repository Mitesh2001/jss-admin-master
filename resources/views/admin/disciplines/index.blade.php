@extends('admin.layouts.app', ['page' => 'discipline'])

@section('title', 'Disciplines')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Disciplines
        </div>

        <div class="col-6 text-right header-form-controls">
            <div class="form-group d-inline-block">
                <button class="btn btn-success btn-sm" onclick="window.location = '{{ route('admin.disciplines.create') }}'">
                    Create
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="disciplines-table">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Label</th>
                        <th>Adult Price</th>
                        <th>Family Price</th>
                        <th>Pensioner Price</th>
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
            $('#disciplines-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.disciplines.datatables') }}",
                columns: [
                    { name: 'id' },
                    { name: 'label' },
                    { name: 'adult_price' },
                    { name: 'family_price' },
                    { name: 'pensioner_price' },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "asc" ]]
            });
        });
    </script>
@endpush
