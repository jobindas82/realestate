<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class TrialBalance extends Model
{
    protected $table = 'trial_balance';

    public static function levelOne()
    {
        return self::where('level', 1)->get();
    }

    public static function levelTwo($parent)
    {
        return self::where('level', 2)->where('parent_id', $parent)->get();
    }

    public static function levelThree($parent)
    {
        return self::where('level', 3)->where('parent_id', $parent)->get();
    }

    public static function levelFour($parent)
    {
        return self::where('level', 4)->where('parent_id', $parent)->get();
    }

    public static function levelFive($parent)
    {
        return self::where('level', 5)->where('parent_id', $parent)->get();
    }

    public function addSpace()
    {
        $space = '';
        for ($i = 0; $i < $this->level; $i++) {
            $space .= '&nbsp;&nbsp;&nbsp;&nbsp;';
        }
        return $space;
    }

    public function addSpaceExcel()
    {
        $space = '';
        for ($i = 0; $i < $this->level * 4; $i++) {
            $space .= '   ';
        }
        return $space;
    }

    public function makeBold()
    {
        return $this->is_parent == 'Y' ? '<b>' . $this->ledger_name . '</b>' : $this->ledger_name;
    }
    
    public function isParent(){
        return $this->is_parent == 'Y' ? true : false;
    }
    
    public function ledgerName()
    {
        return $this->addSpace() . $this->makeBold();
    }

    public function ledgerNameExcel()
    {
        return $this->addSpaceExcel() . $this->ledger_name;
    }

    public function currentDebit()
    {
        return $this->current_balance > 0 ? number_format(round($this->current_balance, 2),  2, '.', '') : null;
    }

    public function currentCredit()
    {
        return $this->current_balance < 0 ? number_format(round(abs($this->current_balance), 2),  2, '.', '') : null;
    }

    public function previousDebit()
    {
        return $this->previous_balance > 0 ? number_format(round($this->previous_balance, 2),  2, '.', '') : null;
    }

    public function previousCredit()
    {
        return $this->previous_balance < 0 ? number_format(round(abs($this->previous_balance), 2),  2, '.', '') : null;
    }

    public static function currentDebitSum()
    {
        return self::where('is_parent', 'N')->where('current_balance', '>', 0)->sum('current_balance');
    }
    public static function currentCreditSum()
    {
        return self::where('is_parent', 'N')->where('current_balance', '<', 0)->sum('current_balance');
    }
    public static function previousDebitSum()
    {
        return self::where('is_parent', 'N')->where('previous_balance', '>', 0)->sum('previous_balance');
    }
    public static function previousCreditSum()
    {
        return self::where('is_parent', 'N')->where('previous_balance', '<', 0)->sum('previous_balance');
    }
}
