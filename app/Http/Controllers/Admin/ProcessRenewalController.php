<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\IndividualRenewal;
use App\Jobs\SendRenewalConfirmationEmail;
use App\ReceiptPayment;
use App\Renewal;
use App\Services\Browsershot;
use App\Traits\ApprovesMember;

class ProcessRenewalController extends Controller
{
    use ApprovesMember;

    /**
     * Process renewal
     *
     * @param int $individualRenewalId
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function processRenewal($individualRenewalId)
    {
        request()->validate(Renewal::validationRules());

        $individualRenewal = IndividualRenewal::query()
            ->with(
                'individual',
                'individual.ssaa',
                'individual.membership',
                'renewal',
                'renewal.renewalRun',
                'renewal.receipt',
                'renewal.receipt.items',
                'renewal.receipt.payments',
                'renewal.receipt.individual',
                'renewal.receipt.individual.suburb',
                'renewal.receipt.individual.state',
                'renewal.receipt.individual.membership:id,membership_number'
            )
            ->find($individualRenewalId)
        ;

        if (request('record_payment')) {
            $paymentDetails = request()->validate(ReceiptPayment::validationRules());

            $this->addPayment($individualRenewal, $paymentDetails);

            $this->approveRenewal($individualRenewal, $individualRenewal->individual, request('is_print_card'));

            $this->generateReceipt($individualRenewal->renewal->receipt);
        }

        $this->sendConfirmationEmail($individualRenewal);

        return redirect()->route('admin.individual_renewal_submissions')->with([
            'type' => 'success',
            'message' => 'Renewal processed successfully.'
        ]);
    }

    /**
     * Add receipt payment
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @param array $paymentDetails
     * @return void
     **/
    public function addPayment($individualRenewal, $paymentDetails)
    {
        $individualRenewal->renewal->receipt->payments()->create($paymentDetails);
    }

    /**
     * Generate receipt
     *
     * @param \App\Receipt $receipt
     * @return void
     **/
    public function generateReceipt($receipt)
    {
        Browsershot::createReceipt($receipt->load('payments'));
    }

    /**
     * Send renewal confirmation email
     *
     * @param \App\IndividualRenewal $individualRenewal
     * @return void
     **/
    public function sendConfirmationEmail($individualRenewal)
    {
        if (request('email_confirmation')) {
            SendRenewalConfirmationEmail::dispatch($individualRenewal->renewal->receipt, $individualRenewal);

            $individualRenewal->renewal->confirmation_email_queued = true;
            $individualRenewal->renewal->save();
        }
    }
}
