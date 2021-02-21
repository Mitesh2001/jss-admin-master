<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

/** @mixin \Eloquent */
class Key extends Model
{
    use LogsActivity, SoftDeletes;

    protected static $logAttributes = ['*'];

    protected static $logOnlyDirty = true;

    /** @var array $keyTypes */
    public static $keyTypes = [
        1 => 'General',
        2 => 'Committee'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "individual_id", "key_type", "key_number", "issued_at", "deposit_amount", "returned_at", "loosed_at"
    ];

    /**
     * Returns the validation rules
     *
     * @return array
     **/
    public static function validationRules()
    {
        return [
            'individual_id' => 'required|exists:individuals,id',
            'key_type' => 'required|in:1,2',
            'key_number' => 'required|numeric|min:1|max:1000',
            'deposit_amount' => 'required|numeric|min:0|max:999999.99',
            'issued_at' => 'required|date',
        ];
    }

    /**
     * Get the individual that owns the key.
     */
    public function individual()
    {
        return $this->belongsTo('App\Individual');
    }

    /**
     * Returns formatted member name for the datatables.
     *
     * @param \App\Key $key
     * @return string
     */
    public static function laratablesIndividualFirstName($key)
    {
        return $key->individual->getName();
    }

    /**
     * Returns formatted key type for the datatables.
     *
     * @param \App\Key $key
     * @return string
     */
    public static function laratablesKeyType($key)
    {
        return static::$keyTypes[$key->key_type];
    }

    /**
     * Returns formatted returned at for the datatables.
     *
     * @param \App\Key $key
     * @return string
     */
    public static function laratablesReturnedAt($key)
    {
        return $key->returned_at ?? 'N/A';
    }

    /**
     * Returns the action column html for datatables.
     *
     * @param \App\Key $key
     * @return string
     */
    public static function laratablesCustomAction($key)
    {
        return view('admin.keys.index_action', compact('key'))->render();
    }

    /**
     * Additional columns to be loaded for datatables.
     *
     * @return array
     */
    public static function laratablesAdditionalColumns()
    {
        return [
            'loosed_at', 'returned_at'
        ];
    }

    /**
     * Load action type.
     *
     * @param \Illuminate\Database\Eloquent\Builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function laratablesQueryConditions($query)
    {
        $keyStatus = session('key_status');
        $keyType = session('key_type');

        $query = $query
            ->where(function ($query) use ($keyStatus) {
                $query->when($keyStatus == 1, function ($query) {
                    $query->whereNull('returned_at')
                        ->whereNull('loosed_at')
                    ;
                })
                ->when($keyStatus == 2, function ($query) {
                    $query->whereNotNull('returned_at')
                        ->orWhereNotNull('loosed_at')
                    ;
                });
            })
            ->when(in_array($keyType, [1, 2]), function ($query) use ($keyType) {
                $query->where('key_type', $keyType);
            })
        ;

        return $query;
    }
}
