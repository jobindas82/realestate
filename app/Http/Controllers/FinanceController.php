<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use App\models\Head;

class FinanceController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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

        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '';
            if( !$eachItem->isCancelled() )
                $actions .= '<a title="Edit" href="/tenant/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            
            $eachItemData[] = [$eachItem->number, $eachItem->formated_date(),  $eachItem->contract_id, $eachItem->cheque_no, $eachItem->cheque_date, $eachItem->name,  $eachItem->amount(), '<div class="text-center">' . $actions . '</div>'];
            $no++;
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('tenants')->ignore((int) $data['id']), 'max:255'],
            'mobile' => ['required', \Illuminate\Validation\Rule::unique('tenants')->ignore((int) $data['id'])],
            'emirates_id' => ['required', \Illuminate\Validation\Rule::unique('tenants')->ignore((int) $data['id'])]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Tenants();
            if ($data['id'] > 0) {
                $model = Tenants::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['tenant_id' => $model->id, 'message' => 'success', 'tenant_id_encrypted' => $model->encoded_key()]);
        }
    }

    public function status(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['_ref'] > 0) {
            $model = Tenants::find($data['_ref']);
            if ($model->id > 0 && $model->is_available != 2) {
                $model->is_available = $data['status'];
                $model->save();
                return response()->json(['message' => 'success'], 200);
            }
        }
        return response()->json(['message' => 'failed']);
    }
    public function query(Request $request)
    {
        $data = $request->all();
        $keyword = trim($data['q']);
        $response = [];

        if ($keyword != '') {
            $model = Tenants::where('is_available', 1)->where('name', 'LIKE', '%' . $keyword . '%')->select('name', 'id', 'email')->limit(200)->get();
            foreach ($model as $i => $eachItem) {
                $response[$i] = ['id' => $eachItem->id, 'name' => $eachItem->name, 'email' => $eachItem->email];
            }
        }

        return response()->json($response, 200);
    }

    public function fetch(Request $request)
    {
        $data = $request->all();
        if (isset($data['_ref']) && $data['_ref'] > 0) {
            $model = Tenants::find($data['_ref']);
            return response()->json(['status' => 'success', 'emirates_id' => $model->emirates_id, 'email' => $model->email, 'passport_no' => $model->passport_number, 'phone' => $model->land_phone, 'mobile' => $model->mobile]);
        }
        return response()->json(['status' => 'failed']);
    }
}
