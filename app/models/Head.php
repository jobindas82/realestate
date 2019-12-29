<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Essentials\UriEncode;

class Head extends Model
{

    protected $table = 'finance';

    protected $fillable = [
        'date', 'type', 'number', 'method', 'contract_id', 'building_id', 'flat_id', 'tenant_id', 'cheque_date', 'cheque_no', 'narration'
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
        return $this->belongsTo(Types::class, 'type');
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

    public function entries()
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

    public function revertCancel()
    {
        $this->is_posted = 1;
        $this->is_cancelled = 0;
        $this->save();
    }

    public function returnCheque()
    {
        $this->cancel();
        $this->cheque_status = 2;
        $this->save();
    }

    public function resetCheque()
    {
        if ($this->id > 0) {
            $this->revertCancel();
            $this->cheque_status = 0;
            $this->save();
        }
    }

    public function clearCheque()
    {
        $this->cheque_status = 1;
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

    public function isReturned()
    {
        return $this->cheque_status == 2 ? true : false;
    }

    public function createNumber($update = true)
    {
        $this->number = $this->entry_type->generate($update);
    }

    public function debitSum($format = false)
    {
        $amount = $this->entries()->where('amount', '>', '0')->get()->sum('amount');
        return $format ? number_format($amount, 2, '.', ',') : $amount;
    }

    public function totalAmount($format = false)
    {
        return \Akaunting\Money\Money::AED($this->entries()->where('amount', '>', '0')->get()->sum('amount'), true)->format();
    }

    public function creditSum($reverse = false)
    {
        $amount = $this->entries()->where('amount', '<', '0')->get()->sum('amount');
        return $reverse ? -1 * $amount : $amount;
    }

    public function formated_date()
    {
        return $this->exists && $this->date != NULL &&   $this->date != '0000-00-00' ? date('d/m/Y', strtotime($this->date)) : '';
    }

    public function formated_cheque_date()
    {
        return $this->exists && $this->cheque_date != NULL &&   $this->cheque_date != '0000-00-00' ? date('d/m/Y', strtotime($this->cheque_date)) : '';
    }

    public function fillContract()
    {
        if ($this->contract_id > 0) {
            $this->building_id = $this->contract->building_id;
            $this->flat_id  = $this->contract->flat_id;
            $this->tenant_id  = $this->contract->tenant_id;
        }
    }

    public function createEntries($entries = [], $fillDate = true, $fillContractInfo = false)
    {
        if (count($entries) > 0) {
            foreach ($entries as $each) {
                $each->head_id = $this->id;
                if ($fillContractInfo) {
                    if ($this->contract_id > 0) {
                        $each->tenant_id =  $this->tenant_id;
                        $each->flat_id =  $this->flat_id;
                        $each->building_id =  $this->building_id;
                        $each->contract_id =  $this->contract_id;
                    }
                }
                if ($fillDate) {
                    $each->date =  $this->date;
                }
                if (round($each->amount, 6) != 0)
                    $each->save();
            }
        }
    }

    public function update_ubl()
    {
        if ($this->id > 0) {
            $debitAmount = (float) $this->debitSum();
            $creditAmount = (float) $this->creditSum(true);

            if ($debitAmount != $creditAmount) {
                $balanceAmount = $debitAmount > $creditAmount ? $creditAmount - $debitAmount : ($debitAmount - $creditAmount) * -1;
                $item = new Entries;
                $item->ledger_id = Ledgers::findClass(Ledgers::UNBALANCED_AMT)->id;
                $item->amount = $balanceAmount;
                $item->code = 'UB';
                $this->createEntries([$item], true, true);
            }
        }
    }

    public function updateChequeDates()
    {
        foreach ($this->entries as $each) {
            $each->date = $this->cheque_date;
            $each->save();
        }
    }

    public function updateEntryByCode($code = NULL, $ledger_id = 0)
    {
        if ($code != NULL && $ledger_id > 0) {
            $model = $this->entries()->where('code', $code)->first();
            $model->ledger_id = $ledger_id;
            $model->save();
        }
    }

    public function paymentMethod(){
        return self::METHOD[$this->method];
    }

    public function paymentMethodDetails(){
        return self::METHOD[$this->method];
    }
}
