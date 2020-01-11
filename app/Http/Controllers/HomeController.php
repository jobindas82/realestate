<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Carbon;

use App\models\Contracts;
use App\models\Head;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('home.index');
    }

    public function expiring_contracts()
    {
        $start = Input::get('start');
        $end = Input::get('end');
        $from_date = Carbon::parse($start)->setTimezone('Asia/Dubai')->format('Y-m-d');
        $to_date = Carbon::parse($end)->setTimezone('Asia/Dubai')->format('Y-m-d');
        $allExpiringContracts = Contracts::select('id', 'to_date')->whereBetween('to_date', [$from_date, $to_date])->where('is_active', 1)->get();
        $events = [];
        foreach ($allExpiringContracts as $each) {
            $events[] = ['title' => 'Expiring | Contract #' . $each->id, "start" => $each->to_date, 'url' => 'contract/export/' . $each->encoded_key()];
        }
        return response()->json($events);
    }

    public function uncleared_payments()
    {
        $start = Input::get('start');
        $end = Input::get('end');
        $from_date = Carbon::parse($start)->setTimezone('Asia/Dubai')->format('Y-m-d');
        $to_date = Carbon::parse($end)->setTimezone('Asia/Dubai')->format('Y-m-d');
        $unclearedPayments = Head::select('id', 'cheque_no', 'cheque_date')
            ->whereBetween('cheque_date', [$from_date, $to_date])
            ->where('type', 2)
            ->where('method', 2)
            ->where('cheque_status', 0)
            ->where('is_posted', 1)
            ->get();
        $events = [];
        foreach ($unclearedPayments as $each) {
            $events[] = ['title' => 'Payment | Cheque #' . $each->cheque_no, "start" => $each->cheque_date, 'url' => 'finance/export/' . $each->encoded_key()];
        }
        return response()->json($events);
    }

    public function uncleared_receipts()
    {
        $start = Input::get('start');
        $end = Input::get('end');
        $from_date = Carbon::parse($start)->setTimezone('Asia/Dubai')->format('Y-m-d');
        $to_date = Carbon::parse($end)->setTimezone('Asia/Dubai')->format('Y-m-d');
        $unclearedReceipts = Head::select('id', 'cheque_no', 'cheque_date')
            ->whereBetween('cheque_date', [$from_date, $to_date])
            ->where('type', 1)
            ->where('method', 2)
            ->where('cheque_status', 0)
            ->where('is_posted', 1)
            ->get();
        $events = [];
        foreach ($unclearedReceipts as $each) {
            $events[] = ['title' => 'Receipt | Cheque #' . $each->cheque_no, "start" => $each->cheque_date, 'url' => 'finance/export/' . $each->encoded_key()];
        }
        return response()->json($events);
    }
}
