<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ContractSettlement extends Model
{

    protected $table = 'contract_settlement';

    protected $fillable = [
        'contract_id', 'remarks', 'amount'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }
}
