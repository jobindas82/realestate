<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\Essentials\UriEncode;
use App\Events\MyEvent;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $userModel = new User;
        if ($id > 0)
            $userModel = User::find($id);
        $view = $id > 0 ? 'users.update' : 'users.create';
        return view($view, ['userModel' => $userModel]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\userRequest  $request
     * @return \App\Http\Requests\userRequest $response
     */
    public function update(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => 'required',
            'email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $count = 0;
            if ($data['old_email'] != $data['email'] && trim($data['email']) != '') {
                $count = DB::table('users')->where('email', $data['email'])->count();
            }

            if ($count > 0) {
                return response()->json(['email' => 'Email already exist!'], 200);
            } else {

                $userModel = new User;
                if ($data['id'] > 0) {
                    $userModel = User::find($data['id']);
                }
                $userModel->fill($data);
                $userModel->save();

                //event(new MyEvent('hello world'));

                return response()->json(['message' => 'success']);
            }
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\userRequest  $request
     * @return \App\Http\Requests\userRequest $response
     */
    public function changepword(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'id' => 'required',
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $userModel = new User;
            if ($data['id'] > 0) {
                $userModel = User::find($data['id']);
            }

            $userModel->password = Hash::make($data['new_password']);
            $userModel->save();

            return response()->json(['message' => 'success']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\userRequest  $request
     * @return \App\Http\Requests\userRequest $response
     */
    public function store(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => 'required',
            'email' => ['required', function ($attribute, $value, $fail) {
                if (trim($value) != '') {
                    $count = DB::table('users')->where('email', $value)->count();
                    if ($count > 0)
                        $fail('Email already in use!');
                }
            }],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $userModel = new User;
            $userModel->fill($data);
            $userModel->password = Hash::make($data['new_password']);
            $userModel->save();

            return response()->json(['message' => 'success']);
        }
    }


    /**
     * Users List JSON Data
     */

    public function userlist()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        //Custom Filter
        $filter_type = $_POST['filter_type'];

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'name',
            2 => 'email',
            3 => 'name',
            4 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = User::query();

        if ($filter_type != '') { }

        if ($keyword != "") {
            $query->orWhere('name', 'LIKE', '%' . $keyword . '%');
            $query->orWhere('email', 'LIKE', '%' . $keyword . '%');
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
            $actions = '<a title="Edit user details" href="/users/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            //Block Button
            $actions .= ' <a title="Block User" href="#" onclick="blockUser(' . $eachItem->id . ')"><i class="material-icons" >block</i></a>';
            $eachItemData[] = [$eachItem->id, $eachItem->name, $eachItem->email, 'NA', '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function update_theme(Request $request){
        
        if( !Auth::guest() ){
            $data = $request->all();
            $model = User::find(Auth::user()->id);
            $model->theme = $data['_ref'];
            $model->save();

            return response()->json(['message' => 'success']);
        }

        return response()->json(['message' => 'failed']);

    }
}
