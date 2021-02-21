@extends('admin.layouts.app', ['page' => 'payment_report'])

@section('title', 'Payments Report')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
    @include ('admin.reports.payments.filters')

    <div class="row">
        <div class="col-6 h3" style="color: #5a5b5d;">
            Payments
        </div>

        <div class="col-6 text-right header-form-controls">
            <a href="{{ route('admin.reports.payments.print',
                [
                    'type' => session('payment_discipline_type'),
                    'startDate' => $startDate,
                    'endDate' => $endDate
                ]) }}"
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
            <table class="table table-striped table-hover clickable-table" id="payments-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Payment Date</th>
                        <th>Paid By</th>
                        <th>Item</th>
                        <th>Description</th>
                        <th>Receipt #</th>
                        <th>Amount</th>
                        <th>Fees</th>
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
            $('#payments-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: "{{ route('admin.reports.payments.datatables') }}",
                columns: [
                    { name: 'created_at' },
                    { name: 'receiptPaymentDate', orderable: false, searchable: false },
                    { name: 'individualName', orderable: false, searchable: false },
                    { name: 'itemLabel', orderable: false, searchable: false },
                    { name: 'description' },
                    { name: 'receiptId', orderable: false, searchable: false },
                    { name: 'amount' },
                    { name: 'fee', orderable: false, searchable: false }
                ],
                order: [[ 0, "desc" ]]
            });
        });
    </script>
@endpush
