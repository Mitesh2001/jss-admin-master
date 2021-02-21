<div class="tab-pane" id="confirmation">
    <input type="hidden" id="renewal-total-discount" name="discount">
    <input type="hidden" id="renewal-total-amount" name="amount">

    <div class="row">
        <div class="col-xs-10 col-xs-offset-1">
            <blockquote>
                <h5 class="col-xs-7">Renewal Details</h5>

                <div class="col-xs-5 left-border text-center">
                    <div class="complete-section">
                        Complete
                        <i class="fa fa-check-circle"></i>
                    </div>
                </div>
                <div class="clearfix"></div>
            </blockquote>
        </div>

        @if(! $isFamilyRenewalAlreadyPaid)
            <div class="col-xs-10 col-xs-offset-1">
                <table class="table table-borderless table-striped-even table-fonts"
                    id="renewal-total-table"
                >
                    <thead class="table-danger">
                        <tr>
                            <th>
                                Item
                            </th>

                            <th>
                                Price
                            </th>
                        </tr>
                    </thead>

                    <tbody></tbody>

                    <tfoot class="table-danger">
                        <tr id="discount-container">
                            <th>
                                <i class="fa fa-tag"></i>
                                Discount -

                                (<span class="pointer link"
                                    data-toggle="modal"
                                    data-target="#discount-details-modal"
                                    title="Click for discount details"
                                >More info</span>)
                            </th>

                            <th>
                                <i class="fa fa-minus" aria-hidden="true"></i>

                                $<span id="total-discount"></span>
                            </th>
                        </tr>

                        <tr>
                            <th>
                                <i class="fa fa-calculator" aria-hidden="true"></i>
                                Total
                            </th>

                            <th>
                                <i class="fa fa-usd"></i>
                                <span id="total-amount"></span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-xs-10 col-xs-offset-1" id="price-container">
                <div class="complete-wrapper-box text-center">
                    <h3>Renewal Price</h3>

                    <div class="drag-drop box-white mb-5">
                        $<span id="renewal-price"></span>
                    </div>

                    <div class="clearfix"></div>

                    <input type="hidden" id="payment-type" name="payment_type" value="2">

                    <div class="checkbox">
                        <label>
                            <input type="checkbox" id="pay-offline" name="payment_type_checkbox">
                            <span style="color: #fff" class="offline-payment">Pay offline</span>
                        </label>
                    </div>
                </div>

                <h3 id="pay-offline-note" class="sub-heading hidden">
                    You have chosen to pay offline. Offline payments can be made by making a bank transfer to JSS Inc, BSB: 036032, ACC: 579111
                    <strong>
                        (please include your SSAA number as the transaction reference).
                    </strong>
                    <br><br>
                    Please note payment is required by 31st December. If payment is not received by this date your membership will not be considered financial.
                </h3>


                <h3 class="sub-heading family-membership-message">
                    <br><br>
                    After completing your  family membership renewal, your family members must also complete their renewals and accept the terms and conditions in order for their membership to be valid.
                </h3>
            </div>
        @else
            <div class="col-xs-10 col-xs-offset-1">
                <h3 class="sub-heading">
                    Your membership has already been paid for by another family member. You must accept the terms and conditions below to complete your renewal. If you are under 18, the terms and conditions must be accepted by your parent or guardian.
                </h3>
            </div>
        @endif

		<div class="clearfix"></div>

		<h3 id="renewal-confirmation" class="sub-heading">
            <p class="d-inline">
                I,
            </p>

            <div class="form-group label-floating inline-input">
                <label class="control-label left-label">Full Name</label>

                <input type="text"
                    name="renewal_applier_full_name"
                    class="form-control"
                    value="{{ old('renewal_applier_full_name') }}"
                    required
                >
            </div>

            <p class="d-inline">
                accept the

                <a class="pointer" data-toggle="modal" data-target="#terms-and-condition-modal">
                    terms and conditions.
                </a>
            </p>
        </h3>
    </div>

    @include('front.renewals.individual.includes.terms_and_conditions_modal')

    @include('front.renewals.individual.includes.discount_details_modal')
</div>


@push('scripts')
    <script>
        var totalSubmitted = 0;
        $('#pay-offline').change(function() {
            if ($('#pay-offline').is(':checked')) {
                $('#payment-type').val(1);
                $('#pay-offline-note').removeClass('hidden');
                $('.btn-finish').val('Submit');

                return;
            }

            $('#payment-type').val(2);
            $('#pay-offline-note').addClass('hidden');
            $('.btn-finish').val('Make payment');
        });

        $(document).on('submit', 'form#individual-renewal-form', function(e) {
            if (totalSubmitted == 0) {
                e.preventDefault();
            }

            $('.btn-finish').attr('disabled', true);

            // If form is invalid
            if (! $(this).valid()) {
                $('.btn-finish').attr('disabled', false);
                return;
            }

            // If Stripe token is already set, follow submit
            if ($('input[name=stripe_token]').val().length) {
                $('.btn-finish').attr('disabled', false);
                return;
            }

            {{-- Remove extra data --}}
            var typeId = $("input[name='type_id']:checked").val();

            $('#family-discipline-template').remove();
            $('#family-member-template').remove();
            $('#discipline-template').remove();

            if (typeId == 2) {
                $('#discipline-table').remove();
            }

            if ($.inArray(typeId, ['1', '3']) != -1) {
                $('#family-discipline-table').remove();
                $('#family-member-table').remove();
            }

            // If family member have already paid the renewal
            // Or offline payment then payment not required.
            if (
                $('input[name=is_family_renewal_already_paid]').val() ||
                $('#pay-offline').is(':checked')
            ) {
                totalSubmitted++;
                $('#individual-renewal-form')[0].submit();
                return;
            }

            stripeHandler.open({
                description: 'Membership Renewal for {{ $individual->getName() }}',
                amount: updateRenewalPrice() * 100,
                currency: 'AUD',
                email: '{{ $individual->email_address }}'
            });
        });
    </script>
@endpush
