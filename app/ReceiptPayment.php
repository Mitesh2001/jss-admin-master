<?php

namespace App;

use App\Traits\LogDeletes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class ReceiptPayment extends Model
{
    use LogDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id', 'amount', 'notes', 'stripe_transfer_no', 'transaction_fee', 'paid_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_paid_at',
    ];

    /**
     * Validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'type_id' => 'required|numeric|exists:payment_types,id',
            'paid_at' => 'required|date',
            'amount' => 'required|numeric',
            'transaction_fee' => 'nullable|numeric',
            'notes' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the type of the payment
     **/
    public function type()
    {
        return $this->belongsTo('App\PaymentType');
    }

    /**
     * Get the formatted paid at for the payment.
     *
     * @return string
     */
    public function getFormattedPaidAtAttribute()
    {
        if (! isset($this->attributes['paid_at'])) {
            return '';
        }

        return (new Carbon($this->attributes['paid_at']))->format('d-m-Y');
    }
}
