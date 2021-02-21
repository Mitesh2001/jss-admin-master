<?php

namespace App;

use App\Traits\LogDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class ReceiptItem extends Model
{
    use LogDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['discipline_id', 'receipt_item_code_id', 'description', 'amount'];

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'receipt_item_code_id' => 'required|numeric|exists:receipt_item_codes,id',
            'type_id' => 'required_if:receipt_item_code_id,1,2,3|nullable|numeric|in:1,2',
            'discipline_id' => 'required_if:type_id,2|nullable|numeric|exists:disciplines,id',
            'description' => 'nullable|string',
            'amount' => 'required|numeric',
        ];
    }

    /**
     * Get the code of the receipt item.
     */
    public function code()
    {
        return $this->belongsTo('App\ReceiptItemCode', 'receipt_item_code_id', 'id');
    }

    /**
     * Get the discipline of the receipt item.
     */
    public function discipline()
    {
        return $this->belongsTo('App\Discipline');
    }

    /**
     * Get the receipt of the receipt item.
     */
    public function receipt()
    {
        return $this->belongsTo('App\Receipt');
    }

    /**
     * Returns the item label
     *
     * @return string item label
     **/
    public function getLabel()
    {
        if ($this->discipline_id) {
            return $this->discipline->label;
        }

        if (in_array($this->receipt_item_code_id, [1, 2, 3])) {
            return 'Membership';
        }

        return 'Other';
    }

    /**
     * Returns the formatted date of the receipt item for datatables.
     *
     * @param \App\ReceiptItem $receiptItem
     * @return string
     */
    public static function laratablesCreatedAt($receiptItem)
    {
        return $receiptItem->created_at->format('Y-m-d');
    }

    /**
     * Returns the payment date of the receipt item for datatables.
     *
     * @param \App\ReceiptItem
     * @return string
     */
    public static function laratablesCustomReceiptPaymentDate($receiptItem)
    {
        return (new Carbon($receiptItem->receipt->payments->max('paid_at')))->format('Y-m-d');
    }

    /**
     * Returns the name of the individual for datatables.
     *
     * @param \App\ReceiptItem
     * @return string
     */
    public static function laratablesCustomIndividualName($receiptItem)
    {
        return view('admin.reports.payments.includes.index_name', compact('receiptItem'))->render();
    }

    /**
     * Returns the receipt id for datatables.
     *
     * @param \App\ReceiptItem
     * @return string
     */
    public static function laratablesCustomReceiptId($receiptItem)
    {
        return view('admin.reports.payments.includes.receipt_id_link', compact('receiptItem'))->render();
    }

    /**
     * Returns the receipt item label for datatables.
     *
     * @param \App\ReceiptItem
     * @return string
     */
    public static function laratablesCustomItemLabel($receiptItem)
    {
        return $receiptItem->code->label . ' - ' . $receiptItem->getLabel();
    }

    /**
     * Returns the calculated receipt item fees for datatables.
     *
     * @param \App\ReceiptItem
     * @return string
     */
    public static function laratablesCustomFee($receiptItem)
    {
        return '$' . $receiptItem->getCalculatedFee();
    }

    /**
     * Returns the calculated receipt item fees.
     *
     * @return double
     */
    public function getCalculatedFee()
    {
        $totalReceiptAmount = $this->receipt->payments->sum('amount');
        $totalReceiptFee = $this->receipt->payments->sum('transaction_fee');

        if (! $this->amount || ! $totalReceiptAmount || ! $totalReceiptFee) {
            return 0;
        }

        return formattedRound($this->amount * $totalReceiptFee / $totalReceiptAmount);
    }

    /**
     * Specify filter conditions for the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        return static::applyFilters($query);
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return ['receipt_id', 'receipt_item_code_id', 'discipline_id'];
    }

    /**
     * Apply datatable filters
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param array Filter values (optional)
     * @return \Illuminate\Database\Eloquent\Builder
     **/
    public static function applyFilters($query, $filters = null)
    {
        $filters = $filters ?: [
            'start_date' => session('payments_start_date_filter'),
            'end_date' => session('payments_end_date_filter'),
            'type' => session('payment_discipline_type') != 'all' ? session('payment_discipline_type') : null,
        ];

        return $query
            ->with([
                'code',
                'receipt',
                'discipline',
                'receipt.payments',
                'receipt.individual'
            ])
            ->whereHas('receipt', function ($query) {
                $receiptPayments = \DB::table('receipt_payments')
                    ->select('receipt_id', \DB::raw('SUM(amount) as payments_total'))
                    ->groupBy('receipt_id')
                ;
                $receiptItems = \DB::table('receipt_items')
                    ->select('receipt_id', \DB::raw('SUM(amount) as items_total'))
                    ->groupBy('receipt_id')
                ;

                $query->joinSub($receiptPayments, 'custom_receipt_payments', function ($join) {
                    $join->on('receipts.id', '=', 'custom_receipt_payments.receipt_id');
                })->joinSub($receiptItems, 'custom_receipt_items', function ($join) {
                    $join->on('receipts.id', '=', 'custom_receipt_items.receipt_id');
                })->whereRaw('payments_total >= items_total');
            })
            ->whereHas('receipt.payments', function ($query) use ($filters) {
                $query->where('paid_at', '>=', $filters['start_date'])
                    ->where('paid_at', '<=', $filters['end_date'])
                ;
            })
            ->when($filters['type'] != 'all', function ($query) use ($filters) {
                $query->where('discipline_id', $filters['type']);
            })
        ;
    }
}
