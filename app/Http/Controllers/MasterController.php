<?php

namespace App\Http\Controllers;

use App\models\FlatTypes;
use App\models\ConstructionTypes;
use App\models\Countries;
use App\models\Location;
use Illuminate\Http\Request;
use App\Essentials\UriEncode;
use App\models\JobTypes;

class MasterController extends Controller
{


    public function flat_type_index()
    {
        return view('masters.flat_type.index');
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
        $view = 'masters.flat_type.create';
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
        return view('masters.construction_type.index');
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
            2 => 'name',
            3 => 'id'
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
            $eachItemData[] = [$eachItem->id, $eachItem->name, $eachItem->taxcode['percentage'], '<div class="text-center">' . $actions . '</div>'];
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
        $view = 'masters.construction_type.create';
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

    public function country_index()
    {
        return view('masters.country.index');
    }

    public function country_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'code',
            2 => 'name',
            3 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = Countries::query();

        if ($keyword != "") {
            $query->orWhere('code', 'LIKE', '%' . $keyword . '%')->orWhere('name', 'LIKE', '%' . $keyword . '%');
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
            $actions = '<a title="Edit details" href="/masters/country/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->code, $eachItem->name, '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function country_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Countries();
        if ($id > 0)
            $model = Countries::find($id);
        $view = 'masters.country.create';
        return view($view, ['model' => $model]);
    }

    public function country_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'code' => ['required', \Illuminate\Validation\Rule::unique('countries')->ignore((int) $data['id']), 'max:10'],
            'name' => ['required', \Illuminate\Validation\Rule::unique('countries')->ignore((int) $data['id']), 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Countries();
            if ($data['id'] > 0) {
                $model = Countries::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }

    public function location_index()
    {
        return view('masters.location.index');
    }

    public function location_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            // datatable column index  => database column name
            0 => 'locations.id',
            1 => 'locations.name',
            2 => 'countries.name',
            3 => 'locations.id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = Location::query()->join('countries', 'locations.country_id', 'countries.id');

        if ($keyword != "") {
            $query->orWhere('locations.name', 'LIKE', '%' . $keyword . '%')->orWhere('countries.name', 'LIKE', '%' . $keyword . '%');
        }

        //Result
        $result = $query->select('locations.name AS location', 'countries.name AS country', 'locations.id AS id')->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as $eachItem) {
            //Edit Button
            $actions = '<a title="Edit details" href="/masters/location/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->location, $eachItem->country, '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function location_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Location();
        if ($id > 0)
            $model = Location::find($id);
        $view = 'masters.location.create';
        return view($view, ['model' => $model]);
    }

    public function location_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'country_id' => ['required'],
            'name' => ['required', \Illuminate\Validation\Rule::unique('locations')->ignore((int) $data['id']), 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Location();
            if ($data['id'] > 0) {
                $model = Location::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }

    public function job_type_index()
    {
        return view('masters.job_type.index');
    }

    public function job_type_list()
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
        $query = JobTypes::query();

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
            $actions = '<a title="Edit details" href="/masters/job/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->name, '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    
    public function job_type_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new JobTypes();
        if ($id > 0)
            $model = JobTypes::find($id);
        $view = 'masters.job_type.create';
        return view($view, ['model' => $model]);
    }

    public function job_type_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('job_types')->ignore((int) $data['id']), 'max:255']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new JobTypes();
            if ($data['id'] > 0) {
                $model = JobTypes::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }
}
