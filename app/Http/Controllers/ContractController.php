<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        //print_r($model);
        return view('contract.create', ['model' => $model]);
    }

    public function create_cheques($key =0)
    {
        $contract_id = UriEncode::decrypt($key);
        if( $contract_id > 0 )
            $model = Contracts::find($contract_id);
        
        if( isset($model->id) && $model->id > 0 )
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

        $status = (int) $_POST['status'];

        $query = Contracts::query()
            ->leftJoin('tenants', 'tenants.id', 'contracts.tenant_id')
            ->leftJoin('buildings', 'buildings.id', 'contracts.building_id')
            ->leftJoin('flats', 'flats.id', 'contracts.flat_id');

        if ($status > 0)
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

        $result = $query
            ->select('contracts.id', 'contracts.is_active', 'tenants.name AS tenant_name', 'buildings.name AS building_name', 'flats.name AS flat_name', 'contracts.from_date', 'contracts.to_date')
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

        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '<a title="Edit" href="/contract/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $actions .= ' <a title="Add Cheques" href="/contract/cheques/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >add_circle</i></a>';
            //$actions .= ' <a title="Export Contract" href="#" onclick="window.open(\'/contract/export/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >picture_as_pdf</i></a>';

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
            'from_date' => ['required', 'date', 'after_or_equal:generated_date'],
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
            $pdf = \PDF::loadView('pdf.invoice_material', ['model' => $model]);
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
                    <td>' . Form::number('Entries[0][amount]', '', ['class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_0_amount', 'min' => 1, 'max' => 999999999999999, 'step'=> '.0000001', 'onKeyup' => 'calculate();', 'onBlur' => 'round_field(this.id)']) . '</td>
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
                                <td>' . Form::number('Entries[' . $i . '][amount]', '', ['class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_' . $i . '_amount', 'min' => 1, 'max' => 999999999999999, 'step'=> '.0000001', 'onKeyup' => 'calculate();', 'onBlur' => 'round_field(this.id)']) . '</td>
                                <td><a href="#" title="Remove" id="Entries_' . $i . '_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                            </tr>';
                $j = $i;
            }
            $j++;
            if ($model->items->sum('tax_amount') > 0)
                $items .= ' <tr>
                            <th><label>' . $j . '</label></th>
                            <td>' . Form::select('Entries[' . $j . '][ledger_id]', \App\models\Ledgers::children(0), Ledgers::findClass(Ledgers::SALES_VAT)->id, ['class' => 'form-control show-tick', 'required' => true, 'id' => 'Entries_' . $j . '_ledger_id']) . '</td>
                            <td>' . Form::number('Entries[' . $j . '][amount]', '', ['class' => 'form-control align-right', 'required' => true, 'id' => 'Entries_' . $j . '_amount', 'min' => 1, 'max' => 999999999999999, 'step'=> '.0000001', 'onKeyup' => 'calculate();', 'onBlur' => 'round_field(this.id)']) . '</td>
                            <td><a href="#" title="Remove" id="Entries_' . $j . '_delete" onclick="deleteRow(this);"><i class="material-icons">delete_forever</i></a></td>
                        </tr>';
        }
        return response()->json(['message' => 'success', 'tenant_name' => $name, 'amount' => $amount, 'contract_items' => $items], 200);
    }
}
