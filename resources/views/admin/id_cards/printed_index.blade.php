@extends('admin.layouts.app', ['page' => 'printed_id_cards'])

@section('title', 'Printed ID Cards')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-12 h3" style="color: #5a5b5d;">
            Printed ID Cards
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="id-cards-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Branch Code</th>
                        <th>SSAA Number</th>
                        <th>Status</th>
                        <th>Membership</th>
                        <th>Print Date</th>
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
                ajax: "{{ route('admin.id_cards.printed_datatables') }}",
                fnDrawCallback: function (oSettings) {
                    $('.dataTables_filter').each(function () {
                        $(this).prepend(`
                            <i class="fa fa-info-circle"
                                title="Type to search based on Name, Branch Code, SSAA Number, Membership or Print Date column values"
                            ></i>
                        `);
                    });
                },
                columns: [
                    { name: 'name', orderable: false },
                    { name: 'branchCode', orderable: false },
                    { name: 'ssaa_number', orderable: false },
                    { name: 'membership_status', orderable: false, searchable: false },
                    { name: 'membership_type', orderable: false },
                    { name: 'printed_at' },
                ],
                order: [[ 5, "asc" ]]
            });
        });
    </script>
@endpush
