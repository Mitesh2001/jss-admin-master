@extends('admin.layouts.app', ['page' => 'renewal_submissions'])

@section('title', 'Renewal Submissions')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Renewal Submissions
        </div>

        <div class="col-6 text-right header-form-controls">
            @include ('admin.renewals.includes.filters')
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="individuals-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Membership #</th>
                        <th>Individual</th>
                        <th>SSAA Expiry</th>
                        <th>Email Address</th>
                        <th></th>
                    </tr>
                </thead>
                {{-- Table data will be fetched by datatables ajax --}}
            </table>
        </div>
    </div>

    @include('admin.renewals.includes.process_renewal_modal')
    @include('admin.renewals.includes.record_payment_modal')
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $( document ).ready(function() {
            $('#individuals-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.individual_renewal_submissions_datatables') }}",
                columns: [
                    { name: 'created_at' },
                    { name: 'membership_no', orderable: false },
                    { name: 'name', orderable: false },
                    { name: 'ssaa_expiry', orderable: false, searchable: false },
                    { name: 'email_address' },
                    { name: 'action', orderable: false, searchable: false }
                ],
                order: [[ 0, "desc" ]]
            });
        });
    </script>
@endpush
