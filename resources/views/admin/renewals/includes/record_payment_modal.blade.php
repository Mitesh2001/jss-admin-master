<div class="modal fade" id="record-payment-modal" tabindex="-1" role="dialog" aria-labelledby="record-payment-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="record-payment-modal-title">Record Payment</h5>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="payment-form">
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
                <button type="button" id="record-payment" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $( document ).ready(function() {
        $('#record-payment-modal').on('show.bs.modal', function (e) {
            $('#record-payment-modal #payment-form')[0].reset();

            $('#record-payment-modal #paid-at')[0]._flatpickr.setDate('{{ now() }}');
            $('#record-payment-modal #amount').val($(e.relatedTarget).data('outstandingAmount'));

            checkProcessRenewalEligibility();
        });

        {{-- Append HTML upon confirmation --}}
        $('#record-payment').click(function() {
            if ($('#payment-form')[0].checkValidity() == false) {
                $('<input>').attr({
                    type: 'submit',
                    id: 'payment-form-submit-btn',
                    class: 'd-none'
                }).appendTo('#payment-form');

                $('#payment-form-submit-btn').click();
                $('#payment-form-submit-btn').remove();

                return;
            }

            var allInputs = $('#record-payment-modal div.modal-body')
                .find(':input[name]')
                .clone()
                .removeAttr('id')
                .removeAttr('required')
            ;

            {{-- Selected value of type is not cloned by jquery so do it manually --}}
            $(allInputs).eq(0).val($('#payment-type-id option:selected').val());
            $('#process-renewal-modal .record-payment-inputs').html(allInputs);

            $('#process-renewal-modal .payment-required-notice').addClass('d-none');
            $('#process-renewal-modal #record-payment-button i').removeClass('d-none');
            $('#process-renewal-modal #payment-recorded').removeClass('d-none');

            isPaymentRecorded = true;
            checkProcessRenewalEligibility();

            $('#record-payment-modal').modal('hide');
        });

        $('#record-payment-modal').on('hidden.bs.modal', function (e) {
            {{-- We have another modal open --}}
            $('body').addClass('modal-open');
        });
    });
</script>
@endpush
