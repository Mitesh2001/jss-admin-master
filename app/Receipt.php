<?php

namespace App;

use App\Traits\LogDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Receipt extends Model
{
    use LogsActivity, LogDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['individual_id', 'dated_at'];

    /**
     * Validation rules
     *
     * @return array validation rules
     **/
    public static function validationRules()
    {
        return [
            'individual_id' => 'required|numeric|exists:individuals,id',
            'dated_at' => 'required|date|date_format:Y-m-d'
        ];
    }

    /**
     * Get the individual of the receipt.
     */
    public function individual()
    {
        return $this->individuals()->wherePivot('is_payer', 1);
    }

    /**
     * Get the individuals of the receipt.
     */
    public function individuals()
    {
        return $this->belongsToMany('App\Individual', 'receipt_individuals', 'receipt_id', 'individual_id')
            ->withPivot('id', 'is_payer')
        ;
    }

    /**
     * Get the items of the receipt.
     */
    public function items()
    {
        return $this->hasMany('App\ReceiptItem');
    }

    /**
     * Get the payments of the receipt.
     */
    public function payments()
    {
        return $this->hasMany('App\ReceiptPayment');
    }

    /**
     * Returns the receipt date in Carbon instance.
     *
     * @return \Carbon\Carbon
     */
    public function getDateCarbon()
    {
        return Carbon::createFromFormat('Y-m-d', $this->dated_at);
    }

    /**
     * Returns the received amount of the receipt from the payments.
     *
     * @return float
     */
    public function getReceivedAmount()
    {
        return formattedRound($this->payments->sum->amount);
    }

    /**
     * Returns the total amount of the receipt from items.
     *
     * @return float
     */
    public function getTotalAmount()
    {
        return formattedRound($this->items->sum->amount);
    }

    /**
     * Returns the suburb label of the entity.
     *
     * @return string
     */
    public function getEntitySuburbLabel()
    {
        return optional(optional($this->individual[0])->suburb)->label;
    }

    /**
     * Returns the state label of the entity.
     *
     * @return string
     */
    public function getEntityStateLabel()
    {
        return optional(optional($this->individual[0])->state)->label;
    }

    /**
     * Returns the outstanding amount of the receipt.
     *
     * @return float
     */
    public function getOutstandingAmount()
    {
        return formattedRound((float) $this->getTotalAmount() - (float) $this->getReceivedAmount());
    }

    /**
     * Specify row class name for datatables.
     *
     * @param \App\Receipt $receipt
     * @return string
     */
    public static function laratablesRowClass($receipt)
    {
        return (float) $receipt->getTotalAmount() < 0.1 ? 'table-danger' : '';
    }

    /**
     * Returns the receipt date to d-m-y format for datatables.
     *
     * @param \App\Receipt $receipt
     * @return string
     */
    public static function laratablesDatedAt($receipt)
    {
        return $receipt->getDateCarbon()->format('d-m-Y');
    }

    /**
     * Returns the name of the individual for datatables.
     *
     * @param \App\Receipt
     * @return string
     */
    public static function laratablesCustomIndividualName($receipt)
    {
        return view('admin.receipts.includes.index_name', compact('receipt'))->render();
    }

    /**
     * Adds the condition for searching the individual of the receipt in the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @param string search term
     * @param \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesSearchIndividualName($query, $searchValue)
    {
        return $query->orWhereHas('individuals', function ($query) use ($searchValue) {
            $query->where('first_name', 'like', '%' . $searchValue . '%')
                ->orWhere('surname', 'like', '%' . $searchValue . '%')
                ->orWhereRaw('CONCAT(first_name, " ", surname) like ' . "'%" . $searchValue . "%'")
            ;
        });
    }

    /**
     * Returns the total amount with currency sign for datatables.
     *
     * @param \App\Receipt
     * @return string
     */
    public static function laratablesCustomTotalAmount($receipt)
    {
        return '$' . $receipt->getTotalAmount();
    }

    /**
     * Returns the received amount with currency sign for datatables.
     *
     * @param \App\Receipt
     * @return string
     */
    public static function laratablesCustomReceivedAmount($receipt)
    {
        $class = '';
        if ((float) $receipt->getTotalAmount() > (float) $receipt->getReceivedAmount()) {
            $class = 'text-danger';
        }

        return '<span class="' . $class . '">$' . $receipt->getReceivedAmount() . '</span>';
    }

    /**
     * Returns the action column html for datatables
     *
     * @param \App\Receipt
     * @return string
     */
    public static function laratablesCustomAction($receipt)
    {
        return view('admin.receipts.includes.index_action', compact('receipt'))->render();
    }

    /**
     * Returns the receipt with all details
     *
     * @param int Id of the receipt
     * @return array
     **/
    public static function fetchWithDetails($receiptId)
    {
        return static::with([
            'individual',
            'individual.suburb',
            'individual.state',
            'items',
            'items.code',
            'payments',
            'payments.type:id,label',
        ])->findOrFail($receiptId);
    }

    /**
     * Returns the static options for the receipt
     *
     * @return array
     **/
    public static function getStaticOptions()
    {
        return [
            'paymentTypes' => PaymentType::getList(),
            'disciplines' => Discipline::all(),
            'membershipTypes' => MembershipType::getList(),
            'receiptCodes' => ReceiptItemCode::all(),
        ];
    }

    /**
     * Prepares the receipt item details
     *
     * @param array $receiptItemDetails
     * @return array $receiptItemDetails
     **/
    public function prepareItemDetails($receiptItemDetails)
    {
        $receiptItemDetails['discipline_id'] = $receiptItemDetails['type_id'] == 2 ? $receiptItemDetails['discipline_id'] : null;

        unset($receiptItemDetails['type_id']);

        return $receiptItemDetails;
    }
}
