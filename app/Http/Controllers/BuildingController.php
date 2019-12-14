<?php

namespace App\Http\Controllers;


use App\models\Buildings;
use App\models\Documents;
use App\models\Flats;

use Illuminate\Support\Facades\Input;
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
        return view($view, ['model' => $model, 'hiddenRow' => 1, 'from' => 1]);
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

            return response()->json(['building_id' => $model->id, 'building_id_encrypted' => $model->encoded_key(), 'message' => 'success']);
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

    public function update_status(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['_ref'] > 0) {
            $model = Buildings::find($data['_ref']);
            if ($model->id > 0) {
                $model->is_available = $data['status'];
                $model->save();

                $headerClass = 'bg-light-green';
                if ($model->is_available == 2)
                    $headerClass = 'bg-amber';
                if ($model->is_available == 3)
                    $headerClass = 'bg-red';

                $address = $model->address;
                $count = $model->flats_available();
                $key = $model->encoded_key();
                $name = $model->name;

                $content = '<div class="header ' . $headerClass . '">
                            <h2>
                                <b>' . $name . '<b> <small> ' . $address . ' </small>
                            </h2>
                            <ul class="header-dropdown m-r--5">
                                <li>
                                    <a href="javascript:void(0);" data-toggle="cardloading" data-loading-effect="timer" data-loading-color="lightBlue">
                                        <span class="badge">' . $count . ' Flats Available</span>
                                    </a>
                                </li>
                                <li class="dropdown">
                                    <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                        <i class="material-icons">more_vert</i>
                                    </a>
                                    <ul class="dropdown-menu pull-right">
                                        <li><a href="/building/create">Create</a></li>
                                        <li><a href="/building/create/' . $key . '">Edit</a></li>
                                        <li><a href="#" onclick="window.open(\'/building/flat/?_ref=' . $key . '\', \'_blank\');">Add Flat</a></li>
                                        <li><a href="#" onclick="window.open(\'/document/create/?__uuid=' . $key . '&__from=1\', \'_blank\');">Add Documents</a></li>';
                if ($model->is_available == 1) {
                    $content .= '<li><a href="#" onclick="building_status(' . $model->id . ', 3);">Block</a></li>
                                                         <li><a href="#" onclick="building_status(' . $model->id . ', 2);">Under Maintenance</a></li>';
                } else if ($model->is_available == 2) {
                    $content .= '<li><a href="#" onclick="building_status(' . $model->id . ', 1);">Active</a></li>
                                                         <li><a href="#" onclick="building_status(' . $model->id . ', 3);">Block</a></li>';
                } else {
                    $content .= '<li><a href="#" onclick="building_status(' . $model->id . ', 1);">Active</a></li>';
                }
                $content .= '</ul>
                                </li>
                            </ul>
                        </div>';

                return response()->json(['message' => 'success', 'content' => $content], 200);
            }
        }
        return response()->json(['message' => 'failed']);
    }

    public function get_documents()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $parent  = (int) $_POST['parent'];
        $from  = (int) $_POST['from'];

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
        $query = Documents::query()->where('from', $from)->where('parent_id', $parent);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('original_name', 'LIKE', '%' . $keyword . '%');
            });
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
            0 => 'flats.id',
            1 => 'buildings.name',
            2 => 'flats.name',
            3 => 'flats.square_feet',
            4 => 'construction_type.name',
            5 => 'flat_types.name',
            6 => 'flats.is_available',
            7 => 'flats.id',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = Flats::query()
            ->leftJoin('buildings', 'flats.building_id', 'buildings.id')
            ->leftJoin('flat_types', 'flats.flat_type_id', 'flat_types.id')
            ->leftJoin('construction_type', 'flats.construction_type_id', 'construction_type.id');

        //if ($building_id > 0)
        $query->where('flats.building_id', $building_id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('flats.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.premise_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.owner_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.landlord_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.plot_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('construction_type.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flat_types.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        //Result
        $result = $query->select('flats.id AS id', 'buildings.name AS building_name', 'flats.name AS flat_name', 'flats.square_feet', 'construction_type.name AS construction_name', 'flat_types.name AS flat_type_name', 'flats.is_available')->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = [];

        foreach ($result as $i => $eachItem) {
            $no = $i + $offset + 1;
            //Edit Button
            $actions = '<a title="Edit" href="#" onclick="window.open(\'/building/flat/?_key=' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >create</i></a>';
            $actions .= ' <a title="Add Document" href="#" onclick="window.open(\'/document/create/?__from=2&__uuid=' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >attach_file</i></a>';

            if ($eachItem->is_available == 1) {
                $actions .= ' <a title="Block" href="#" onclick="block_flat(' . $eachItem->id . ', 3);"><i class="material-icons" >block</i></a>';
            }
            if ($eachItem->is_available == 3) {
                $actions .= ' <a title="Unblock" href="#" onclick="block_flat(' . $eachItem->id . ', 1);"><i class="material-icons" >clear</i></a>';
            }
            $eachItemData[] = [$no, $eachItem->building_name, $eachItem->flat_name, $eachItem->square_feet, $eachItem->construction_name, $eachItem->flat_type_name,  $eachItem->occupancy(), '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function flat_create()
    {

        $building_reference = Input::get('_ref');
        $key = Input::get('_key');

        $flat_id = UriEncode::decrypt($key);
        $building_id = UriEncode::decrypt($building_reference);

        $model = new Flats();
        $modelBuilding = new Buildings();

        if ($flat_id > 0) {
            $model = Flats::find($flat_id);
            $building_id = (int) $model->building_id;
        }

        if ($building_id > 0)
            $modelBuilding = Buildings::find($building_id);

        $view = 'buildings.flat.create';
        return view($view, ['model' => $model, 'modelBuilding' => $modelBuilding, 'building_id' => $building_id, 'from' => 2]);
    }

    public function flat_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $Message = [
            'name.unique_with' => 'Flat already added!',
            'flat_type_id.gt' => 'Choose a Flat Type',
            'construction_type_id.gt' => 'Choose a Construction Type'
        ];

        $validator = \Validator::make($data, [
            'name' => ['required', 'max:255', 'unique_with:flats,building_id,' . $request->id],
            'building_id' => ['required', 'integer', 'gt:0'],
            'construction_type_id' => ['required', 'integer', 'gt:0'],
            'flat_type_id' => ['required', 'integer', 'gt:0'],
            'premise_id' => ['required', \Illuminate\Validation\Rule::unique('flats')->ignore((int) $data['id']), 'max:255']
        ], $Message);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Flats();
            if ($data['id'] > 0) {
                $model = Flats::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['flat_id' => $model->id, 'flat_id_encoded' => $model->encoded_key(), 'message' => 'success']);
        }
    }

    public function flat_status(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['_ref'] > 0) {
            $availableType = 0;
            if ($data['status'] == 3) {
                $availableType = 1;
            }
            if ($data['status'] == 1) {
                $availableType = 3;
            }
            $model = Flats::find($data['_ref']);
            if ($model->id > 0 && $model->is_available == $availableType) {
                $model->is_available = $data['status'];
                $model->save();
            }
        }

        return response()->json(['message' => 'success']);
    }

    public function flat_all($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Buildings();
        if ($id > 0)
            $model = Buildings::find($id);
        if (!isset($model->id))
            abort(403, 'Unauthorized action.');

        $view = 'buildings.flat.all';
        return view($view, ['model' => $model, 'hiddenRow' => 1, 'from' => 1]);
    }
}
