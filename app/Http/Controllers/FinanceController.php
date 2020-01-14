<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use App\models\Head;
use App\models\Entries;

use Collective\Html\FormFacade as Form;

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
        $contract_id = (int) $_POST['contract'];

        $query = Head::query()->leftJoin('tenants', 'tenants.id', 'finance.tenant_id')->where('finance.type', $type);

        if ($contract_id > 0) {
            $query->where('finance.contract_id', $contract_id);
        }

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.cheque_no', 'LIKE', '%' . $keyword . '%');
            });
        }

        $count = $query->count();
        $result = $query
            ->select('finance.type', 'finance.cheque_status', 'finance.number', 'finance.date', 'finance.contract_id', 'finance.cheque_no', 'finance.cheque_date', 'tenants.name', 'finance.id', 'finance.is_posted', 'finance.is_cancelled')
            ->skip($offset)
            ->take($limit)
            ->orderBy($filterColumn, $filterOrder)
            ->get();

        $recordsTotal = $count;
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
            $cancelledOrReturned = $eachItem->is_cancelled;
            if ($eachItem->cheque_status == 2)
                $cancelledOrReturned = 100; ///////////////////////// Override |||| Cheque returns
            if ($eachItem->cheque_status == 1)
                $cancelledOrReturned = 99; ///////////////////////// Override |||| Cheque Cleared
            $actions = '';
            if (!$eachItem->isCancelled() && $contract_id == 0) {
                $actions .= '<a title="Edit" href="' . $routes[$type] . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
                if ($eachItem->isPosted()) {
                    $actions .= '<a title="Un-Post" href="#" onclick="updateStatus(' . $eachItem->id . ', 0, 0);"><i class="material-icons" >thumb_down</i></a>';
                    if ($eachItem->type == 1 && $eachItem->contract_id > 0 )
                        $actions .= ' <a title="Export Invoice" href="#" onclick="window.open(\'/finance/export/invoice/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >receipt</i></a>';
                } else
                    $actions .= '<a title="Post" href="#" onclick="updateStatus(' . $eachItem->id . ', 1, 0);"><i class="material-icons" >thumb_up</i></a>';
                $actions .= '<a title="Cancel" href="#" onclick="updateStatus(' . $eachItem->id . ', 1, 1);"><i class="material-icons" >block</i></a>';
            }
            $actions .= ' <a title="Export" href="#" onclick="window.open(\'/finance/export/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >picture_as_pdf</i></a>';
            if ($type == 1) {
                $eachItemData[] = [$eachItem->number, $eachItem->formated_date(),  $eachItem->contract_id, $eachItem->cheque_no, $eachItem->formated_cheque_date(), $eachItem->name,  $eachItem->debitSum(true), '<div class="text-center">' . $actions . '</div>', $eachItem->is_posted, $cancelledOrReturned];
            } else if ($type == 2) {
                $eachItemData[] = [$eachItem->number, $eachItem->formated_date(), $eachItem->cheque_no, $eachItem->formated_cheque_date(), $eachItem->debitSum(true), '<div class="text-center">' . $actions . '</div>', $eachItem->is_posted, $cancelledOrReturned];
            } else {
                $eachItemData[] = [$eachItem->number, $eachItem->formated_date(),  $eachItem->entries()->where('amount', '>', 0)->first()->ledger->name,  $eachItem->debitSum(true), '<div class="text-center">' . $actions . '</div>', $eachItem->is_posted, $cancelledOrReturned];
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
            $model->resetCheque();

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
            $model->resetCheque();

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

    public function update_status(Request $request)
    {
        $data = $request->all();
        if ($data['_ref'] > 0) {
            if ($data['type'] == 0)
                $data['status'] == 0 ? Head::find($data['_ref'])->unPost() : Head::find($data['_ref'])->post();
            else
                Head::find($data['_ref'])->cancel();
        }
        return response()->json(['message' => 'success']);
    }

    public function cheque_management()
    {
        return view('finance.cheque_management');
    }

    public function cheque_list()
    {
        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'number',
            1 => 'date',
            2 => 'cheque_date',
            3 => 'cheque_no',
            4 => 'number',
            5 => 'narration',
            6 => 'id',
            7 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $type = (int) $_POST['type'];
        $history = (int) $_POST['history'];

        $query = Head::query();

        if ($history == 0) {
            $query->where('type', $type)
                ->where('method', 2)
                ->where('is_posted', 1)
                ->where('cheque_status', 0);
        } else {
            $query->where('cheque_status', 2);
        }


        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('cheque_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('number', 'LIKE', '%' . $keyword . '%');
            });
        }

        $count = $query->count();
        $result = $query
            ->skip($offset)
            ->take($limit)
            ->orderBy($filterColumn, $filterOrder)
            ->get();

        $recordsTotal = $count;
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as $eachItem) {
            $havingLedger = $eachItem->type == 1 ? $eachItem->entries()->where('amount', '>', '0')->first() : $eachItem->entries()->where('amount', '<', '0')->first();

            if ($history == 0) {

                $actions = '<a title="Clear Cheque" href="#" onclick="updateCheque(' . $eachItem->id . ', true, ' . $type . ');"><i class="material-icons" >check</i></a>';
                $actions .= ' <a title="Return Cheque" href="#" onclick="updateCheque(' . $eachItem->id . ', false, ' . $type . ');"><i class="material-icons" >block</i></a>';

                $eachItemData[] = [
                    $eachItem->number,
                    $eachItem->formated_date(),
                    '<input type="text" class="form-control datepicker" value="' . $eachItem->formated_cheque_date() . '" id="Cheque_date_' . $eachItem->id . '" />',
                    '<input type="text" class="form-control" value="' . $eachItem->cheque_no . '" id="Cheque_no_' . $eachItem->id . '" />',
                    '' . Form::select('Bank_' . $eachItem->id, \App\models\Ledgers::childrenHaveClass($havingLedger->ledger_id,  \App\models\Ledgers::BANK_CHILD), $havingLedger->ledger_id, ['class' => 'form-control show-tick', 'id' => 'Bank_' . $eachItem->id]) . '',
                    $eachItem->narration,
                    $eachItem->debitSum(true),
                    '<div class="text-center">' . $actions . '</div>'
                ];
            } else {

                $actions = ' <a title="Revert Cheque Return" href="#" onclick="revertCheque(' . $eachItem->id . ');"><i class="material-icons" >sync_problem</i></a>';

                $eachItemData[] = [
                    $eachItem->entry_type->name,
                    $eachItem->number,
                    $eachItem->formated_date(),
                    $eachItem->formated_cheque_date(),
                    $eachItem->cheque_no,
                    $havingLedger->ledger->name,
                    $eachItem->narration,
                    $eachItem->debitSum(true),
                    '<div class="text-center">' . $actions . '</div>'
                ];
            }
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function update_cheques(Request $request)
    {
        $data = $request->all();
        if ($data['id'] > 0) {
            $model = Head::find($data['id']);
            $model->cheque_date = date('Y-m-d', strtotime(str_replace('/', '-', $data['cheque_date'])));
            if (trim($data['cheque_no']) != null)
                $model->cheque_no = $data['cheque_no'];
            $model->updateEntryByCode($data['type'] == 1 ? 'R-DB' : 'P-CR', $data['bank']);
            $model->updateChequeDates();
            $data['operation'] == 'true' ? $model->clearCheque() : $model->returnCheque();
        }
        return response()->json(['message' => 'success']);
    }

    public function revert_cheques(Request $request)
    {
        $data = $request->all();
        if ($data['id'] > 0) {
            $model = Head::find($data['id']);
            $model->resetCheque();
        }
        return response()->json(['message' => 'success']);
    }

    public function export($key = 0)
    {
        $id = UriEncode::decrypt($key);
        if ($id > 0) {
            $model = Head::find($id);
            $watermarkText = NULL;
            if ($model->is_cancelled == 1)
                $watermarkText = 'Cancelled';
            if ($model->cheque_status == 2)
                $watermarkText = 'Returned';

            $pdf = \PDF::loadView('pdf.finance.' . strtolower($model->entry_type->name), ['model' => $model]);
            $pdf->setWatermarkText(['showWatermarkText' => true, 'watermarkText' => $watermarkText]);
            return $pdf->stream(strtolower($model->entry_type->name). $model->number . '.pdf');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function export_invoice($key = 0)
    {
        $id = UriEncode::decrypt($key);
        if ($id > 0) {
            $model = Head::find($id);
            return \PDF::loadView('pdf.finance.invoice', ['model' => $model])->stream('invoice.pdf');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
