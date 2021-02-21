<?php

namespace App\Jobs;

use App\Receipt;
use App\Renewal;
use App\Services\Browsershot;
use App\Traits\ApprovesMember;
use App\Traits\PreparesReceiptItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRenewal implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        PreparesReceiptItems,
        ApprovesMember
    ;

    protected $individual;

    protected $renewalRun;

    protected $transactionFee;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($individual, $renewalRun, $transactionFee = 0)
    {
        $this->individual = $individual->load('ssaa');
        $this->renewalRun = $renewalRun;
        $this->transactionFee = $transactionFee;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $renewal = Renewal::where('renewal_run_id', $this->renewalRun->id)
            ->with('iRenewal', 'iRenewal.renewal', 'iRenewal.familyMembers', 'iRenewal.disciplines', 'iRenewal.parentRenewal', 'iRenewal.parentRenewal.renewal')
            ->where('individual_id', $this->individual->id)
            ->first()
        ;

        // If the renewal is submitted by 2nd or 3rd member of the family
        if (
            $renewal->iRenewal->payment_type == 0
        ) {
            // If the 1st family member renewal approved then approve other family member renewals
            if ($renewal->iRenewal->parentRenewal->renewal->approved) {
                $this->approveRenewal(
                    $renewal->iRenewal,
                    $this->individual,
                    $renewal->iRenewal->parentRenewal->individual->has('idCard')
                );

                $renewal->confirmation_email_queued = true;
                $renewal->confirmation_emailed = true;
                $renewal->save();
            }

            return;
        }

        $receipt = Receipt::create([
            'dated_at' => now()->format('Y-m-d')
        ]);

        $receipt->individuals()->attach([$this->individual->id]);
        if ($renewal->iRenewal->type_id == 2) {
            $renewal->iRenewal->familyMembers->each(function ($familyMember) use ($receipt) {
                if ($familyMember->id != $this->individual->id) {
                    $receipt->individuals()->attach([
                        $familyMember->id => [
                            'is_payer' => false,
                        ]
                    ]);
                }
            });
        }

        $renewal->receipt_id = $receipt->id;
        $renewal->save();

        $receipt->items()->createMany($this->getReceiptItems($renewal));

        $iRenewal = $renewal->iRenewal;
        if ($iRenewal->payment_type == 2) {
            $receipt->payments()->create([
                'type_id' => 1, // Online Payment
                'amount' => $iRenewal->amount - $iRenewal->discount,
                'stripe_transfer_no' => $iRenewal->transaction_no,
                'transaction_fee' => $this->transactionFee,
                'paid_at' => now(),
            ]);

            $this->approveRenewal($renewal->iRenewal, $this->individual, $isPrintCard = true);

            Browsershot::createReceipt(
                $receipt->load(
                    'items',
                    'payments',
                    'individual',
                    'individual.suburb',
                    'individual.state',
                    'individual.membership:id,membership_number'
                )
            );

            $renewal->confirmation_email_queued = true;
            $renewal->save();

            SendRenewalConfirmationEmail::dispatch($receipt, $renewal->iRenewal);
        }
    }
}
