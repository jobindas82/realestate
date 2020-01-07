<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\models\Head;

class BalanceSheetAssets extends Model
{
    protected $table = 'balance_sheet_asset';

    public static function LevelOne()
    {
        return self::where('level', 1)->get();
    }
    public static function LevelTwo($parent)
    {
        return self::where('level', 2)->where('parent_id', $parent)->get();
    }
    public static function LevelThree($parent)
    {
        return self::where('level', 3)->where('parent_id', $parent)->get();
    }
    public static function LevelFour($parent)
    {
        return self::where('level', 4)->where('parent_id', $parent)->get();
    }
    public static function LevelFive($parent)
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

    public function makeBold()
    {
        return $this->is_parent == 'Y' ? '<b>' . $this->ledger_name . '</b>' : $this->ledger_name;
    }

    public function ledgerName()
    {
        return $this->addSpace() . $this->makeBold();
    }

    public function BalanceInBase(){
        return number_format(round((float) $this->balance, 2),  2, '.', ',');
    }

    public static function sum(){
        return self::where('is_parent', 'N')->sum('balance');
    }

    public function isParent(){
        return $this->is_parent == 'Y' ? true : false;
    }
    
    public function addSpaceExcel()
    {
        $space = '';
        for ($i = 0; $i < $this->level * 4; $i++) {
            $space .= '   ';
        }
        return $space;
    }

    public function ledgerNameExcel()
    {
        return $this->addSpaceExcel() . $this->ledger_name;
    }
}
