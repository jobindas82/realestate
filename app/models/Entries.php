<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Entries extends Model
{

    protected $table = 'entries';
    public $timestamps = false;

    protected $fillable = [
        'head_id', 'ledger_id', 'amount', 'code', 'contract_id', 'building_id', 'flat_id', 'tenant_id'
    ];

    public function ledger()
    {
        return $this->belongsTo(Ledgers::class, 'ledger_id');
    }

    public function head()
    {
        return $this->belongsTo(Head::class, 'head_id');
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
}
