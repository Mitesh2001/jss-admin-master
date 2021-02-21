<?php

namespace App\Jobs;

use App\Services\Browsershot;
use App\Services\Sparkpost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendRenewalConfirmationEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $sparkpost;
    protected $receipt;
    protected $individualRenewal;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($receipt, $individualRenewal)
    {
        $this->sparkpost = new Sparkpost;
        $this->receipt = $receipt;
        $this->individualRenewal = $individualRenewal;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $templateId = 'jss-renewal-confirmation';

        $templateDetails = $this->sparkpost->fetchTemplateDetails($templateId);

        $content = $templateDetails['results']['content'];

        $this->sparkpost->description($templateDetails['results']['description'])
            ->options([
                'transactional' => true,
                'inline_css' => true,
            ])->content([
                'from' => $content['from'],
                'subject' => $content['subject'],
                'html' => $content['html'],
                'text' => $content['text'] ?? null,
                'attachments' => [
                    [
                        'name' => 'JSS-receipt-' . $this->receipt->id . '.pdf',
                        'type' => 'application/pdf',
                        'data' => $this->getInvoiceContents(),
                    ],
                ],
            ])->send(collect([$this->receipt->individual[0]]))
        ;

        $this->individualRenewal->renewal->confirmation_emailed = true;
        $this->individualRenewal->renewal->save();
    }

    /**
     * Returns the receipt/invoice PDF contents in base64 version.
     *
     * @return string
     */
    private function getInvoiceContents()
    {
        return base64_encode(Browsershot::createReceipt($this->receipt->load('payments')));
    }
}
