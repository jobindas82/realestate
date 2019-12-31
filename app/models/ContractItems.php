<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class ContractItems extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'contract_items';
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'contract_id', 'ledger_id', 'amount', 'tax_id', 'tax_percentage', 'tax_amount', 'net_amount'
    ];

    public function contract()
    {
        return $this->belongsTo(Contracts::class, 'contract_id');
    }


    public function ledger()
    {
        return $this->belongsTo(Ledgers::class, 'ledger_id');
    }

    public function tax()
    {
        return $this->belongsTo(TaxCode::class, 'tax_id');
    }

    public function ratio(){
        $grossAmount  = $this->contract->gross_amount_wo_format();
        if( $grossAmount > 0 ){
            return round(($this->amount / $grossAmount), 6);
        }
        return 0;
    }
}
