<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\Buildings;
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

    public function create($key=0){
        $id = UriEncode::decrypt($key);
        $model = new Buildings();
        if ($id > 0)
            $model = Buildings::find($id);
        $view = 'buildings.create';
        return view($view, ['model' => $model]);
    }
}
