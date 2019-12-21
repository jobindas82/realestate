<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Head extends Model
{

    protected $table = 'finance';

    protected $fillable = [
        'date', 'type', 'number', 'method', 'contract_id', 'building_id', 'flat_id', 'tenant_id', 'cheque_date', 'cheque_no'
    ];

    const METHOD = [1 => 'Cash', 2 => 'Cheque', 3 => 'Bank Transfer'];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
    }

    public function entry_type()
    {
        return $this->belongsTo(Types::class, 'contract_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contracts::class, 'contract_id');
    }

    public function building()
    {
        return $this->belongsTo(Buildings::class, 'building_id');
    }

    public function flat()
    {
        return $this->belongsTo(Flats::class, 'flat_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenants::class, 'tenant_id');
    }

    public function items()
    {
        return $this->hasMany(Entries::class, 'head_id');
    }

    public function encoded_key()
    {
        return $this->exists ? UriEncode::encrypt((int) $this->id) : '';
    }

    public function post()
    {
        $this->is_posted = 1;
        $this->save();
    }

    public function unPost()
    {
        $this->is_posted = 0;
        $this->save();
    }

    public function cancel()
    {
        $this->is_posted = 0;
        $this->is_cancelled = 1;
        $this->save();
    }

    public function isPosted()
    {
        return $this->is_posted == 0 ? false : true;
    }

    public function isCancelled()
    {
        return $this->is_cancelled == 0 ? false : true;
    }

    public function createNumber()
    {
        if ($this->entry_type->current > 0) {
            $newNumber = $this->entry_type->current + 1;
            $this->entry_type->current = $newNumber;
            $this->entry_type->save();
            return (int) $newNumber;
        }
        $this->entry_type->current = $this->start;
        $this->entry_type->save();
        return (int) $this->entry_type->start;
    }

    public function amount()
    {
        return 0;
    }

    public function formated_date()
    {
        return $this->exists && $this->date != NULL &&   $this->date != '0000-00-00' ? date('d/m/Y', strtotime($this->date)) : '';
    }

    public function formated_cheque_date()
    {
        return $this->exists && $this->cheque_date != NULL &&   $this->cheque_date != '0000-00-00' ? date('d/m/Y', strtotime($this->cheque_date)) : '';
    }

    public function cash_account()
    {
        return 0;
    }

    public function cheque_account()
    {
        return 0;
    }

    public function bank_account()
    {
        return 0;
    }
}