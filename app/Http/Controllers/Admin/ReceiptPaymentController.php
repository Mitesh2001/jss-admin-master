<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Receipt;
use App\ReceiptPayment;

class ReceiptPaymentController extends Controller
{
    /**
     * Save new payment of the receipt
     *
     * @param \App\Receipt
     * @return array
     **/
    public function store(Receipt $receipt)
    {
        $validatedData = request()->validate(ReceiptPayment::validationRules());

        $payment = $receipt->payments()->create($validatedData);
        $payment->load('type');
        $receipt->load('payments');

        return [
            'payment' => $payment,
            'receiptReceivedAmount' => $receipt->getReceivedAmount(),
        ];
    }

    /**
     * Updates the payment for the receipt.
     *
     * @param int receipt id
     * @param \App\ReceiptPayment
     * @return array
     */
    public function update($receiptId, ReceiptPayment $payment)
    {
        $validatedData = request()->validate(ReceiptPayment::validationRules());

        $payment->update($validatedData);
        $payment->load('type');

        $receipt = Receipt::with('payments')->find($receiptId);

        return [
            'payment' => $payment,
            'receiptReceivedAmount' => $receipt->getReceivedAmount(),
        ];
    }

    /**
     * Deletes the payment.
     *
     * @param int receipt id
     * @param \App\ReceiptPayment
     * @return int total received amount
     */
    public function destroy($receiptId, ReceiptPayment $payment)
    {
        $payment->delete();

        return Receipt::with('payments')->find($receiptId)->getReceivedAmount();
    }
}
