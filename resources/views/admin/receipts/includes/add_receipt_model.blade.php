<div class="modal fade" id="addReceiptModal" role="dialog" aria-labelledby="addReceiptModalTitle" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addReceiptModalTitle">
                    <span class="text-muted">
                        Receipts
                    </span>
                    | Add new
                </h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form method="post" class="form" id="add-receipt-model" action="{{ route('admin.receipts.store') }}">
                @csrf

                <div class="modal-body">
                    @include('admin.receipts.add')
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" id="save-payment" class="btn btn-primary">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
