<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Users List JSON Data
     */

    public function  userlist(){

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];

        $columns = array(
            // datatable column index  => database column name
            0 => 'inb_doc_no',
            1 => 'inb_delivery_note_no',
            2 => 'inb_ta_number',
            3 => 'inb_bill_of_entry_no',
            4 => 'inb_added_date',
            5 => 'inb_delivery_note_posted',
            6 => 'inb_doc_no',
        );

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];
        $orderBy   = $filterColumn." ".$filterOrder;
        $condition = '1=1';
        if(isset($_POST['search']['value']) && trim($_POST['search']['value']) !="")
        {
            $searchString = trim($_POST['search']['value']);
            $condition.=" AND (inb_doc_no LIKE '%".$searchString."%' OR inb_delivery_note_no LIKE '%".$searchString."%' OR inb_ta_number LIKE '%".$searchString."%' OR inb_bill_of_entry_no LIKE '%".$searchString."%')";
        }

        $users = DB::table('users')->get();
        $count = DB::table('users')->count();

        $recordsTotal = $count;
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData=array();


        foreach ( $users as $eachItem )
        {
            $eachItemData[]=array($eachItem->name, $eachItem->name, $eachItem->name, $eachItem->name, $eachItem->name, $eachItem->name);
        }
        $data['data'] = $eachItemData;
        echo json_encode($data);

    }
}
