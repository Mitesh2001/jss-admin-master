<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Individual;
use App\Receipt;
use App\ReceiptItem;
use App\ReceiptPayment;
use App\Services\Browsershot;
use Freshbitsweb\Laratables\Laratables;

class ReceiptController extends Controller
{
    /**
     * Display receipts.
     *
     * @return \Illuminate\Http\Response
     **/
    public function index()
    {
        $individuals = Individual::all();

        return view('admin.receipts.index', compact('individuals'));
    }

    /**
     * Returns list of receipts.
     *
     * @return json
     **/
    public function datatables()
    {
        return Laratables::recordsOf(Receipt::class);
    }

    /**
     * Saves new receipt.
     *
     * @return \Illuminate\Http\RedirectResponse
     **/
    public function store()
    {
        $validatedData = request()->validate(Receipt::validationRules());
        unset($validatedData['individual_id']);
        $receipt = Receipt::create($validatedData);
        $receipt->individuals()->attach([request('individual_id')]);

        return redirect()->route('admin.receipts.edit', ['receipt' => $receipt->id])->with([
            'type' => 'success',
            'message' => 'Receipt created successfully.'
        ]);
    }

    /**
     * Displays the form for editing the specified receipt.
     *
     * @param int $receiptId
     * @return \Illuminate\Http\Response
     */
    public function edit($receiptId)
    {
        $receipt = Receipt::fetchWithDetails($receiptId);

        $parameters = Receipt::getStaticOptions();
        $parameters['receipt'] = $receipt;

        return view('admin.receipts.edit', $parameters);
    }

    /**
     * Update receipt details.
     *
     * @param \App\Receipt $receipt
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Receipt $receipt)
    {
        $validatedData = request()->validate([
            'dated_at' => 'required|date|date_format:Y-m-d'
        ]);

        $receipt->update($validatedData);

        return redirect()->route('admin.receipts.index')->with([
            'type' => 'success',
            'message' => 'Receipt updated successfully.'
        ]);
    }

    /**
     * Delete the Receipt
     *
     * @param \App\Receipt $receipt
     * @return void
     */
    public function destroy(Receipt $receipt)
    {
        // Let's delete the related records first
        ReceiptItem::where('receipt_id', $receipt->id)->get()->each->delete();
        ReceiptPayment::where('receipt_id', $receipt->id)->get()->each->delete();

        $receipt->individuals()->detach();

        $receipt->delete();

        return redirect()->back()->with([
            'type' => 'success',
            'message' => 'Receipt deleted successfully.'
        ]);
    }

    /**
     * Print the receipt
     *
     * @param int $receiptId
     * @return \Illuminate\Http\Response
     **/
    public function print($receiptId)
    {
        $receipt = Receipt::fetchWithDetails($receiptId);

        return Browsershot::createReceipt($receipt);
    }
}
