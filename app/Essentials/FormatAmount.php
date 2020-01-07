<?php

namespace App\Essentials;

use App\models\Ledgers;

class FormatAmount
{
    public $amount =0;
    public $ledger =0;
    public $roundOff =0;

    function __construct($amount = 0, $ledger = 0, $roundOff= 2)
    {   
        $this->amount = round($amount, $roundOff);
        $this->ledger = $ledger;
        $this->roundOff = $roundOff;
    }

    public static function format($amount = 0, $ledger = 0){
        return new self($amount, $ledger);
    }

    function _text_notation(){
        return $this->amount > 0 ? ' Dr' : ' Cr';
    }

    public function outText(){
        return number_format(round(abs($this->amount), 2),  2, '.', ',').$this->_text_notation();
    }

    public function onBase(){
        return number_format(Ledgers::onBaseFormat($this->amount, $this->ledger),  $this->roundOff, '.', '');
    }
}