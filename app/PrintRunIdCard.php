<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/** @mixin \Eloquent */
class PrintRunIdCard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'card_id', 'full_name', 'membership_number', 'member_since', 'discipline_list'
    ];
}
