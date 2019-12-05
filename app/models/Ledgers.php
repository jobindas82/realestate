<?php

namespace App\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Ledgers extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ledgers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'name', 'is_active',
    ];
}
