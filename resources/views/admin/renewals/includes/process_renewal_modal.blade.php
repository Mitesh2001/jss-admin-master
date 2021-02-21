<div class="modal fade" tabindex="-1" id="process-renewal-modal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post"
                id="process-renewal-form"
                action="{{ route('admin.individual_renewals.process_renewal') }}"
                data-original-action="{{ route('admin.individual_renewals.process_renewal') }}"
            >
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Process Renewal</h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="record-payment-inputs d-none"></div>

                    <div class="row">
                        <div class="col-12 record-payment-container">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="record_payment" value="0">

                                <input type="checkbox" name="record_payment" class="custom-control-input" id="record-receipt-payment" value="1" checked="">

                                <label class="custom-control-label" for="record-receipt-payment">
                                    <p>
                                        Record payment
                                        <i id="payment-recorded" class="fa fa-check text-success d-none"></i>

                                        <button type="button"
                                            id="record-payment-button"
                                            class="btn btn-sm btn-outline-dark float-right"
                                            data-toggle="modal"
                                            data-target="#record-payment-modal"
                                        >
                                            Record Payment
                                            <i class="fa fa-check text-success d-none"></i>
                                        </button>
                                    </p>

                                    <p>
                                        This step will add payment to the associated invoice of the renewal.
                                    </p>

                                    <div class="text-danger mb-3 payment-required-notice">
                                        This invoice requires a payment to be recorded.
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="col-12 approval-container">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="renewal-approval" value="1"
                                    checked disabled>

                                <label class="custom-control-label" for="renewal-approval">
                                    <p>
                                        Renewal Approval
                                        <i class="fa fa-check text-success"></i>
                                    </p>

                                    <p>
                                        This step will officially approve the renewal for the entity and update their details in the register.
                                    </p>
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="is_print_card" value="0">
                                <input type="checkbox" name="is_print_card" class="custom-control-input" id="print-card" value="1" checked>

                                <label class="custom-control-label" for="print-card">
                                    <p>Mark for Card Print</p>

                                    <p>
                                        This step will add an entry of this individual in the list of ID Cards to be printed.
                                    </p>
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="custom-control custom-checkbox">
                                <input type="hidden" name="email_confirmation" value="0">
                                <input type="checkbox" name="email_confirmation" class="custom-control-input" id="email-confirmation" value="1" checked>

                                <label class="custom-control-label" for="email-confirmation">
                                    <p>Email Confirmation</p>

                                    <p>
                                        This step will email the entity with confirmation of their renewal along with the receipt attached.
                                    </p>
                                </label>
                            </div>
                        </div>

                        <div id="confirmation-email-text" class="col-10 offset-2">
                            <p>
                                Email &amp; Receipt will be sent to<br>
                                <span class="text-danger" id="receipt-receiver-email"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>

                    <button type="submit" class="btn btn-primary submit-operation" disabled>
                        Process Renewal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        var isPaymentRecorded = false;
        var isAlreadyApproved = 0;

        $('#process-renewal-modal').on('show.bs.modal', function (e) {
            isPaymentRecorded = false;
            var entityEmail = e.relatedTarget.dataset.email;
            var url = e.relatedTarget.dataset.url;
            var outstandingAmount = e.relatedTarget.dataset.outstandingAmount;
            isAlreadyApproved = parseInt(e.relatedTarget.dataset.approved);

            $('#process-renewal-modal #process-renewal-form').attr('action', url);
            $('#process-renewal-modal #receipt-receiver-email').html(entityEmail);
            $('#process-renewal-modal #record-payment-button').data('outstanding-amount', outstandingAmount);
            $('#process-renewal-modal #payment-recorded').addClass('d-none');
            $('#process-renewal-modal .payment-required-notice').removeClass('d-none');
            $('#process-renewal-modal .record-payment-inputs').html('');
            $('#process-renewal-modal #record-payment-button i').addClass('d-none');
            $('#process-renewal-modal #email-confirmation').prop('checked', true);
            $('#process-renewal-modal #record-receipt-payment').prop('checked', true).prop('disabled', false);
            $('#process-renewal-modal #record-payment-button').removeClass('d-none');


            checkProcessRenewalEligibility();

            if (isAlreadyApproved) {
                $('#process-renewal-modal #record-payment-button').addClass('d-none');
                $('#process-renewal-modal #record-receipt-payment').prop('disabled', true);
                $('#process-renewal-modal #payment-recorded').removeClass('d-none');
                $('#process-renewal-modal .payment-required-notice').addClass('d-none');
                $('#process-renewal-modal .submit-operation').prop('disabled', false);
            }
        });

        $('#record-receipt-payment').change(function() {
            checkProcessRenewalEligibility();

            if ($(this).is(":checked")) {
                $('#email-confirmation').prop('disabled', false);
                $('#email-confirmation').prop('checked', true);
                return;
            }

            $('#email-confirmation').prop('checked', false);
            $('#email-confirmation').prop('disabled', true);
        });


        $('#email-confirmation').change(function() {
            if (isAlreadyApproved) {
                if (! $('#email-confirmation:checked').val()) {
                    $('#process-renewal-modal .submit-operation').prop('disabled', true);
                    return;
                }

                $('#process-renewal-modal .submit-operation').prop('disabled', false);
            }
        });

        function checkProcessRenewalEligibility() {
            if (! isPaymentRecorded || ! $('#record-receipt-payment:checked').val()) {
                $('#process-renewal-modal .submit-operation').prop('disabled', true);

                return;
            }

            $('#process-renewal-modal .submit-operation').prop('disabled', false);
        }
    </script>
@endpush
