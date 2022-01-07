<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topup extends Model
{

    protected $table = 'topup';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'amount',
        'status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}