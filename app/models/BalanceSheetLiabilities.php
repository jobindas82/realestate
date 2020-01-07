<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\models\Head;

class BalanceSheetLiabilities extends Model
{
    protected $table = 'balance_sheet_liability';

    public static function currentProfitEntry($toDate)
    {
        $year = Carbon::createFromFormat('Y-m-d', $toDate)->year;
        $fromDate = $year . '-01-01';
        $currentProfit = \App\Models\Entries::leftJoin('ledgers', 'entries.ledger_id', 'ledgers.id')
            ->where('entries.is_posted', 1)
            ->whereIn('ledgers.type', ["I", "E"])
            ->whereBetween('entries.date', [$fromDate, $toDate])
            ->sum('entries.amount');

        $retainedEarnings = \App\Models\Entries::leftJoin('ledgers', 'entries.ledger_id', 'ledgers.id')
            ->where('entries.is_posted', 1)
            ->whereIn('ledgers.type', ["I", "E"])
            ->where('entries.date', '<', $fromDate)
            ->sum('entries.amount');


        $model = new Head();
        $model->date = $toDate;
        $model->is_posted = 1;
        if ($model->save()) {

            $currentProfitEntry = new Entries;
            $currentProfitEntry->ledger_id = Ledgers::findClass(Ledgers::CURRENT_PROFIT)->id;
            $currentProfitEntry->amount = $currentProfit;
            $currentProfitEntry->date = $toDate;;
            $currentProfitEntry->code = 'CPR';

            $retainedEntry = new Entries;
            $retainedEntry->ledger_id = Ledgers::findClass(Ledgers::RETAINED_EARNINGS)->id;
            $retainedEntry->amount = $retainedEarnings;
            $retainedEntry->date = $toDate;;
            $retainedEntry->code = 'EAR';

            $model->createEntries([$currentProfitEntry, $retainedEntry], false, true);
        }
        return $model->id;
    }

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

    public function BalanceInBase()
    {
        return number_format(round((float) -1 * $this->balance, 2),  2, '.', ',');
    }

    public static function sum()
    {
        return number_format(round((float) -1 * self::where('is_parent', 'N')->sum('balance'), 2),  2, '.', ',');
    }

    public function isParent()
    {
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
