<?php

namespace App\Http\Controllers;

use App\models\Documents;
use App\Essentials\UriEncode;

use Illuminate\Support\Facades\Input;

class DocumentController extends Controller
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


    public function building()
    {
        $queryPrams = Input::all();

        $parent_id = isset( $queryPrams['__uuid'] ) ? UriEncode::decrypt( $queryPrams['__uuid'] ) : 0;
        $from = 1;

        if( (int) $parent_id == 0 )
            abort(403, 'Unauthorized action.');

        return view('document.building', [ 'from' => $from, 'parent_id' => $parent_id ]);
    }
}
