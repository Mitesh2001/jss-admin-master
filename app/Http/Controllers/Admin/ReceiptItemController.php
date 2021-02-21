<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Receipt;
use App\ReceiptItem;

class ReceiptItemController extends Controller
{
    /**
     * Save new item of the receipt
     *
     * @param \App\Receipt
     * @return array
     **/
    public function store(Receipt $receipt)
    {
        $validatedData = request()->validate(ReceiptItem::validationRules());

        $receiptItem = $receipt->items()->create($receipt->prepareItemDetails($validatedData));
        $receiptItem->load('code');

        return [
            'item' => $receiptItem,
            'receiptTotalAmount' => $receipt->getTotalAmount(),
        ];
    }

    /**
     * Updates the items of the receipt.
     *
     * @param \App\Receipt $receipt
     * @param \App\ReceiptItem $item
     * @return array
     */
    public function update(Receipt $receipt, ReceiptItem $item)
    {
        $validatedData = request()->validate(ReceiptItem::validationRules());

        $item->update($receipt->prepareItemDetails($validatedData));
        $item->load('code');

        return [
            'item' => $item,
            'receiptTotalAmount' => $receipt->load('items')->getTotalAmount(),
        ];
    }

    /**
     * Deletes the receipt item.
     *
     * @param int receipt id
     * @param \App\ReceiptItem
     * @return int receipt total amount
     */
    public function destroy($receiptId, ReceiptItem $item)
    {
        $item->delete();

        return Receipt::with('items')->find($receiptId)->getTotalAmount();
    }
}
