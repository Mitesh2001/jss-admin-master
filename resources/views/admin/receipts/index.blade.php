@extends('admin.layouts.app', ['page' => 'receipt'])

@section('title', 'Receipts')

@push('styles')
    @include('common.datatables.styles')
@endpush

@section('content')
<div class="row">
    <div class="col-6 h3" style="color: #5a5b5d;">
        Receipts
    </div>

    <div class="col-6 text-right header-form-controls">
        <div class="form-group d-inline-block ml-3">
            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addReceiptModal">
                Create
            </button>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive m-2">
        <table class="table table-striped table-hover clickable-table" id="receipts-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Receipt Date</th>
                    <th>Added Timestamp</th>
                    <th>Individual</th>
                    <th>Total Amount</th>
                    <th>Amount Received</th>
                    <th></th>
                </tr>
            </thead>
            {{-- Table data will be fetched by datatables ajax --}}
        </table>
    </div>
</div>

@include('admin.receipts.includes.add_receipt_model')

@endsection

@push('scripts')
@include('common.datatables.scripts')

<script>
    var selected = [];

    $( document ).ready(function() {
        $('#receipts-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ route('admin.receipts.datatables') }}",
            columns: [
                { name: 'id' },
                { name: 'dated_at' },
                { name: 'created_at' },
                { name: 'individual.name', orderable: false },
                { name: 'total_amount', orderable: false, searchable: false },
                { name: 'received_amount', orderable: false, searchable: false },
                { name: 'action', orderable: false, searchable: false }
            ],
            order: [[ 2, "desc" ]]
        });
    });
</script>
@endpush
