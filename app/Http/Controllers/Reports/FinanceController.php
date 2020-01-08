<?php

namespace App\Http\Controllers\Reports;

use App\models\Head;
use App\models\Entries;
use App\models\Ledgers;
use App\models\TrialBalance;
use App\models\BalanceSheetAssets;
use App\models\BalanceSheetLiabilities;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Carbon;
use Akaunting\Money\Money;

class FinanceController extends \App\Http\Controllers\Controller
{
    public $tempVoucher = 0;

    public function general_ledger()
    {
        return view('reports.finance.filters.gl');
    }

    public function general_ledger_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];

        $ledger_id = (int) $_POST['ledger_id'];
        $from_date = Carbon::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');
        $contract_id = (int) $_POST['contract_id'];

        $query = Entries::query()
            ->leftJoin('ledgers', 'entries.ledger_id', 'ledgers.id')
            ->whereBetween('entries.date', [$from_date, $to_date])
            ->where('entries.is_posted', 1)
            ->where('entries.ledger_id', $ledger_id);

        if ($contract_id > 0) {
            $query->where('ledgers.contract_id', $contract_id);
        }

        $result = $query
            ->select('entries.date', 'entries.head_id', 'entries.amount')
            ->skip($offset)
            ->take($limit)
            ->orderBy('entries.date', 'ASC')
            ->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = [];

        $opening_balance = Entries::where('entries.date', '<', $from_date)
            ->where('entries.is_posted', 1)
            ->where('entries.ledger_id', $ledger_id)->sum('entries.amount');
        $closing = $opening_balance;

        //Opening balance
        $eachItemData[] = [
            null,
            null,
            null,
            null,
            null,
            'Opening Balance',
            null,
            null,
            \App\Essentials\FormatAmount::format($opening_balance)->outText()
        ];

        $debitSum = 0;
        $creditSum = 0;

        foreach ($result as $eachItem) {

            $debit = null;
            $credit = null;
            $closing += $eachItem->amount;

            if ($eachItem->amount > 0) {
                $debitSum += $eachItem->amount;
                $debit = number_format(round($eachItem->amount, 2),  2, '.', ',');
            }

            if ($eachItem->amount < 0) {
                $creditSum += abs($eachItem->amount);
                $credit = $eachItem->amount < 0 ? number_format(round(abs($eachItem->amount), 2),  2, '.', ',') : null;
            }


            $eachItemData[] = [
                $eachItem->formated_date(),
                $eachItem->head->entry_type->name,
                $eachItem->head->number,
                $eachItem->head->cheque_no,
                $eachItem->head->formated_cheque_date(),
                $eachItem->head->narration,
                $debit,
                $credit,
                \App\Essentials\FormatAmount::format($closing)->outText()
            ];
        }

        $data['closing_balance'] = \App\Essentials\FormatAmount::format($closing)->outText();
        $data['debit_sum'] = number_format(round($debitSum, 2),  2, '.', ',');
        $data['credit_sum'] = number_format(round($creditSum, 2),  2, '.', ',');
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function general_ledger_excel()
    {
        $from = Input::get('from');
        $to = Input::get('to');
        $ledger_id = (int) Input::get('ledger');
        $contract_id = (int) Input::get('contract');

        $from_date = Carbon::createFromFormat('d/m/Y', $from)->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $to)->format('Y-m-d');

        $query = Entries::query()
            ->leftJoin('ledgers', 'entries.ledger_id', 'ledgers.id')
            ->whereBetween('entries.date', [$from_date, $to_date])
            ->where('entries.is_posted', 1)
            ->where('entries.ledger_id', $ledger_id);

        if ($contract_id > 0) {
            $query->where('ledgers.contract_id', $contract_id);
        }

        $result = $query
            ->select('entries.date', 'entries.head_id', 'entries.amount')
            ->orderBy('entries.date', 'ASC')
            ->get();

        $opening_balance = Entries::where('entries.date', '<', $from_date)
            ->where('entries.is_posted', 1)
            ->where('entries.ledger_id', $ledger_id)->sum('entries.amount');
        $closing = $opening_balance;

        $excelFile = new \App\Essentials\ExcelBuilder('general_ledger');
        $excelFile->setWorkSheetTitle('General Ledger');
        $excelFile->mergeCenterCells('A1', 'I1');
        $excelFile->setCell('A1', 'General Ledger', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('B2', 'Ledger', ['makeBold' => true]);
        $ledgerModel = Ledgers::find($ledger_id);
        $buildingName =  isset($ledgerModel->id) && $ledgerModel->id > 0 ? $ledgerModel->name : '';
        $excelFile->setCell('C2', $buildingName);

        $excelFile->setCell('B3', 'From', ['makeBold' => true]);
        $excelFile->setCell('C3', $from);
        $excelFile->setCell('B4', 'To', ['makeBold' => true]);
        $excelFile->setCell('C4', $to);

        $row = 5;

        if ($contract_id > 0) {
            $excelFile->setCell('B' . $row, 'Contract', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, $contract_id);
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Date', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Type', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Number', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Cheque No', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Cheque Date', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Narration', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Debit', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Credit', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Running Balance', ['makeBold' => true, 'autoWidthIndex' => 8]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'I' . $row, 'A9DEFB');

        $row++;
        $excelFile->mergeRightCells('A' . $row, 'H' . $row);
        $excelFile->setCell('A' . $row, 'Opening Balance', ['makeBold' => true]);
        $excelFile->setCell('I' . $row, \App\Essentials\FormatAmount::format($opening_balance)->outText(), ['makeBold' => true]);

        $debitSum = 0;
        $creditSum = 0;

        foreach ($result as $eachItem) {

            $debit = null;
            $credit = null;
            $closing += $eachItem->amount;

            if ($eachItem->amount > 0) {
                $debitSum += $eachItem->amount;
                $debit = number_format(round($eachItem->amount, 2),  2, '.', ',');
            }

            if ($eachItem->amount < 0) {
                $creditSum += abs($eachItem->amount);
                $credit = $eachItem->amount < 0 ? number_format(round(abs($eachItem->amount), 2),  2, '.', ',') : null;
            }


            $row++;
            $excelFile->setCellMultiple([
                ['A' . $row, $eachItem->formated_date()],
                ['B' . $row, $eachItem->head->entry_type->name],
                ['C' . $row, $eachItem->head->number],
                ['D' . $row, $eachItem->head->cheque_no],
                ['E' . $row, $eachItem->head->formated_cheque_date()],
                ['F' . $row, $eachItem->head->narration],
                ['G' . $row, $debit],
                ['H' . $row, $credit],
                ['I' . $row, \App\Essentials\FormatAmount::format($closing)->outText()]
            ]);
        }

        $row++;
        $excelFile->mergeRightCells('A' . $row, 'H' . $row);
        $excelFile->setCell('A' . $row, 'Closing Balance', ['makeBold' => true]);
        $excelFile->setCell('I' . $row, \App\Essentials\FormatAmount::format($closing)->outText(), ['makeBold' => true]);

        $excelFile->output();
    }

    public function createTrialBalance($from, $to, $previousFrom, $previousTo, $params = [])
    {
        DB::update(
            '       CREATE OR REPLACE VIEW current_year AS 
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv1=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv2=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv3=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv4=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv5=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id'
        );

        DB::update(
            '       CREATE OR REPLACE VIEW previous_year AS 
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv1=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $previousFrom . '" AND "' . $previousTo . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv2=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $previousFrom . '" AND "' . $previousTo . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv3=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $previousFrom . '" AND "' . $previousTo . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv4=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $previousFrom . '" AND "' . $previousTo . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv5=L.id WHERE L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $previousFrom . '" AND "' . $previousTo . '" GROUP BY L.id'
        );

        DB::update(
            '       CREATE OR REPLACE VIEW trial_balance AS 
                    SELECT ledger_id, ledger_name, parent_id, level, is_parent, SUM(current_balance) AS current_balance, SUM(previous_balance) AS previous_balance FROM ( SELECT L.id AS ledger_id, L.name AS ledger_name, L.parent_id AS parent_id, L.level AS level, L.is_parent AS is_parent, C.balance AS current_balance, NULL AS previous_balance  FROM ledgers L LEFT JOIN current_year C ON L.id = C.id
                    UNION
                    SELECT L.id AS ledger_id, L.name AS ledger_name, L.parent_id AS parent_id, L.is_parent AS is_parent, L.level AS level, NULL AS current_balance, P.balance AS previous_balance  FROM ledgers L LEFT JOIN previous_year P ON L.id = P.id) AS A GROUP BY A.ledger_id'
        );
    }

    public function trial_balance()
    {
        return view('reports.finance.filters.tb');
    }

    public function trial_balance_list()
    {

        $from_date =  $_POST['from_date'];
        $to_date = $_POST['to_date'];

        $from = Carbon::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
        $to =  Carbon::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');

        $previousFrom = date('Y-m-d', strtotime($from . ' -1 year'));
        $previousTo = date('Y-m-d', strtotime($to . ' -1 year'));

        $this->createTrialBalance($from, $to, $previousFrom, $previousTo);

        $data['draw'] = $_POST['draw']; //Draw
        $eachItemData = [];

        //level 1
        foreach (TrialBalance::levelOne() as $eachOne) {
            $eachItemData[] = [$eachOne->ledgerName(), $eachOne->currentDebit(), $eachOne->currentCredit(), $eachOne->ledgerName(), $eachOne->previousDebit(), $eachOne->previousCredit()];
            //Level 2
            foreach (TrialBalance::levelTwo($eachOne->ledger_id) as $eachTwo) {
                $eachItemData[] = [$eachTwo->ledgerName(), $eachTwo->currentDebit(), $eachTwo->currentCredit(), $eachTwo->ledgerName(), $eachTwo->previousDebit(), $eachTwo->previousCredit()];
                //Level 3
                foreach (TrialBalance::levelThree($eachTwo->ledger_id) as $eachThree) {
                    $eachItemData[] = [$eachThree->ledgerName(), $eachThree->currentDebit(), $eachThree->currentCredit(), $eachThree->ledgerName(), $eachThree->previousDebit(), $eachThree->previousCredit()];
                    //Level 4
                    foreach (TrialBalance::levelFour($eachThree->ledger_id) as $eachFour) {
                        $eachItemData[] = [$eachFour->ledgerName(), $eachFour->currentDebit(), $eachFour->currentCredit(), $eachFour->ledgerName(), $eachFour->previousDebit(), $eachFour->previousCredit()];
                        //level 5
                        foreach (TrialBalance::levelFive($eachFour->ledger_id) as $eachFive) {
                            $eachItemData[] = [$eachFive->ledgerName(), $eachFive->currentDebit(), $eachFive->currentCredit(), $eachFive->ledgerName(), $eachFive->previousDebit(), $eachFive->previousCredit()];
                        }
                    }
                }
            }
        }

        $data['data'] = $eachItemData;
        $data['current_year'] = 'Current Year (' . $from_date . ' - ' . $to_date . ')';
        $data['previous_year'] = 'Previous Year (' . date('d/m/Y', strtotime($previousFrom)) . ' - ' .  date('d/m/Y', strtotime($previousTo)) . ')';
        $data['current_debit'] = Money::AED(TrialBalance::currentDebitSum(), true)->format();
        $data['current_credit'] = Money::AED(abs(TrialBalance::currentCreditSum()), true)->format();
        $data['previous_debit'] =  Money::AED(TrialBalance::previousDebitSum(), true)->format();
        $data['previous_credit'] = Money::AED(abs(TrialBalance::previousCreditSum()), true)->format();
        return response()->json($data);
    }

    public function trial_balance_excel()
    {
        $from_date =  Input::get('from');
        $to_date = Input::get('to');

        $from = Carbon::createFromFormat('d/m/Y', $from_date)->format('Y-m-d');
        $to =  Carbon::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');

        $previousFrom = date('Y-m-d', strtotime($from . ' -1 year'));
        $previousTo = date('Y-m-d', strtotime($to . ' -1 year'));

        $this->createTrialBalance($from, $to, $previousFrom, $previousTo);

        $excelFile = new \App\Essentials\ExcelBuilder('trial_balance');
        $excelFile->setWorkSheetTitle('Trial balance');
        $excelFile->mergeCenterCells('A1', 'F1');
        $excelFile->setCell('A1', 'Trial balance', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->mergeCenterCells('A2', 'C2');
        $excelFile->setCell('A2', 'Current Year (' . $from_date . ' - ' . $to_date . ')', ['makeBold' => true, 'fontSize' => 14]);

        $excelFile->mergeCenterCells('D2', 'F2');
        $excelFile->setCell('D2', 'Previous Year (' . date('d/m/Y', strtotime($previousFrom)) . ' - ' .  date('d/m/Y', strtotime($previousTo)) . ')', ['makeBold' => true, 'fontSize' => 14]);

        $row = 3;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Ledger', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Debit', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Credit', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Ledger', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Debit', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Credit', ['makeBold' => true, 'autoWidthIndex' => 5]],
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'C' . $row, 'A9DEFB');
        $excelFile->setBackgroundColorRange('D' . $row, 'F' . $row, 'CAF9BE');

        //level 1
        foreach (TrialBalance::levelOne() as $eachOne) {
            $row++;
            $excelFile->setCellMultiple([
                ['A' . $row, $eachOne->ledgerNameExcel(), ['makeBold' => $eachOne->isParent()]],
                ['B' . $row, $eachOne->currentDebit()],
                ['C' . $row, $eachOne->currentCredit()],
                ['D' . $row, $eachOne->ledgerNameExcel(), ['makeBold' => $eachOne->isParent()]],
                ['E' . $row, $eachOne->previousDebit()],
                ['F' . $row, $eachOne->previousCredit()]
            ]);
            //Level 2
            foreach (TrialBalance::levelTwo($eachOne->ledger_id) as $eachTwo) {
                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachTwo->ledgerNameExcel(), ['makeBold' => $eachTwo->isParent()]],
                    ['B' . $row, $eachTwo->currentDebit()],
                    ['C' . $row, $eachTwo->currentCredit()],
                    ['D' . $row, $eachTwo->ledgerNameExcel(), ['makeBold' => $eachTwo->isParent()]],
                    ['E' . $row, $eachTwo->previousDebit()],
                    ['F' . $row, $eachTwo->previousCredit()]
                ]);
                //Level 3
                foreach (TrialBalance::levelThree($eachTwo->ledger_id) as $eachThree) {
                    $row++;
                    $excelFile->setCellMultiple([
                        ['A' . $row, $eachThree->ledgerNameExcel(), ['makeBold' => $eachThree->isParent()]],
                        ['B' . $row, $eachThree->currentDebit()],
                        ['C' . $row, $eachThree->currentCredit()],
                        ['D' . $row, $eachThree->ledgerNameExcel(), ['makeBold' => $eachThree->isParent()]],
                        ['E' . $row, $eachThree->previousDebit()],
                        ['F' . $row, $eachThree->previousCredit()]
                    ]);
                    //Level 4
                    foreach (TrialBalance::levelFour($eachThree->ledger_id) as $eachFour) {
                        $row++;
                        $excelFile->setCellMultiple([
                            ['A' . $row, $eachFour->ledgerNameExcel(), ['makeBold' => $eachFour->isParent()]],
                            ['B' . $row, $eachFour->currentDebit()],
                            ['C' . $row, $eachFour->currentCredit()],
                            ['D' . $row, $eachFour->ledgerNameExcel(), ['makeBold' => $eachFour->isParent()]],
                            ['E' . $row, $eachFour->previousDebit()],
                            ['F' . $row, $eachFour->previousCredit()]
                        ]);
                        //level 5
                        foreach (TrialBalance::levelFive($eachFour->ledger_id) as $eachFive) {
                            $row++;
                            $excelFile->setCellMultiple([
                                ['A' . $row, $eachFive->ledgerNameExcel(), ['makeBold' => $eachFive->isParent()]],
                                ['B' . $row, $eachFive->currentDebit()],
                                ['C' . $row, $eachFive->currentCredit()],
                                ['D' . $row, $eachFive->ledgerNameExcel(), ['makeBold' => $eachFive->isParent()]],
                                ['E' . $row, $eachFive->previousDebit()],
                                ['F' . $row, $eachFive->previousCredit()]
                            ]);
                        }
                    }
                }
            }
        }

        $row++;
        $excelFile->setCell('A' . $row, 'Total', ['makeBold' => true]);
        $excelFile->setCell('B' . $row, Money::AED(TrialBalance::currentDebitSum(), true)->format(), ['makeBold' => true]);
        $excelFile->setCell('C' . $row, Money::AED(abs(TrialBalance::currentCreditSum()), true)->format(), ['makeBold' => true]);

        $excelFile->setCell('D' . $row, 'Total', ['makeBold' => true]);
        $excelFile->setCell('E' . $row, Money::AED(TrialBalance::previousDebitSum(), true)->format(), ['makeBold' => true]);
        $excelFile->setCell('F' . $row, Money::AED(abs(TrialBalance::previousCreditSum()), true)->format(), ['makeBold' => true]);

        $excelFile->output();
    }

    public function balance_sheet()
    {
        return view('reports.finance.filters.bs');
    }

    public function createBalanceSheetAsset($to = NULL, $from = '2010-01-01')
    {
        DB::update(
            '       CREATE OR REPLACE VIEW asset AS 
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv1=L.id WHERE L.type="A" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv2=L.id WHERE L.type="A" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv3=L.id WHERE L.type="A" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv4=L.id WHERE L.type="A" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv5=L.id WHERE L.type="A" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id'
        );

        DB::update(
            '   CREATE OR REPLACE VIEW balance_sheet_asset AS SELECT L.id AS ledger_id, L.name AS ledger_name, L.parent_id AS parent_id, L.level AS level, L.type, L.is_parent AS is_parent, A.balance AS balance FROM ledgers L LEFT JOIN asset A ON L.id = A.id WHERE L.type="A"'
        );
    }

    public function createBalanceSheetLiability($to = NULL, $from = '2010-01-01')
    {


        $this->tempVoucher = BalanceSheetLiabilities::currentProfitEntry($to);

        DB::update(
            '       CREATE OR REPLACE VIEW liability AS 
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv1=L.id WHERE L.type="L" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv2=L.id WHERE L.type="L" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv3=L.id WHERE L.type="L" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv4=L.id WHERE L.type="L" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id
                    UNION ALL
                    SELECT L.id, L.name, L.level, SUM(E.amount) AS balance FROM entries E LEFT JOIN ledgers L ON E.lv5=L.id WHERE L.type="L" AND L.id IS NOT NULL AND E.is_posted=1 AND E.date BETWEEN "' . $from . '" AND "' . $to . '" GROUP BY L.id'
        );

        DB::update(
            '   CREATE OR REPLACE VIEW balance_sheet_liability AS SELECT L.id AS ledger_id, L.name AS ledger_name, L.parent_id AS parent_id, L.level AS level, L.type, L.is_parent AS is_parent, LI.balance AS balance FROM ledgers L LEFT JOIN liability LI ON L.id = LI.id WHERE L.type="L"'
        );
    }

    public function balance_sheet_asset_list()
    {
        $to_date =  Carbon::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');
        $this->createBalanceSheetAsset($to_date);

        $data['draw'] = $_POST['draw']; //Draw
        $eachItemData = [];

        //level 1
        foreach (BalanceSheetAssets::LevelOne() as $eachOne) {
            $eachItemData[] = [$eachOne->ledgerName(), $eachOne->BalanceInBase()];
            //Level 2
            foreach (BalanceSheetAssets::LevelTwo($eachOne->ledger_id) as $eachTwo) {
                $eachItemData[] = [$eachTwo->ledgerName(), $eachTwo->BalanceInBase()];
                //Level 3
                foreach (BalanceSheetAssets::LevelThree($eachTwo->ledger_id) as $eachThree) {
                    $eachItemData[] = [$eachThree->ledgerName(), $eachThree->BalanceInBase()];
                    //Level 4
                    foreach (BalanceSheetAssets::LevelFour($eachThree->ledger_id) as $eachFour) {
                        $eachItemData[] = [$eachFour->ledgerName(), $eachFour->BalanceInBase()];
                        //level 5
                        foreach (BalanceSheetAssets::LevelFive($eachFour->ledger_id) as $eachFive) {
                            $eachItemData[] = [$eachFive->ledgerName(), $eachFive->BalanceInBase()];
                        }
                    }
                }
            }
        }

        $data['data'] = $eachItemData;
        $data['asset_total'] = Money::AED(BalanceSheetAssets::sum(), true)->format();
        return response()->json($data);
    }

    public function balance_sheet_liability_list()
    {
        $to_date =  Carbon::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');
        $this->createBalanceSheetLiability($to_date);

        $data['draw'] = $_POST['draw']; //Draw
        $eachItemData = [];

        //level 1
        foreach (BalanceSheetLiabilities::LevelOne() as $eachOne) {
            $eachItemData[] = [$eachOne->ledgerName(), $eachOne->BalanceInBase()];
            //Level 2
            foreach (BalanceSheetLiabilities::LevelTwo($eachOne->ledger_id) as $eachTwo) {
                $eachItemData[] = [$eachTwo->ledgerName(), $eachTwo->BalanceInBase()];
                //Level 3
                foreach (BalanceSheetLiabilities::LevelThree($eachTwo->ledger_id) as $eachThree) {
                    $eachItemData[] = [$eachThree->ledgerName(), $eachThree->BalanceInBase()];
                    //Level 4
                    foreach (BalanceSheetLiabilities::LevelFour($eachThree->ledger_id) as $eachFour) {
                        $eachItemData[] = [$eachFour->ledgerName(), $eachFour->BalanceInBase()];
                        //level 5
                        foreach (BalanceSheetLiabilities::LevelFive($eachFour->ledger_id) as $eachFive) {
                            $eachItemData[] = [$eachFive->ledgerName(), $eachFive->BalanceInBase()];
                        }
                    }
                }
            }
        }

        $data['data'] = $eachItemData;
        $data['liability_total'] = Money::AED(BalanceSheetLiabilities::sum(), true)->format();
        $this->deleteTemporaryVoucher();
        return response()->json($data);
    }

    public function balance_sheet_excel()
    {
        $to_date = Input::get('to');
        $to =  Carbon::createFromFormat('d/m/Y', $to_date)->format('Y-m-d');

        $this->createBalanceSheetAsset($to);
        $this->createBalanceSheetLiability($to);

        $excelFile = new \App\Essentials\ExcelBuilder('balance_sheet');
        $excelFile->setWorkSheetTitle('Balance Sheet');
        $excelFile->mergeCenterCells('A1', 'D1');
        $excelFile->setCell('A1', 'Balance Sheet (' . $to_date . ')', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->mergeCenterCells('A2', 'B2');
        $excelFile->setCell('A2', 'Total Assets', ['makeBold' => true, 'fontSize' => 14]);

        $excelFile->mergeCenterCells('C2', 'D2');
        $excelFile->setCell('C2', 'Total Equity & Liabilities', ['makeBold' => true, 'fontSize' => 14]);

        $row = 3;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Ledger', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Amount', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Ledger', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Amount', ['makeBold' => true, 'autoWidthIndex' => 3]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'B' . $row, 'A9DEFB');
        $excelFile->setBackgroundColorRange('C' . $row, 'D' . $row, 'CAF9BE');

        //level 1
        foreach (BalanceSheetAssets::LevelOne() as $eachOne) {
            $row++;
            $excelFile->setCellMultiple([
                ['A' . $row, $eachOne->ledgerNameExcel(), ['makeBold' => $eachOne->isParent()]],
                ['B' . $row, $eachOne->BalanceInBase()],
            ]);
            //Level 2
            foreach (BalanceSheetAssets::LevelTwo($eachOne->ledger_id) as $eachTwo) {
                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachTwo->ledgerNameExcel(), ['makeBold' => $eachTwo->isParent()]],
                    ['B' . $row, $eachTwo->BalanceInBase()],
                ]);
                //Level 3
                foreach (BalanceSheetAssets::LevelThree($eachTwo->ledger_id) as $eachThree) {
                    $row++;
                    $excelFile->setCellMultiple([
                        ['A' . $row, $eachThree->ledgerNameExcel(), ['makeBold' => $eachThree->isParent()]],
                        ['B' . $row, $eachThree->BalanceInBase()],
                    ]);
                    //Level 4
                    foreach (BalanceSheetAssets::LevelFour($eachThree->ledger_id) as $eachFour) {
                        $row++;
                        $excelFile->setCellMultiple([
                            ['A' . $row, $eachFour->ledgerNameExcel(), ['makeBold' => $eachFour->isParent()]],
                            ['B' . $row, $eachFour->BalanceInBase()],
                        ]);
                        //level 5
                        foreach (BalanceSheetAssets::LevelFive($eachFour->ledger_id) as $eachFive) {
                            $row++;
                            $excelFile->setCellMultiple([
                                ['A' . $row, $eachFive->ledgerNameExcel(), ['makeBold' => $eachFive->isParent()]],
                                ['B' . $row, $eachFive->BalanceInBase()],
                            ]);
                        }
                    }
                }
            }
        }

        $row++;
        $excelFile->setCell('A' . $row, 'Total', ['makeBold' => true]);
        $excelFile->setCell('B' . $row, Money::AED(BalanceSheetAssets::sum(), true)->format(), ['makeBold' => true]);

        $row = 3;
        //level 1
        foreach (BalanceSheetLiabilities::LevelOne() as $eachOne) {
            $row++;
            $excelFile->setCellMultiple([
                ['C' . $row, $eachOne->ledgerNameExcel(), ['makeBold' => $eachOne->isParent()]],
                ['D' . $row, $eachOne->BalanceInBase()],
            ]);
            //Level 2
            foreach (BalanceSheetLiabilities::LevelTwo($eachOne->ledger_id) as $eachTwo) {
                $row++;
                $excelFile->setCellMultiple([
                    ['C' . $row, $eachTwo->ledgerNameExcel(), ['makeBold' => $eachTwo->isParent()]],
                    ['D' . $row, $eachTwo->BalanceInBase()],
                ]);
                //Level 3
                foreach (BalanceSheetLiabilities::LevelThree($eachTwo->ledger_id) as $eachThree) {
                    $row++;
                    $excelFile->setCellMultiple([
                        ['C' . $row, $eachThree->ledgerNameExcel(), ['makeBold' => $eachThree->isParent()]],
                        ['D' . $row, $eachThree->BalanceInBase()],
                    ]);
                    //Level 4
                    foreach (BalanceSheetLiabilities::LevelFour($eachThree->ledger_id) as $eachFour) {
                        $row++;
                        $excelFile->setCellMultiple([
                            ['C' . $row, $eachFour->ledgerNameExcel(), ['makeBold' => $eachFour->isParent()]],
                            ['D' . $row, $eachFour->BalanceInBase()],
                        ]);
                        //level 5
                        foreach (BalanceSheetLiabilities::LevelFive($eachFour->ledger_id) as $eachFive) {
                            $row++;
                            $excelFile->setCellMultiple([
                                ['C' . $row, $eachFive->ledgerNameExcel(), ['makeBold' => $eachFive->isParent()]],
                                ['D' . $row, $eachFive->BalanceInBase()],
                            ]);
                        }
                    }
                }
            }
        }

        $row++;
        $excelFile->setCell('C' . $row, 'Total', ['makeBold' => true]);
        $excelFile->setCell('D' . $row, Money::AED(BalanceSheetLiabilities::sum(), true)->format(), ['makeBold' => true]);

        $this->deleteTemporaryVoucher();

        $excelFile->output();
    }

    public function deleteTemporaryVoucher()
    {
        if ($this->tempVoucher > 0) {
            Head::find($this->tempVoucher)->delete();
            Entries::where('head_id', $this->tempVoucher)->delete();
        }
        return;
    }

    public function tax()
    {
        return view('reports.finance.filters.tax');
    }

    public function tax_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $from_date = Carbon::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');

        $query = Entries::query()
            ->leftJoin('buildings', 'entries.building_id', 'buildings.id')
            ->leftJoin('tenants', 'tenants.id', 'entries.tenant_id')
            ->leftJoin('flats', 'flats.id', 'entries.flat_id')
            ->whereBetween('entries.date', [$from_date, $to_date])
            ->where('entries.is_posted', 1)
            ->where('entries.ledger_id', Ledgers::findClass(Ledgers::SALES_VAT)->id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('flats.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('buildings.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('entries.contract_id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('entries.date', 'entries.head_id', 'entries.amount', 'entries.ledger_id')
            ->skip($offset)
            ->take($limit)
            ->orderBy('entries.date', 'ASC')
            ->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = [];

        foreach ($result as $i => $eachItem) {

            $eachItemData[] = [
                ($i + $offset + 1),
                $eachItem->head->entry_type->name,
                $eachItem->head->formated_date(),
                $eachItem->head->number,
                $eachItem->head->contract_id,
                $eachItem->head->tenant->name,
                $eachItem->head->building->name,
                $eachItem->head->flat->name,
                \App\Essentials\FormatAmount::format($eachItem->amount, $eachItem->ledger_id)->onBase()

            ];
        }

        $data['data'] = $eachItemData;
        $taxOnSales = Entries::salesTax($from_date, $to_date);
        $data['tax_sales'] = number_format(round($taxOnSales, 2),  2, '.', ',');
        $taxOnPurchase = Entries::purchaseTax($from_date, $to_date);
        $data['tax_purchase'] = number_format(round($taxOnPurchase, 2),  2, '.', ',');
        $taxOnExpense =  Entries::expenseTax($from_date, $to_date);
        $data['tax_expense'] = number_format(round($taxOnExpense, 2),  2, '.', ',');
        $taxPayable = $taxOnSales - $taxOnPurchase - $taxOnExpense;
        $data['tax_payable'] =  number_format(round($taxPayable, 2),  2, '.', ',');

        return response()->json($data);
    }

    public function tax_excel()
    {
        $from_date = Carbon::createFromFormat('d/m/Y', Input::get('from'))->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', Input::get('to'))->format('Y-m-d');

        $query = Entries::query()
            ->leftJoin('buildings', 'entries.building_id', 'buildings.id')
            ->leftJoin('tenants', 'tenants.id', 'entries.tenant_id')
            ->leftJoin('flats', 'flats.id', 'entries.flat_id')
            ->whereBetween('entries.date', [$from_date, $to_date])
            ->where('entries.is_posted', 1)
            ->where('entries.ledger_id', Ledgers::findClass(Ledgers::SALES_VAT)->id);

        $result = $query
            ->select('entries.date', 'entries.head_id', 'entries.amount', 'entries.ledger_id')
            ->orderBy('entries.date', 'ASC')
            ->get();

        $excelFile = new \App\Essentials\ExcelBuilder('tax_report');
        $excelFile->setWorkSheetTitle('Tax Payable');
        $excelFile->mergeCenterCells('A1', 'I1');
        $excelFile->setCell('A1', 'Tax Report', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('A2', 'From', ['makeBold' => true]);
        $excelFile->setCell('B2', $from_date);
        $excelFile->setCell('A3', 'To', ['makeBold' => true]);
        $excelFile->setCell('B3', $to_date);

        $row = 5;
        $excelFile->setCellMultiple([
            ['A' . $row, '#', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Type', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Date', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Number', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Contract #', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Building', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Flat', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Tax Amount', ['makeBold' => true, 'autoWidthIndex' => 8]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'I' . $row, 'A9DEFB');

        foreach ($result as $i => $eachItem) {
            $row++;
            $excelFile->setCellMultiple([
                ['A' . $row, ($i + 1)],
                ['B' . $row, $eachItem->head->entry_type->name],
                ['C' . $row, $eachItem->head->formated_date()],
                ['D' . $row, $eachItem->head->number],
                ['E' . $row, $eachItem->head->contract_id],
                ['F' . $row, $eachItem->head->tenant->name],
                ['G' . $row, $eachItem->head->building->name],
                ['H' . $row, $eachItem->head->flat->name],
                ['I' . $row, \App\Essentials\FormatAmount::format($eachItem->amount, $eachItem->ledger_id)->onBase()]

            ]);
        }

        $taxOnSales = Entries::salesTax($from_date, $to_date);
        $taxOnPurchase = Entries::purchaseTax($from_date, $to_date);
        $taxOnExpense =  Entries::expenseTax($from_date, $to_date);
        $taxPayable = $taxOnSales - $taxOnPurchase - $taxOnExpense;

        $row++;
        $excelFile->mergeRightCells('A' . $row, 'H' . $row);
        $excelFile->setCell('A' . $row, 'Tax on Sales', ['makeBold' => true]);
        $excelFile->setCell('I' . $row, number_format(round($taxOnSales, 2),  2, '.', ','), ['makeBold' => true]);

        $row++;
        $excelFile->mergeRightCells('A' . $row, 'H' . $row);
        $excelFile->setCell('A' . $row, 'Tax on Purchase', ['makeBold' => true]);
        $excelFile->setCell('I' . $row, number_format(round($taxOnPurchase, 2),  2, '.', ','), ['makeBold' => true]);

        $row++;
        $excelFile->mergeRightCells('A' . $row, 'H' . $row);
        $excelFile->setCell('A' . $row, 'Tax on Expense', ['makeBold' => true]);
        $excelFile->setCell('I' . $row, number_format(round($taxOnExpense, 2),  2, '.', ','), ['makeBold' => true]);

        $row++;
        $excelFile->mergeRightCells('A' . $row, 'H' . $row);
        $excelFile->setCell('A' . $row, 'Tax on Expense', ['makeBold' => true]);
        $excelFile->setCell('I' . $row, number_format(round($taxPayable, 2),  2, '.', ','), ['makeBold' => true]);

        $excelFile->output();
    }

    public function cheque()
    {
        return view('reports.finance.filters.cheque');
    }

    public function cheque_list()
    {
        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'finance.number',
            1 => 'finance.cheque_date',
            2 => 'finance.cheque_no',
            3 => 'finance.contract_id',
            4 => 'tenants.name',
            5 => 'finance.narration',
            6 => 'finance.id',
            7 => 'finance.cheque_status'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $type = (int) $_POST['type'];
        $status = (int) $_POST['status'];

        $from_date = Carbon::createFromFormat('d/m/Y', $_POST['from_date'])->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', $_POST['to_date'])->format('Y-m-d');

        $query = Head::query()
            ->leftJoin('tenants', 'tenants.id', 'finance.tenant_id')
            ->whereBetween('finance.cheque_date', [$from_date, $to_date])
            ->where('finance.type', $type)
            ->where('finance.method', 2);

        if ($status > 0) {
            $query->where('finance.cheque_status', $status);
        } else {
            $query->where(function ($q) use ($status) {
                $q->where('finance.is_posted', 1)
                    ->orWhere('finance.cheque_status', 2);
            });
        }

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('finance.cheque_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('finance.id', 'finance.number', 'finance.cheque_no', 'finance.cheque_date', 'finance.tenant_id', 'finance.narration', 'finance.cheque_status', 'finance.method', 'finance.contract_id')
            ->skip($offset)
            ->take($limit)
            ->orderBy($filterColumn, $filterOrder)
            ->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as  $eachItem) {

            $eachItemData[] = [
                $eachItem->number,
                $eachItem->formated_cheque_date(),
                $eachItem->cheque_no,
                $eachItem->contract_id,
                $eachItem->tenant->name,
                $eachItem->narration,
                $eachItem->debitSum(true),
                $eachItem->chequeStatus(),
            ];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function cheque_export()
    {
        $from_date = Carbon::createFromFormat('d/m/Y', Input::get('from'))->format('Y-m-d');
        $to_date = Carbon::createFromFormat('d/m/Y', Input::get('to'))->format('Y-m-d');
        $type = (int) Input::get('type');
        $status = (int) Input::get('status');

        $query = Head::query()
            ->leftJoin('tenants', 'tenants.id', 'finance.tenant_id')
            ->whereBetween('finance.cheque_date', [$from_date, $to_date])
            ->where('finance.type', $type)
            ->where('finance.method', 2);

        if ($status > 0) {
            $query->where('finance.cheque_status', $status);
        } else {
            $query->where(function ($q) use ($status) {
                $q->where('finance.is_posted', 1)
                    ->orWhere('finance.cheque_status', 2);
            });
        }

        $result = $query
            ->select('finance.id', 'finance.number', 'finance.cheque_no', 'finance.cheque_date', 'finance.tenant_id', 'finance.narration', 'finance.cheque_status', 'finance.method', 'finance.contract_id')
            ->get();

        $excelFile = new \App\Essentials\ExcelBuilder('cheque_report');
        $excelFile->setWorkSheetTitle('Cheque Report');
        $excelFile->mergeCenterCells('A1', 'H1');
        $excelFile->setCell('A1', 'Cheque Report', ['makeBold' => true, 'fontSize' => 20]);
        $excelFile->setCell('A2', 'From', ['makeBold' => true]);
        $excelFile->setCell('B2', $from_date);
        $excelFile->setCell('A3', 'To', ['makeBold' => true]);
        $excelFile->setCell('B3', $to_date);
        $excelFile->setCell('A4', 'Type', ['makeBold' => true]);
        $typeArr = [0 => NULL, 1 => 'Receivable', 2 => 'Payable'];
        $typeLabel = $typeArr[$type];
        $excelFile->setCell('B4', $typeLabel);
        $excelFile->setCell('A5', 'Status', ['makeBold' => true]);
        $statusArr =[ 0 => 'All', 1 => 'Cleared', 2 => 'Returned' ];
        $statusLabel = $statusArr[$status];
        $excelFile->setCell('B5', $statusLabel);

        $row = 7;
        $excelFile->setCellMultiple([
            ['A' . $row, '#', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Cheque Date', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Cheque No', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Contract #', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Narration', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Amount', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Status', ['makeBold' => true, 'autoWidthIndex' => 7]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'H' . $row, 'A9DEFB');

        foreach ($result as $eachItem) {
            $row++;
            $excelFile->setCellMultiple([
                ['A' . $row, $eachItem->number],
                ['B' . $row, $eachItem->formated_cheque_date()],
                ['C' . $row, $eachItem->cheque_no],
                ['D' . $row, $eachItem->contract_id],
                ['E' . $row, $eachItem->tenant->name],
                ['F' . $row, $eachItem->narration],
                ['G' . $row, $eachItem->debitSum(true)],
                ['H' . $row, $eachItem->chequeStatus()],
            ]);
        }

        $excelFile->output();
    }
}
