@extends('admin.layouts.app', ['page' => 'receipt'])

@section('title', 'Edit a Receipt')

@section('content')
    <form method="post"
        class="form"
        action="{{ route('admin.receipts.update', ['receipt' => $receipt->id]) }}"
    >
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-9 h3">
                <span class="text-muted">
                    Receipts
                </span>
                | #{{ $receipt->id }}
            </div>

            <div class="col-3 text-right header-form-controls">
                <div class="form-group d-inline-block">
                    <a class="btn btn-primary btn-sm"
                        href="{{ route('admin.receipts.print', ['receipt' => $receipt->id]) }}"
                        target="_blank"
                    >
                        Print
                    </a>

                    <button class="btn btn-success btn-sm" >
                        Save
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            @include('admin.receipts.includes.individual_details')

            @include('admin.receipts.includes.code_details')

            @include('admin.receipts.includes.payment_details')
        </div>
    </form>

    @include('admin.receipts.includes.code_model')
    @include('admin.receipts.includes.payment_model')
@endsection

@push('scripts')
    <script>
        var globalTotalAmount = parseFloat({{ $receipt->getTotalAmount() }});
        var globalReceivedAmount = parseFloat({{ $receipt->getReceivedAmount() }});

        $("#dated-at").flatpickr({
            altInput: true,
            altFormat: "d-m-Y",
            dateFormat: "Y-m-d",
        });
    </script>
@endpush
