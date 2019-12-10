<?php

namespace App\Http\Controllers;


use App\models\Buildings;
use App\models\Documents;

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
        return view($view, ['model' => $model]);
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
        
        if( $data['purchase_date'] != '' )
            $data['purchase_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['purchase_date'])));

        //Validation of Request
        $validator = \Validator::make($data, [
            'depreciation_percentage' => ['required', 'numeric'],
            'purchase_date' => ['required', 'date'],
        ]);

        if( (int) $data['id'] > 0 ){
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
        }else{
            return response()->json(['purchase_date' => 'Please save Building First!'], 200);
        }
    }

    public function get_documents(){
        
        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

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
        $query = Documents::query();

        if ($keyword != "") {
            $query->orWhere('title', 'LIKE', '%' . $keyword . '%')->orWhere('expiry_date', 'LIKE', '%' . $keyword . '%');
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
            $actions = '<a title="Download" href="/file_handler/' . $eachItem->filename . '"><i class="material-icons" >file_download</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->title, $eachItem->formated_expiry_date(), '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }
}
