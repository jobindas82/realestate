<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\FlatTypes;
use App\ConstructionTypes;
use Illuminate\Http\Request;
use App\Essentials\UriEncode;

class MasterController extends Controller
{


    public function flat_type_index()
    {
        return view('masters.flat_type_index');
    }

    public function flat_type_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = FlatTypes::query();

        if ($keyword != "") {
            $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
        }

        //Result
        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();


        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '<a title="Edit details" href="/masters/flat/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->name, '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }


    public function flat_type_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new FlatTypes();
        if ($id > 0)
            $model = FlatTypes::find($id);
        $view = 'masters.flat_type_create';
        return view($view, ['model' => $model]);
    }

    public function flat_type_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('flat_types')->ignore((int) $data['id']), 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new FlatTypes();
            if ($data['id'] > 0) {
                $model = FlatTypes::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }

    public function construction_type_index()
    {
        return view('masters.construction_type_index');
    }

    public function construction_type_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = ConstructionTypes::query();

        if ($keyword != "") {
            $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
        }

        //Result
        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();


        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '<a title="Edit details" href="/masters/construction/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->name, $eachItem->taxcode['percentage'],'<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }


    public function construction_type_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new ConstructionTypes();
        if ($id > 0)
            $model = ConstructionTypes::find($id);
        $view = 'masters.construction_type_create';
        return view($view, ['model' => $model]);
    }

    public function construction_type_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('construction_type')->ignore((int) $data['id']), 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new ConstructionTypes();
            if ($data['id'] > 0) {
                $model = ConstructionTypes::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }
}