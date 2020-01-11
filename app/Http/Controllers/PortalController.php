<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

class PortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }
}
