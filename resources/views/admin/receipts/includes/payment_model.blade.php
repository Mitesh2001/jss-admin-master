<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalTitle">Manage Payment</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <span id="payment-add-url" class="d-none">
                    {{ route('admin.receipts.payments.store', ['receipt' => $receipt->id]) }}
                </span>

                <span id="payment-edit-url" class="d-none">{{ route ('admin.receipts.payments.update', [
                    'receipt' => $receipt->id,
                    'payment' => 'EditReceiptPaymentIdHere',
                ]) }}</span>

                <span id="payment-edit-method" class="d-none">@method('PUT')</span>

                <form method="post" class="form" id="payment-form">
                    @csrf

                    <div class="form-group">
                        <label for="payment-type-id">Type:</label>
                        <select id="payment-type-id" name="type_id" class="form-control" required>
                            <option value="">Please select</option>

                            @foreach ($paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}">
                                    {{ $paymentType->label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <label for="paid-at">Date Paid:</label>

                    <div class="input-group mb-3">
                        <input type="date"
                            id="paid-at"
                            class="form-control"
                            name="paid_at"
                            data-input
                        >

                        <div class="input-group-prepend">
                            <a class="input-group-text" data-toggle>
                                <i class="fa fa-calendar"></i>
                            </a>
                        </div>
                    </div>

                    <label for="amount">Amount:</label>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>

                        <input type="number"
                            pattern="\d{1,6}(\.\d{0,2})?"
                            class="form-control"
                            name="amount"
                            id="amount"
                            step="0.01"
                            min="0"
                            required
                        >
                    </div>

                    <label for="transaction-fee">Transaction Fee:</label>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>

                        <input type="number"
                            pattern="\d{1,6}(\.\d{0,2})?"
                            class="form-control"
                            name="transaction_fee"
                            id="transaction-fee"
                            step="0.01"
                            min="0"
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="notes">Notes:</label>
                        <input type="text"
                            class="form-control"
                            id="notes"
                            name="notes"
                        >
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="save-payment" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    var paymentForm = document.getElementById('payment-form');
    var paidAt = document.getElementById('paid-at');
    var paymentAddUrl = document.getElementById('payment-add-url').innerHTML;
    var paymentEditUrl = document.getElementById('payment-edit-url').innerHTML;
    var putMethodInputContainer = document.getElementById('payment-edit-method');
    var putMethodInput = putMethodInputContainer.getElementsByTagName('input')[0];
    var savePayment = document.getElementById('save-payment');

    $( document ).ready(function() {
        $('#paymentModal').on('show.bs.modal', function (e) {
            paymentForm.reset();
            $('#payment-type-id').trigger("change");

            if (e.relatedTarget.tagName == 'BUTTON') {
                paymentForm.action = paymentAddUrl;
                paidAt._flatpickr.setDate('{{ now() }}');
                $('#payment-form input[name="_method"]').remove();
                return;
            }

            updatePaymentFormForEdit(e);
        });

        savePayment.addEventListener('click', function(e) {
            if ($('#payment-form')[0].checkValidity() == false) {
                $('<input>').attr({
                    type: 'submit',
                    id: 'payment-form-submit-btn',
                    class: 'd-none'
                }).appendTo('#payment-form');

                $('#payment-form-submit-btn').click();
                $('#payment-form-submit-btn').remove();

                return false;
            }

            var isAddPayment = ! $("#payment-form").find('input[name="_method"]').length;

            if (isAddPayment) {
                var amount = parseFloat(document.getElementById('amount').value);
                var calculatedReceivedAmount = amount + globalReceivedAmount;

                if (calculatedReceivedAmount < globalTotalAmount) {
                    if (! confirm('The amount entered does not match the amount of the receipt, are you sure you want to save a partial payment?')) {
                        return;
                    }
                }
            }

            savePayment.disabled = true;

            $.ajax({
                method: "POST",
                url: paymentForm.action,
                data: $("#payment-form").serialize()
            }).done(function( response ) {
                if (isAddPayment) {
                    addPayment(response.payment);
                } else {
                    editPayment(response.payment);
                }

                savePayment.disabled = false;
                $('#paymentModal').modal('hide');

                $("input#amount-received").val(response.receiptReceivedAmount);
                globalReceivedAmount = parseFloat(response.receiptReceivedAmount);

                showNotice("success", "Payment saved successfully.");
            });
        });
    });

    function updatePaymentFormForEdit(e) {
        var paymentId = e.relatedTarget.dataset.paymentId;
        paymentForm.action = paymentEditUrl.replace('EditReceiptPaymentIdHere', paymentId);
        paymentForm.appendChild(putMethodInput);

        var paymentRow = document.getElementById("payment-id-" + paymentId);

        var typeId = paymentRow.getElementsByClassName("type")[0].dataset.typeId;
        $("#payment-form select[name='type_id']").val(typeId);

        var paidAtDate = paymentRow.getElementsByClassName("paid-at")[0].dataset.date;
        paidAt._flatpickr.setDate(paidAtDate);

        var amount = paymentRow.getElementsByClassName("amount")[0].innerHTML;
        $("#payment-form input[name='amount']").val(amount);

        var transactionFee = paymentRow.getElementsByClassName("transaction-fee")[0].innerHTML;
        $("#payment-form input[name='transaction_fee']").val(transactionFee);

        var notes = paymentRow.getElementsByClassName("notes")[0].dataset.notes;
        $("#payment-form input[name='notes']").val(notes);

        $('#payment-type-id').trigger("change");
    }

    function editPayment(payment) {
        var paymentRow = document.getElementById("payment-id-" + payment.id);

        setPaymentValuesIn(paymentRow, payment);
    }
</script>
@endpush
