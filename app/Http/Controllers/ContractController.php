<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use App\models\Contracts;

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
            $model = Contracts::with('tenant')->find($id);
        //print_r($model);
        return view('contract.create', ['model' => $model]);
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
        
        if ($validator->fails() ) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Contracts();
            if ($data['id'] > 0) {
                $model = Contracts::find($data['id']);
            }
            $model->fill($data);
            $model->save();

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
}
