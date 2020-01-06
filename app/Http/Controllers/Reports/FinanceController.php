<?php

namespace App\Http\Controllers\Reports;

use App\models\Head;
use App\models\Entries;
use App\models\Ledgers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Carbon;

class FinanceController extends \App\Http\Controllers\Controller
{
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
        DB::select(
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

        DB::select(
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

        DB::select(
            '       CREATE OR REPLACE VIEW trial_balance AS 
                    SELECT ledger_id, ledger_name, parent_id, level, SUM(current_balance) AS current_balance, SUM(previous_balance) AS previous_balance FROM ( SELECT L.id AS ledger_id, L.name AS ledger_name, L.parent_id AS parent_id, L.level AS level, C.balance AS current_balance, NULL AS previous_balance  FROM ledgers L LEFT JOIN current_year C ON L.id = C.id
                    UNION
                    SELECT L.id AS ledger_id, L.name AS ledger_name, L.parent_id AS parent_id, L.level AS level, NULL AS current_balance, P.balance AS previous_balance  FROM ledgers L LEFT JOIN previous_year P ON L.id = P.id) AS A GROUP BY A.ledger_id'
        );
    }

    public function trial_balance()
    {
        return view('reports.finance.filters.tb');
    }

    public function trial_balance_list()
    {

        $from = '2020-01-01';
        $to = '2020-12-31';

        $previousFrom = date('Y-m-d', strtotime($from . ' -1 year'));
        $previousTo = date('Y-m-d', strtotime($to . ' -1 year'));

        $this->createTrialBalance($from, $to, $previousFrom, $previousTo);
        
        $data['draw'] = $_POST['draw']; //Draw
        $eachItemData = [];

        //Level One

    }
}
