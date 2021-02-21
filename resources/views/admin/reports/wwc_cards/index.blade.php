@extends('admin.layouts.app', ['page' => 'wwc_cards_report'])

@section('title', 'WWC Cards Report')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            WWC Cards Report
        </div>

        <div class="col-6 text-right header-form-controls">
            <a href="{{ route('admin.reports.wwc_cards.print') }}"
                class="btn btn-dark"
                target="_blank"
            >
                <i class="fa fa-file-text"></i>
                &nbsp; PDF Report
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body table-responsive m-2">
            <table class="table table-striped table-hover clickable-table" id="wwc-cards-report-table">
                <thead>
                    <tr>
                        <th>Member number</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>WWC Card #</th>
                        <th>WWC Expiry</th>
                        <th>Status</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    @include('common.datatables.scripts')

    <script>
        $( document ).ready(function() {
            $('#wwc-cards-report-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.reports.wwc_cards.datatables') }}",
                columns: [
                    { name: 'membership.membership_number', orderable: false, searchable: false },
                    { name: 'name' },
                    { name: 'email_address' },
                    { name: 'wwc_card_number' },
                    { name: 'wwc_expiry_date' },
                    { name: 'status', orderable: false, searchable: false  },
                ],
                order: [[ 4, "asc" ]]
            });
        });
    </script>
@endpush
