<?php

namespace App\models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

use Akaunting\Money\Currency;
use Akaunting\Money\Money;

class Contracts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contracts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id', 'building_id', 'flat_id', 'generated_date', 'from_date', 'to_date',
        'util_payment', 'is_active', 'is_renewed', 'previous_contract', 'terms'
    ];

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            $model->created_by =  Auth::user()->id;
        });
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

    public function parentContract()
    {
        return $this->belongsTo(Contracts::class, 'previous_contract');
    }

    public function items()
    {
        return $this->hasMany(ContractItems::class, 'contract_id');
    }

    public function encoded_key()
    {
        return $this->exists ? UriEncode::encrypt((int) $this->id) : '';
    }

    public function formated_generated_date()
    {
        return $this->exists && $this->generated_date != NULL &&   $this->generated_date != '0000-00-00' ? date('d/m/Y', strtotime($this->generated_date)) : '';
    }

    public function formated_from_date()
    {
        return $this->exists && $this->from_date != NULL &&   $this->from_date != '0000-00-00' ? date('d/m/Y', strtotime($this->from_date)) : '';
    }

    public function formated_to_date()
    {
        return $this->exists && $this->to_date != NULL &&   $this->to_date != '0000-00-00' ? date('d/m/Y', strtotime($this->to_date)) : '';
    }

    public function status()
    {
        if ($this->is_active == 1)
            return 'Active';
        else
            return 'Closed';
    }

    public function grossAmount()
    {
        return Money::AED($this->items->sum('net_amount'), true)->format();
    }

    public function getContractDetailsAttribute()
    {
        return $this->id . ' | ' . $this->tenant->name;
    }

    public static function activeContracts($id = 0, $prepend = false)
    {
        $query = self::query()->where('is_active', 1);
        if( $id > 0)
            $query->orWhere('id', $id);
        $response = $query->get()->pluck('contract_details', 'id');
        if( $prepend )
            $response = $response->prepend('0 | None', 0);
        return $response;
    }
}
