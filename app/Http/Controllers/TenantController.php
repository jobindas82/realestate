<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;
use Illuminate\Support\Facades\Input;

use App\models\Tenants;

use Collective\Html\FormFacade as Form;

class TenantController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tenant.index');
    }

    public function create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Tenants;
        if ($id > 0)
            $model = Tenants::find($id);

        return view('tenant.create', ['model' => $model, 'from' => 3]);
    }


    public function list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'mobile',
            3 => 'email',
            4 => 'emirates_id',
            5 => 'passport_number',
            6 => 'is_available',
            7 => 'id',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $query = Tenants::query();

        if ($keyword != "") {
            $query->orWhere('name', 'LIKE', '%' . $keyword . '%')
                ->orWhere('email', 'LIKE', '%' . $keyword . '%')
                ->orWhere('emirates_id', 'LIKE', '%' . $keyword . '%')
                ->orWhere('mobile', 'LIKE', '%' . $keyword . '%')
                ->orWhere('passport_number', 'LIKE', '%' . $keyword . '%');
        }

        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        $no = $offset + 1;

        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '<a title="Edit" href="/tenant/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $actions .= ' <a title="View Documents" href="#" onclick="window.open(\'/document/all/3/' . $eachItem->encoded_key() . '\', \'_blank\', \'location=yes,height=0,width=0,scrollbars=yes,status=yes\');"><i class="material-icons">folder</i></a>';
            $actions .= ' <a title="Add Document" href="#" onclick="window.open(\'/document/create/?__from=3&__uuid=' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >attach_file</i></a>';

            if ($eachItem->is_available == 1) {
                $actions .= ' <a title="Block" href="#" onclick="block_tenant(' . $eachItem->id . ', 3);"><i class="material-icons" >block</i></a>';
            }
            if ($eachItem->is_available == 3) {
                $actions .= ' <a title="Unblock" href="#" onclick="block_tenant(' . $eachItem->id . ', 1);"><i class="material-icons" >clear</i></a>';
            }

            $eachItemData[] = [$no, $eachItem->name,  $eachItem->mobile, $eachItem->email, $eachItem->emirates_id, $eachItem->passport_number,  $eachItem->status(), '<div class="text-center">' . $actions . '</div>'];
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
            $model = Tenants::where('name', 'LIKE', '%' . $keyword . '%')->orWhere('mobile', 'LIKE', '%' . $keyword . '%')->select('name', 'id', 'email')->limit(200)->get();
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
            return response()->json([
                'status' => 'success',
                'emirates_id' => $model->emirates_id,
                'email' => $model->email,
                'passport_no' => $model->passport_number,
                'phone' => $model->land_phone,
                'mobile' => $model->mobile,
                'active_contracts' =>  '' . Form::select('contract_id', \App\models\Contracts::activeContractsTenant($model->id, true), '', ['class' => 'form-control show-tick simple-dropdown', 'onChange' => 'getDetails(this.value);']) . ''
            ]);
        }
        return response()->json(['status' => 'failed']);
    }

    public function pdf()
    {
        $filter = Input::get('filter');
        $table = '<div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered ">
                        <thead>
                            <tr>
                                <th style="width:1%">#</th>
                                <th style="width:15%">Name</th>
                                <th style="width:15%">E-Mail</th>
                                <th style="width:8%">Emirates ID</th>
                                <th style="width:5%">Phone</th>
                                <th style="width:5%">Mobile</th>
                                <th style="width:5%">Passport</th>
                                <th style="width:5%">TRN</th>
                            </tr>
                        </thead>
                        <tbody>';

        $conditionFilter = [
            1 => ['field' => 'is_available', 'value' => 1, 'alias' => 'Active'],
            2 => ['field' => 'is_available', 'value' => 2, 'alias' => 'Have Contract'],
            3 => ['field' => 'is_available', 'value' => 3, 'alias' => 'Blocked'],
        ];

        $model = Tenants::query();

        $condition = array_key_exists($filter, $conditionFilter) ? $conditionFilter[$filter] : NULL;

        if ($condition != NULL) {
            $model->where($condition['field'], $condition['value']);
        }

        $items = $model->get();

        if ($items->count() > 0) {
            foreach ($items as $i => $eachItem) {
                $table .= '  <tr>
                               <td>' . ($i + 1) . '</td>
                               <td>' . $eachItem->name . '</td>
                               <td>' . $eachItem->email . '</td>
                               <td>' . $eachItem->emirates_id . '</td>
                               <td>' . $eachItem->phone . '</td>
                               <td>' . $eachItem->mobile . '</td>
                               <td>' . $eachItem->passport . '</td>
                               <td>' . $eachItem->trn_no . '</td>
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
            'title' => 'Tenant List',
            'table' => $table
        ];

        $condition != NULL ? $props['customField'] = ['name' => 'Filter', 'value' => $condition['alias']] : '';

        return \PDF::loadView('pdf.exports.table', ['props' => $props])->download('tenants.pdf');
    }

    public function excel()
    {
        $filter = Input::get('filter');

        $conditionFilter = [
            1 => ['field' => 'is_available', 'value' => 1, 'alias' => 'Active'],
            2 => ['field' => 'is_available', 'value' => 2, 'alias' => 'Have Contract'],
            3 => ['field' => 'is_available', 'value' => 3, 'alias' => 'Blocked'],
        ];

        $model = Tenants::query();

        $condition = array_key_exists($filter, $conditionFilter) ? $conditionFilter[$filter] : NULL;

        if ($condition != NULL) {
            $model->where($condition['field'], $condition['value']);
        }

        $items = $model->get();

        $excelFile = new \App\Essentials\ExcelBuilder('tenants_list');
        $excelFile->mergeCenterCells('B1', 'E1');
        $excelFile->setCell('B1', 'Tenant List', ['makeBold' => true]);
        $row = 4;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Name', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Email', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Emirates ID', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Phone', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Mobile', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Passport', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'TRN', ['makeBold' => true, 'autoWidthIndex' => 6]]
        ]);
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {
                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->name,],
                    ['B' . $row, $eachItem->email,],
                    ['C' . $row, $eachItem->emirates_id,],
                    ['D' . $row, $eachItem->phone,],
                    ['E' . $row, $eachItem->mobile,],
                    ['F' . $row, $eachItem->passport,],
                    ['G' . $row, $eachItem->trn_no,]
                ]);
            }
        }
        $excelFile->output();
    }
}
