<?php

namespace App\Http\Controllers;


use App\models\Buildings;
use App\models\Documents;
use App\models\Flats;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

class BuildingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('buildings.index');
    }

    public function create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Buildings();
        if ($id > 0)
            $model = Buildings::find($id);
        $view = 'buildings.create';
        return view($view, ['model' => $model, 'hiddenRow' => 1]);
    }

    public function save_basics(Request $request)
    {

        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('buildings')->ignore((int) $data['id']), 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Buildings();
            if ($data['id'] > 0) {
                $model = Buildings::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['building_id' => $model->id, 'message' => 'success']);
        }
    }

    public function save_depreciation(Request $request)
    {

        //Input Data
        $data = $request->all();

        if ($data['purchase_date'] != '')
            $data['purchase_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['purchase_date'])));

        //Validation of Request
        $validator = \Validator::make($data, [
            'depreciation_percentage' => ['required', 'numeric'],
            'purchase_date' => ['required', 'date'],
        ]);

        if ((int) $data['id'] > 0) {
            if ($validator->fails()) {
                return response()->json($validator->messages(), 200);
            } else {

                $model = new Buildings();
                if ($data['id'] > 0) {
                    $model = Buildings::find($data['id']);
                }
                $model->fill($data);
                $model->save();

                return response()->json(['message' => 'success']);
            }
        } else {
            return response()->json(['purchase_date' => 'Please save Building First!'], 200);
        }
    }

    public function get_documents()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $parent  = (int) $_POST['parent'];

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',
            2 => 'expiry_date',
            3 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = Documents::query()->where('from', 1)->where('parent_id', $parent);

        if ($keyword != "") {
            $query->where('title', 'LIKE', '%' . $keyword . '%')->orWhere('expiry_date', 'LIKE', '%' . $keyword . '%');
        }

        //Result
        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = [];

        foreach ($result as $eachItem) {
            //Edit Button

            $actions = '<a title="Download" href="/document/download/?__token=' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >file_download</i></a>';
            $actions .= '<a title="Save" href="#" onclick="save_doc(' . $eachItem->id . ');"><i class="material-icons" >save</i></a>';
            $actions .= '<a title="Remove" href="#" onclick="remove_doc(' . $eachItem->id . ');"><i class="material-icons" >delete_sweep</i></a>';

            $eachItemData[] = [$eachItem->id, '<input type="text" class="form-control" value="' . $eachItem->title . '" id="doc_title_' . $eachItem->id . '" />', '<input type="text" class="form-control datepicker" value="' . $eachItem->formated_expiry_date() . '" id="doc_exp_' . $eachItem->id . '" />', '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function update_document(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['expiry_date'] != '')
            $data['expiry_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['expiry_date'])));

        //Validation of Request
        $validator = \Validator::make($data, [
            '_ref' => ['required', 'integer', 'gt:0'],
            'expiry_date' => ['nullable', 'date']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {
            $model = Documents::find($data['_ref']);
            $model->title = $data['title'];
            $model->expiry_date = $data['expiry_date'];
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }

    public function flat_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $building_id  = (int) $_POST['parent'];

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'id',
            3 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = Flats::query();

        if( $building_id > 0 )
            $query->where('building_id', $building_id);

        if ($keyword != "") {
            $query->where('name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('premise_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('owner_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('landlord_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('plot_no', 'LIKE', '%' . $keyword . '%');
        }

        //Result
        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = [];

        foreach ($result as $i => $eachItem) {
            $no = $i + $offset + 1;
            //Edit Button
            $actions = '<a title="Edit" href="/masters/location/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = [$no, $eachItem->building->name, $eachItem->name, $eachItem->square_feet, $eachItem->construction->name, $eachItem->flat_type->name,  $eachItem->occupancy(), '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }
}
