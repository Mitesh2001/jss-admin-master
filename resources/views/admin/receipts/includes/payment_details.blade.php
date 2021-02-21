<div class="col-12 mb-3">
    <div class="card">
        <div class="card-body p-3">
            <h5>Payment Details</h5>

            <div id="payments-container">
                <div class="row font-weight-bold">
                    <div class="col-3">Type</div>

                    <div class="col-2">Date Paid</div>

                    <div class="col-2">Amount</div>

                    <div class="col-2">Transaction Fee</div>

                    <div class="col-2">Notes</div>
                </div>
            </div>

            <button type="button"
                id="add-payment"
                class="btn btn-sm btn-outline-dark float-right mt-3 d-none"
                data-toggle="modal"
                data-target="#paymentModal"
            >
                Add Payment
            </button>
            <div class="clearfix"></div>
        </div>
    </div>
</div>

<template id="payment-template">
    <div class="row">
        <div class="col-3 type"></div>

        <div class="col-2 paid-at"></div>

        <div class="col-2">
            $<span class="amount"></span>
        </div>

        <div class="col-2">
            $<span class="transaction-fee"></span>
        </div>

        <div class="col-2 notes"></div>

        <div class="col-1 text-center">
            <div class="dropdown">
                <button class="btn btn-secondary px-1 py-0 mb-1 dropdown-toggle remove-down-icon" type="button" data-toggle="dropdown">
                    <i class="fa fa-ellipsis-h"></i>
                </button>

                <div class="dropdown-menu">
                    <a class="dropdown-item p-edit pointer"
                        data-toggle="modal"
                        data-target="#paymentModal"
                    >
                        <i class="fa fa-edit"></i> Edit
                    </a>
                    <a class="dropdown-item p-trash pointer">
                        <i class="fa fa-trash"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>

@push('scripts')
<script>
    var payments = @json($receipt->payments);
    var paymentsContainer = document.getElementById('payments-container');
    var paymentTemplate = document.getElementById('payment-template');
    var paymentDeleteUrl = "{{ route('admin.receipts.payments.destroy', ['receipt' => $receipt->id, 'payment' => 'receiptPaymentIdHere']) }}";

    $( document ).ready(function() {
        if (payments && payments.length) {
            for (var i = 0; i < payments.length; i++) {
                addPayment(payments[i]);
            }
        }

        $(paymentsContainer).on("click", "a.p-trash", function(e) {
            var paymentId = this.dataset.paymentId;

            bootbox.confirm({
                size: "medium",
                message: "Are you sure?",
                callback: function(result) {
                    if (result === true) {
                        $.ajax({
                            method: "DELETE",
                            url: paymentDeleteUrl.replace('receiptPaymentIdHere', paymentId)
                        }).done(function( receiptReceivedAmount ) {
                            $("div#payment-id-" + paymentId).remove();
                            $("input#amount-received").val(receiptReceivedAmount);
                            globalReceivedAmount = parseFloat(receiptReceivedAmount);

                            showNotice("success", "Payment deleted successfully.");
                        });
                    }
                }
            });
        });
    });

    function addPayment(p) {
        var pElement = paymentTemplate.content.querySelector('div.row').cloneNode(true);
        pElement.setAttribute("id", "payment-id-" + p.id);

        pElement = setPaymentValuesIn(pElement, p);
        paymentsContainer.appendChild(pElement);
    }

    function setPaymentValuesIn(element, payment) {
        element.getElementsByClassName("type")[0].dataset.typeId = payment.type.id;
        element.getElementsByClassName("type")[0].innerHTML = payment.type.label;

        element.getElementsByClassName("paid-at")[0].dataset.date = payment.paid_at;
        element.getElementsByClassName("paid-at")[0].innerHTML = payment.formatted_paid_at;

        element.getElementsByClassName("amount")[0].innerHTML = parseFloat(payment.amount).toFixed(2);

        element.getElementsByClassName("transaction-fee")[0].innerHTML = parseFloat(payment.transaction_fee).toFixed(2);

        element.getElementsByClassName("notes")[0].dataset.notes = payment.notes;
        element.getElementsByClassName("notes")[0].innerHTML = payment.notes;

        element.getElementsByClassName("p-edit")[0].dataset.paymentId = payment.id;
        element.getElementsByClassName("p-trash")[0].dataset.paymentId = payment.id;

        return element;
    }
</script>
@endpush
