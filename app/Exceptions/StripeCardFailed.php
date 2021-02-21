<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class StripeCardFailed extends Exception
{
    /** @var string $exceptionMessage */
    protected $exceptionMessage;

    public function __construct($exceptionMessage) {
        $this->exceptionMessage = $exceptionMessage;
    }

    /**
     * Report the exception.
     *
     * @return void
     */
    public function report()
    {
        Log::channel('stripe_failed')
            ->info('Stripe Card issue', [
                'error' => $this->exceptionMessage,
                'request_data' => request()->all()
            ])
        ;
    }

    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return redirect()->back()->withInput()->withErrors([
            'stripe' => $this->exceptionMessage
        ]);
    }
}
