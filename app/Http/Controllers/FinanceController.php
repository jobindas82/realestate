<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use App\models\Head;
use App\models\Entries;

class FinanceController extends Controller
{

    public function receipt_index()
    {
        return view('finance.receipt.index');
    }

    public function receipt_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Head;
        if ($id > 0)
            $model = Head::find($id);

        return view('finance.receipt.create', ['model' => $model]);
    }


    public function list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'finance.number',
            1 => 'finance.date',
            2 => 'finance.contract_id',
            3 => 'finance.cheque_no',
            4 => 'finance.cheque_date',
            5 => 'tenants.name',
            6 => 'finance.id',
            7 => 'finance.id',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];
        $type = (int) $_POST['type'];

        $query = Head::query()->leftJoin('tenants', 'tenants.id', 'finance.tenant_id')->where('finance.type', $type);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.cheque_no', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('finance.number', 'finance.date', 'finance.contract_id', 'finance.cheque_no', 'finance.cheque_date', 'tenants.name', 'finance.id', 'finance.is_posted', 'finance.is_cancelled')
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

        $no = $offset + 1;
        $routes = [
            1 => '/finance/receipt/create/',
            2 => '/finance/payment/create/',
            3 => '/finance/journal/create/'
        ];

        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '';
            if (!$eachItem->isCancelled()) {
                $actions .= '<a title="Edit" href="' . $routes[$type] . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
                if( $eachItem->isPosted())
                    $actions .= '<a title="Un-Post" href="#" onclick="updateStatus('. $eachItem->id .', 0, 0);"><i class="material-icons" >thumb_down</i></a>';
                else
                    $actions .= '<a title="Post" href="#" onclick="updateStatus('. $eachItem->id .', 1, 0);"><i class="material-icons" >thumb_up</i></a>';
                $actions .= '<a title="Cancel" href="#" onclick="updateStatus('. $eachItem->id .', 1, 1);"><i class="material-icons" >block</i></a>';
            }
            if ($type == 1) {
                $eachItemData[] = [$eachItem->number, $eachItem->formated_date(),  $eachItem->contract_id, $eachItem->cheque_no, $eachItem->formated_cheque_date(), $eachItem->name,  $eachItem->debitSum(true), '<div class="text-center">' . $actions . '</div>', $eachItem->is_posted, $eachItem->is_cancelled];
            } else if ($type == 2) {
                $eachItemData[] = [$eachItem->number, $eachItem->formated_date(), $eachItem->cheque_no, $eachItem->formated_cheque_date(), $eachItem->debitSum(true), '<div class="text-center">' . $actions . '</div>', $eachItem->is_posted, $eachItem->is_cancelled];
            } else {
                $eachItemData[] = [$eachItem->number, $eachItem->formated_date(),  $eachItem->entries()->where('amount', '>', 0)->first()->ledger->name,  $eachItem->debitSum(true), '<div class="text-center">' . $actions . '</div>', $eachItem->is_posted, $eachItem->is_cancelled];
            }
            $no++;
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function receipt_save(Request $request)
    {
        $data = $request->all();

        if ($data['date'] != '')
            $data['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])));
        if ($data['cheque_date'] != '')
            $data['cheque_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['cheque_date'])));

        $validator = \Validator::make($data, [
            'date' => ['required', 'date'],
            'contract_id' => ['bail', 'required', 'gt:0'],
            'method' => ['bail', 'required', 'gt:0'],
            'cash_account_id' => [new \App\Rules\cashMethod((int) $data['method'])],
            'cheque_account_id' => [new \App\Rules\chequeMethod((int) $data['method'])],
            'cheque_date' => [new \App\Rules\chequeMethodDate((int) $data['method'])],
            'bank_account_id' => [new \App\Rules\bankMethod((int) $data['method'])],
            'Entries.*.ledger_id' => ['required', 'integer', 'gt:0'],
            'Entries.*.amount' => ['required', 'gt:0', 'numeric'],
            'total_value' => ['required', 'gt:0', 'numeric']
        ], [
            'contract_id.gt' => 'Contract cannot blank.'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $dbLedger = [
                1 => $data['cash_account_id'],
                2 => $data['cheque_account_id'],
                3 => $data['bank_account_id']
            ];

            $model = new Head();
            if ($data['id'] > 0) {
                $model = Head::find($data['id']);
                Entries::where('head_id', $data['id'])->delete();
            } else {
                $model->type = 1;
                $model->createNumber();
            }
            $model->fill($data);
            $model->fillContract();

            $items = [];
            $totalAmount = 0;
            $consideringDate = $data['method'] == 2 ? $data['cheque_date'] : $data['date'];
            foreach ($data['Entries'] as $i => $eachItem) {
                $items[$i] = new Entries;
                $items[$i]->ledger_id = $eachItem['ledger_id'];
                $items[$i]->amount = -1 * $eachItem['amount'];
                $items[$i]->date = $consideringDate;
                $totalAmount += $eachItem['amount'];
            }

            $db = new Entries;
            $db->ledger_id = $dbLedger[$model->method];
            $db->amount = $totalAmount;
            $db->date = $consideringDate;
            $db->code = 'R-DB';

            if ($model->save()) {
                $model->createEntries([$db], false, true); //dr
                $model->createEntries($items, false, true); //cr
                $model->update_ubl();
            }

            return response()->json(['receipt_id' => $model->id, 'message' => 'success']);
        }
    }

    public function payment_index()
    {
        return view('finance.payment.index');
    }

    public function payment_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Head;
        if ($id > 0)
            $model = Head::find($id);

        return view('finance.payment.create', ['model' => $model]);
    }

    public function payment_save(Request $request)
    {
        $data = $request->all();

        if ($data['date'] != '')
            $data['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])));
        if ($data['cheque_date'] != '')
            $data['cheque_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['cheque_date'])));

        $validator = \Validator::make($data, [
            'date' => ['required', 'date'],
            'contract_id' => ['nullable', 'gte:0'],
            'method' => ['bail', 'required', 'gt:0'],
            'cash_account_id' => [new \App\Rules\cashMethod((int) $data['method'])],
            'cheque_account_id' => [new \App\Rules\chequeMethod((int) $data['method'])],
            'cheque_date' => [new \App\Rules\chequeMethodDate((int) $data['method'])],
            'bank_account_id' => [new \App\Rules\bankMethod((int) $data['method'])],
            'Entries.*.ledger_id' => ['required', 'integer', 'gt:0'],
            'Entries.*.amount' => ['required', 'gt:0', 'numeric'],
            'total_value' => ['required', 'gt:0', 'numeric']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $crLedger = [
                1 => $data['cash_account_id'],
                2 => $data['cheque_account_id'],
                3 => $data['bank_account_id']
            ];

            $model = new Head();
            if ($data['id'] > 0) {
                $model = Head::find($data['id']);
                Entries::where('head_id', $data['id'])->delete();
            } else {
                $model->type = 2; // Payment
                $model->createNumber();
            }

            $model->fill($data);
            $model->fillContract();

            $items = [];
            $totalAmount = 0;
            $consideringDate = $data['method'] == 2 ? $data['cheque_date'] : $data['date'];
            foreach ($data['Entries'] as $i => $eachItem) {
                $items[$i] = new Entries;
                $items[$i]->ledger_id = $eachItem['ledger_id'];
                $items[$i]->amount = $eachItem['amount'];
                $items[$i]->date = $consideringDate;
                $totalAmount += $eachItem['amount'];
            }

            $cr = new Entries;
            $cr->ledger_id = $crLedger[$model->method];
            $cr->amount = -1 * $totalAmount;
            $cr->date = $consideringDate;
            $cr->code = 'P-CR';

            if ($model->save()) {
                $model->createEntries([$cr], false, true); //cr
                $model->createEntries($items, false, true); //dr
                $model->update_ubl();
            }

            return response()->json(['payment_id' => $model->id, 'message' => 'success']);
        }
    }

    public function journal_index()
    {
        return view('finance.journal.index');
    }

    public function journal_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Head;
        if ($id > 0)
            $model = Head::find($id);

        return view('finance.journal.create', ['model' => $model]);
    }

    public function journal_save(Request $request)
    {
        $data = $request->all();

        if ($data['date'] != '')
            $data['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])));

        $validator = \Validator::make($data, [
            'date' => ['required', 'date'],
            'Entries.*.ledger_id' => ['required', 'integer', 'gt:0'],
            'Entries.*.debit' => ['numeric', 'nullable'],
            'Entries.*.credit' => ['numeric', 'nullable']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {
            $model = new Head();
            if ($data['id'] > 0) {
                $model = Head::find($data['id']);
                Entries::where('head_id', $data['id'])->delete();
            } else {
                $model->type = 3; // Journal
                $model->createNumber();
            }

            $model->fill($data);
            foreach ($data['Entries'] as $i => $eachItem) {
                $items[$i] = new Entries;
                $items[$i]->ledger_id = $eachItem['ledger_id'];
                if ($eachItem['debit'] > 0)
                    $items[$i]->amount = (float) $eachItem['debit'];
                if ($eachItem['credit'] > 0)
                    $items[$i]->amount = -1 * $eachItem['credit'];
            }

            if ($model->save()) {
                $model->createEntries($items);
                $model->update_ubl();
            }

            return response()->json(['journal_id' => $model->id, 'message' => 'success']);
        }
    }

    public function update_status(Request $request){
        $data = $request->all();
        if( $data['_ref'] > 0 ){
            if( $data['type'] == 0 ) 
                $data['status'] == 0 ? Head::find($data['_ref'])->unPost() : Head::find($data['_ref'])->post();
            else 
                Head::find($data['_ref'])->cancel();
        }
        return response()->json(['message' => 'success']);
    }
}
