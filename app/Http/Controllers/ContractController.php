<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Essentials\UriEncode;

use App\models\Contracts;
use App\models\ContractItems;
use App\models\Ledgers;
use Collective\Html\FormFacade as Form;

class ContractController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('contract.index');
    }

    public function create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Contracts;
        if ($id > 0)
            $model = Contracts::find($id);
        return view('contract.create', ['model' => $model, 'renewFlag' => false]);
    }

    public function renew($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Contracts;
        if ($id > 0) {
            $model = Contracts::find($id);
            $model->generated_date = date('Y-m-d');
            $model->from_date = NULL;
            $model->to_date = NULL;
        }
        return view('contract.create', ['model' => $model, 'renewFlag' => true]);
    }

    public function settlement($key = 0)
    {
        $id = UriEncode::decrypt($key);
        if ($id > 0)
            $model = Contracts::find($id);
        if (!isset($model->id) || (int) $model->id == 0)
            abort(403, 'Unauthorized action.');
        return view('contract.settlement', ['model' => $model, 'total' => 0]);
    }

    public function create_cheques($key = 0)
    {
        $contract_id = UriEncode::decrypt($key);
        if ($contract_id > 0)
            $model = Contracts::find($contract_id);

        if (isset($model->id) && $model->id > 0)
            return view('contract.create_cheques', ['model' => $model]);
        else
            abort(403, 'Unauthorized action.');
    }


    public function list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'contracts.id',
            1 => 'tenants.name',
            2 => 'buildings.name',
            3 => 'flats.name',
            4 => 'contracts.from_date',
            5 => 'contracts.to_date',
            6 => 'contracts.id',
            7 => 'contracts.is_active',
            8 => 'contracts.id',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $status = $_POST['status'];

        $query = Contracts::query()
            ->leftJoin('tenants', 'tenants.id', 'contracts.tenant_id')
            ->leftJoin('buildings', 'buildings.id', 'contracts.building_id')
            ->leftJoin('flats', 'flats.id', 'contracts.flat_id');

        if ($status != '')
            $query->where('is_active', $status);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('flats.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.premise_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('buildings.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('contracts.id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $count = $query->count();
        $result = $query
            ->select('contracts.id', 'contracts.is_active', 'tenants.name AS tenant_name', 'buildings.name AS building_name', 'flats.name AS flat_name', 'contracts.from_date', 'contracts.to_date')
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

        foreach ($result as $eachItem) {
            $actions = '';
            if ($eachItem->is_active == 1) {
                $actions .= ' <a title="Edit" href="/contract/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
                $actions .= ' <a title="Add Cheques" href="/contract/cheques/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >add_circle</i></a>';
                $actions .= ' <a title="Settlement" href="/contract/settlement/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >low_priority</i></a>';
            }
            if ($eachItem->is_active == 0) {
                $actions .= ' <a title="Renew Contract" href="/contract/renew/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >autorenew</i></a>';
            }
            $actions .= ' <a title="View Receipts" href="#" onclick="window.open(\'cheques/list/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\', \'location=yes,height=0,width=0,scrollbars=yes,status=yes\');"><i class="material-icons">euro_symbol</i></a>';
            $actions .= ' <a title="Export Contract" href="#" onclick="window.open(\'/contract/export/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >picture_as_pdf</i></a>';

            $eachItemData[] = [$eachItem->id, $eachItem->tenant_name,  $eachItem->building_name, $eachItem->flat_name, $eachItem->formated_from_date(), $eachItem->formated_to_date(),  $eachItem->grossAmount(), $eachItem->status(), '<div class="text-center">' . $actions . '</div>'];
            $no++;
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function save(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['generated_date'] != '')
            $data['generated_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['generated_date'])));
        if ($data['from_date'] != '')
            $data['from_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['from_date'])));
        if ($data['to_date'] != '')
            $data['to_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['to_date'])));

        //Validation of Request
        $validator = \Validator::make($data, [
            'tenant_id' => ['required', 'gt:0'],
            'flat_id' => ['required', new \App\Rules\flatAvailability((int) $data['id'])],
            'building_id' => ['required', 'gt:0'],
            'generated_date' => ['required', 'date'],
            'from_date' => ['required', 'date'],
            'to_date' => ['required', 'date', 'after_or_equal:from_date'],
            'ContractItems.*.ledger_id' => ['required', 'integer', 'gt:0'],
            'ContractItems.*.amount' => ['required', 'gt:0', 'numeric'],
            'ContractItems.*.tax_id' => ['required', 'integer', 'gt:0'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Contracts();
            if ($data['id'] > 0) {
                $model = Contracts::find($data['id']);
                ContractItems::where('contract_id', $data['id'])->delete();
            }
            $model->fill($data);
            if ($model->save()) {
                foreach ($data['ContractItems'] as $eachItem) {
                    $itemModel = new ContractItems();
                    $itemModel->contract_id = $model->id;
                    $itemModel->fill($eachItem);
                    $itemModel->save();
                }
            }

            //Update Status
            $model->flat->occupied();
            $model->tenant->onContract();

            return response()->json(['contract_id' => $model->id, 'message' => 'success']);
        }
    }

    public function status(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['_ref'] > 0) {
            $model = Contracts::find($data['_ref']);
            if ($model->id > 0 && $model->is_available != 2) {
                $model->is_available = $data['status'];
                $model->save();
                return response()->json(['message' => 'success'], 200);
            }
        }
        return response()->json(['message' => 'failed']);
    }

    public function export($key = 0)
    {
        $id = UriEncode::decrypt($key);
        if ($id > 0) {

            $model = Contracts::find($id);
            $pdf = \PDF::loadView('pdf.contract.contract', ['model' => $model]);
            return $pdf->stream('contract.pdf');
        } else {
            abort(403, 'Unauthorized action.');
        }
    }

    public function fetch(Request $request)
    {

        $id = $request->get('_ref');
        $name = '';
        $amount = '';
        $items = ' <tr>
                    <th><label>1</label></th>
                    <td>' . Form::select('Entries[0][ledger_id]', \App\models\Ledgers::children(0), '', ['class' => 'form-control show-tick', 'required' => true, 'id' => 'Entries_0_ledger_id']) . '</td>
                    <td>' . Form::number('Entries[0][amount]', '', ['class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_0_amount', 'min' => 1, 'max' => 999999999999999, 'step' => '.0000001', 'onKeyup' => 'calculate();', 'onBlur' => 'round_field(this.id)']) . '</td>
                    <td><a href="#" title="Remove" id="Entries_0_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                </tr>';

        if ($id > 0) {
            $model = Contracts::find($id);
            $name = $model->tenant->name;
            $amount = $model->grossAmount(false);
            $items = '';
            $j = 0;
            foreach ($model->items as $i => $eachItem) {
                $items .= ' <tr>
                                <th><label>' . ($i + 1) . '</label></th>
                                <td>' . Form::select('Entries[' . $i . '][ledger_id]', \App\models\Ledgers::children($eachItem->ledger_id), $eachItem->ledger_id, ['class' => 'form-control show-tick', 'required' => true, 'id' => 'Entries_' . $i . '_ledger_id']) . '</td>
                                <td>' . Form::number('Entries[' . $i . '][amount]', '', ['class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_' . $i . '_amount', 'min' => 1, 'max' => 999999999999999, 'step' => '.0000001', 'onKeyup' => 'calculate();', 'onBlur' => 'round_field(this.id)']) . '</td>
                                <td><a href="#" title="Remove" id="Entries_' . $i . '_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                            </tr>';
                $j = $i;
            }
            $j++;
            if ($model->items->sum('tax_amount') > 0)
                $items .= ' <tr>
                            <th><label>' . $j . '</label></th>
                            <td>' . Form::select('Entries[' . $j . '][ledger_id]', \App\models\Ledgers::children(0), Ledgers::findClass(Ledgers::SALES_VAT)->id, ['class' => 'form-control show-tick', 'required' => true, 'id' => 'Entries_' . $j . '_ledger_id']) . '</td>
                            <td>' . Form::number('Entries[' . $j . '][amount]', '', ['class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_' . $j . '_amount', 'min' => 1, 'max' => 999999999999999, 'step' => '.0000001', 'onKeyup' => 'calculate();', 'onBlur' => 'round_field(this.id)']) . '</td>
                            <td><a href="#" title="Remove" id="Entries_' . $j . '_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                        </tr>';
        }
        return response()->json(['message' => 'success', 'tenant_name' => $name, 'amount' => $amount, 'contract_items' => $items], 200);
    }

    public function save_cheques(Request $request)
    {
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'contract_id' => ['required', 'gt:0', 'integer'],
            'Entries.*.cheque_date' => ['required'],
            'Entries.*.cheque_no' => ['required', 'max:255'],
            'Entries.*.amount' => ['required', 'numeric', 'gt:0'],
            'Entries.*.bank_id' => ['required', 'gt:0', 'integer']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {
            foreach ($data['Entries'] as $each) {

                $head = new \App\models\Head();
                $head->date = date('Y-m-d', strtotime(str_replace('/', '-', $each['cheque_date'])));
                $head->type = 1;
                $head->method = 2;
                $head->contract_id = $data['contract_id'];
                $head->cheque_date = $head->date;
                $head->cheque_no = $each['cheque_no'];
                $head->narration = 'Cheque Received for Contract #' . $head->contract_id;
                $head->createNumber();
                $head->fillContract();

                if ($head->save()) {

                    $db = new \App\models\Entries;
                    $db->ledger_id = $each['bank_id'];
                    $db->amount = $each['amount'];
                    $db->date =  $head->date;
                    $db->code = 'R-DB';
                    $items[0] = $db;

                    $j = 0;
                    foreach ($head->contract->items as $i => $eachItem) {
                        $ratio = $eachItem->ratio();
                        $items[$i + 1] = new \App\models\Entries;
                        $items[$i + 1]->ledger_id = $eachItem->ledger_id;
                        $items[$i + 1]->amount = -1 * round(($each['amount'] * $ratio), 6);

                        $j = $i + 1;
                    }

                    $j++;
                    $items[$j] = new \App\models\Entries;
                    $items[$j]->ledger_id = Ledgers::findClass(Ledgers::SALES_VAT)->id;
                    $items[$j]->amount = -1 * round(($each['amount'] * $head->contract->vatRatio()), 6);

                    $head->createEntries($items, true, true);
                    $head->update_ubl();
                }
            }

            return response()->json(['message' => 'success']);
        }
    }

    public function cheques_list($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Contracts;
        if ($id > 0)
            $model = Contracts::find($id);
        return view('contract.cheques_list', ['model' => $model]);
    }

    public function save_early_settlement(Request $request)
    {
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'id' => ['bail', 'required', 'gt:0', 'integer'],
            'contract_no' => [new \App\Rules\ifClosedContract($data['id'])]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {
            $model = Contracts::find($data['id']);
            $model->closeContract();
            $model->tenant->makeAvailable();
            $model->flat->vacant();

            foreach ($data['Receipts'] as $each) {
                if ($each > 0) {
                    \App\models\Head::find($each)->returnCheque();
                }
            }
            return response()->json(['message' => 'success']);
        }
    }

    public function save_expired_settlement(Request $request)
    {
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'id' => ['bail', 'required', 'gt:0', 'integer'],
            'contract_no' => [new \App\Rules\ifClosedContract($data['id'])],
            'ContractSettlement.*.remarks' => ['required', 'nullable'],
            'ContractSettlement.*.amount' => ['required', 'nullable', 'numeric', 'gt:0'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {
            $model = Contracts::find($data['id']);
            $model->closeContract();
            $model->tenant->makeAvailable();
            $model->flat->vacant();

            $items = [];
            foreach ($data['ContractSettlement'] as $i => $each) {
                $items[$i] = new \App\models\ContractSettlement();
                $items[$i]->remarks = $each['remarks'];
                $items[$i]->amount = $each['amount'];   
            }
            $model->addSettlement($items);  
            return response()->json(['message' => 'success']);
        }
    }

    public function details(Request $request){
        $response = [];
        $contract_id = $request->get('contract_id');
        if( $contract_id > 0){
            $model = Contracts::find($contract_id);
            $response['building_name'] = $model->building->name;
            $response['flat_name'] =  $model->flat->name;
        }

        return response()->json($response);
    }

    public function pdf()
    {
        $filter = Input::get('filter');
        $from = Input::get('from');
        $to = Input::get('to');

        $table = '<div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th style="width:10%">Contract #</th>
                                <th style="width:20%">Tenant</th>
                                <th style="width:10%">Building</th>
                                <th style="width:10%">Flat</th>
                                <th style="width:15%">From</th>
                                <th style="width:15%">To</th>
                                <th style="width:15%">Gross Amt.</th>
                                <th style="width:10%">Status</th>
                            </tr>
                        </thead>
                        <tbody>';

        $statusConditionFilter = [
            0 => ['field' => 'is_active', 'value' => 0, 'alias' => 'Active'],
            1 => ['field' => 'is_active', 'value' => 1, 'alias' => 'Closed'],
        ];

        $model = Contracts::query();

        //Date Conversion
        $from = $from != '' && count(explode('/', $from)) ==3 ? date('Y-m-d', strtotime(str_replace('/', '-', $from))) : date('Y-01-01');
        $to = $to != '' && count(explode('/', $to)) ==3 ? date('Y-m-d', strtotime(str_replace('/', '-', $to))) : date('Y-12-31');

        $model->whereBetween('from_date', [$from, $to]);
        //End

        $statusCondition = array_key_exists($filter, $statusConditionFilter) ? $statusConditionFilter[$filter] : NULL;

        if ($statusCondition != NULL) {
            $model->where($statusCondition['field'], $statusCondition['value']);
        }

        $items = $model->orderBy('from_date', 'ASC')->get();

        if ($items->count() > 0) {
            foreach ($items as $i => $eachItem) {
                $table .= '  <tr>
                               <td>' . $eachItem->id . '</td>
                               <td>' . $eachItem->tenant->name . '</td>
                               <td>' . $eachItem->building->name . '</td>
                               <td>' . $eachItem->flat->name . '</td>
                               <td>' . $eachItem->formated_from_date() . '</td>
                               <td>' . $eachItem->formated_to_date() . '</td>
                               <td>' . $eachItem->grossAmount() . '</td>
                               <td>' . $eachItem->status() . '</td>
                            </tr>';
            }
        } else {
            $table .= ' <tr>
                            <td colspan="8">No data</th>
                        </tr>';
        }



        $table .= '</tbody>
                </table>
            </div>';

        $props = [
            'title' => 'Contracts',
            'table' => $table,
            'from_date' => $from,
            'to_date'=> $to
        ];

        $statusCondition != NULL ? $props['customField'] = ['name' => 'Filter', 'value' => $statusCondition['alias']] : '';

        return \PDF::loadView('pdf.exports.table', ['props' => $props])->download('contracts.pdf');
    }

    public function excel()
    {
        $filter = Input::get('filter');
        $from = Input::get('from');
        $to = Input::get('to');

        $statusConditionFilter = [
            0 => ['field' => 'is_active', 'value' => 0, 'alias' => 'Active'],
            1 => ['field' => 'is_active', 'value' => 1, 'alias' => 'Closed'],
        ];

        $model = Contracts::query();

        //Date Conversion
        $from = $from != '' && count(explode('/', $from)) ==3 ? date('Y-m-d', strtotime(str_replace('/', '-', $from))) : date('Y-01-01');
        $to = $to != '' && count(explode('/', $to)) ==3 ? date('Y-m-d', strtotime(str_replace('/', '-', $to))) : date('Y-12-31');

        $model->whereBetween('from_date', [$from, $to]);
        //End

        $statusCondition = array_key_exists($filter, $statusConditionFilter) ? $statusConditionFilter[$filter] : NULL;

        if ($statusCondition != NULL) {
            $model->where($statusCondition['field'], $statusCondition['value']);
        }

        $items = $model->orderBy('from_date', 'ASC')->get();

        $excelFile = new \App\Essentials\ExcelBuilder('contracts_list');
        $excelFile->mergeCenterCells('B1', 'G1');
        $excelFile->setCell('B1', 'Contracts List', ['makeBold' => true, 'fontSize' => 20 ]);
        $excelFile->setCell('B2', 'From', ['makeBold' => true]);
        $excelFile->setCell('B3', 'To', ['makeBold' => true]);
        $excelFile->setCell('C2', $from);
        $excelFile->setCell('C3', $to);
        
        if( $statusCondition != NULL ){
            $excelFile->setCell('B4', 'Filter', ['makeBold' => true]);
            $excelFile->setCell('C4', $statusCondition['alias']);
        }

        $row = 6;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Contract #', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Building', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Flat', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'From', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'To', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Gross Amount', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Status', ['makeBold' => true, 'autoWidthIndex' => 7]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'H' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {
                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->id],
                    ['B' . $row, $eachItem->tenant->name],
                    ['C' . $row, $eachItem->building->name],
                    ['D' . $row, $eachItem->flat->name],
                    ['E' . $row, $eachItem->formated_from_date()],
                    ['F' . $row, $eachItem->formated_to_date()],
                    ['G' . $row, $eachItem->grossAmount()],
                    ['H' . $row, $eachItem->status()]
                ]);
            }
        }
        $excelFile->output();
    }
}
