@extends('front.renewals.layout')

@section('content')
<div class="row">
    <div class="col-sm-12">
        <h3 class="info-text">
            {{ config('app.name') }}<br>
            Renewal of Membership - Submission Received
        </h3>

        <h3 class="info-text">
            <strong>{{ $individual->getName() }}</strong><br>
            Membership Number: {{ $individual->getMembershipNumber() }}
        </h3>
    </div>

    <div class="col-xs-10 col-xs-offset-1">
        <blockquote>
            <h5 class="col-xs-8">Membership Renewal Submission</h5>

            <div class="col-xs-4">
                <div class="complete-section">
                    Complete
                    <i class="fa fa-check-circle text-green"></i>
                </div>
            </div>
            <div class="clearfix"></div>
        </blockquote>

        @if(! $isFamilyRenewalAlreadyPaid)
            <blockquote>
                <h5 class="col-xs-8">Renewal Payment</h5>

                <div class="col-xs-4">
                    <div class="complete-section">
                        @if($transactionId)
                            Complete
                            <i class="fa fa-check-circle text-green"></i>
                        @else
                            Not Complete
                            <i class="fa fa-times-circle text-red"></i>
                        @endif
                    </div>
                </div>

                <div class="clearfix"></div>
            </blockquote>
        @endif
    </div>

    <div class="clearfix"></div>

    <div class="col-sm-10 col-sm-offset-1 mb-5">
        @if($transactionId)
            <h3 class="sub-heading">
                <strong>TRANSACTION ID:</strong>  {{ $transactionId }}
            </h3>
        @endif


        <h3 class="sub-heading">
            @if($transactionId)
                Thank you for completing your membership renewal submission.
            @else
                Thank you for submitting your membership renewal.
            @endif

            @if($isFamily && ! $isFamilyRenewalAlreadyPaid)
                <div class="alert alert-warning" role="alert">
                    Your family members must now also complete their renewals and accept the terms and conditions in order for their membership to be valid.
                </div>
            @endif
        </h3>
        @if(! $isFamilyRenewalAlreadyPaid)
            <h3 class="sub-heading">
                @if($transactionId)
                    Your renewal will now be processed and then you will receive a confirmation email that will include your receipt for payment.
                @else
                    You have chosen to pay offline. Offline payments can be made by making a bank transfer to JSS Inc, BSB: 036032, ACC: 579111
                    <strong>
                        (please include your SSAA number as the transaction reference).
                    </strong>

                    <br>
                    <br>

                    Please note payment is required by 31st December. If payment is not received by this date your membership will not be considered financial.
                @endif
            </h3>
        @endif
    </div>
</div>
@endsection
