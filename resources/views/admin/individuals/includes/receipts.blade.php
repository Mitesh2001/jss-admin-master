<div class="col-12 col-md-6 mb-5">
    <div class="card">
        <div class="card-body p-3">
            <div class="row">
                <div class="col-6">
                    <h5>Receipts</h5>
                </div>
            </div>

            <div class="row font-weight-bold">
                <div class="col-3">Receipt Date</div>
                <div class="col-3">Total Amount</div>
                <div class="col-3">Amount Received</div>
                <div class="col-3">Action</div>
            </div>

            @forelse ($receipts as $receipt)
                <div class="row">
                    <div class="col-3">{{ $receipt->dated_at }}</div>
                    <div class="col-3">${{ $receipt->getTotalAmount() }}</div>
                    <div class="col-3">${{ $receipt->getReceivedAmount() }}</div>
                    <div class="col-3">
                        <div class="dropdown">
                            <button class="btn btn-secondary px-1 py-0 mb-1 dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
                                <i class="fa fa-ellipsis-h"></i>
                            </button>

                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.receipts.edit', ['receipt' => $receipt->id]) }}">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <form method="post"
                                    action="{{ route('admin.receipts.destroy', ['receipt' => $receipt->id]) }}"
                                    class="inline pointer"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <a class="dropdown-item delete-button">
                                        <i class="fa fa-trash"></i> Delete
                                    </a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="row">
                    <div class="col-12">No records found.</div>
                </div>
            @endforelse

            <form method="post" class="form" action="{{ route('admin.receipts.store') }}">
                @csrf

                <input type="hidden" name="individual_id" value="{{ $individual->id }}">
                <input type="hidden" name="dated_at" value="{{ now()->format('Y-m-d') }}">

                <button type="button"
                    class="btn btn-sm btn-outline-dark float-right delete-button"
                    data-message="Are you sure you want to create a new receipt for this individual?"
                >
                    Add Receipt
                </button>
            </form>
        </div>
    </div>
</div>
