<?php

namespace App\Http\Controllers\Reports;

use App\models\Head;
use App\models\Entries;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class FinanceController extends \App\Http\Controllers\Controller
{
    public function general_ledger()
    {
        return view('reports.finance.filters.gl');
    }

}
