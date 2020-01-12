<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use \App\models\Tickets;
use Illuminate\Support\Facades\Auth;

class FmController extends Controller
{
    public function tickets()
    {
        return view('fm.ticket.index');
    }

    public function ticket_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Tickets;
        if ($id > 0)
            $model = Tickets::find($id);

        return view('fm.ticket.create', ['model' => $model, 'showTicket' => true, 'showJob' => false]);
    }

    public function job_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Tickets;
        if ($id > 0)
            $model = Tickets::find($id);

        return view('fm.ticket.create', ['model' => $model, 'showTicket' => true, 'showJob' => true]);
    }


    public function ticket_list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'ticket.id',
            1 => 'ticket.date',
            2 => 'tenants.name',
            3 => 'ticket.contract_id',
            4 => 'ticket.details',
            5 => 'ticket.remarks',
            6 => 'ticket.is_active',
            7 => 'ticket.id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $jobType = $_POST['job_type'];
        $status = $_POST['status'];

        $query = Tickets::query()
            ->leftJoin('tenants', 'tenants.id', 'ticket.tenant_id')
            ->where('job_type', $jobType);

        if ($keyword == "") {
            $query->where('is_active', 1);
        }

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ticket.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('ticket.id', 'ticket.date', 'tenants.name', 'ticket.contract_id', 'ticket.details', 'ticket.job_type', 'ticket.is_active', 'ticket.remarks')
            ->skip($offset)
            ->take($limit)
            ->orderBy($filterColumn, $filterOrder)
            ->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as $eachItem) {
            $actions = '';
            if ($eachItem->isTicket()) {
                if (boolval($eachItem->is_active))
                    $actions .= '<a title="Edit" href="/fm/ticket/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
                $actions .= ' <a title="Convert to Job" href="/fm/job/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >fast_forward</i></a>';
            } else {
                if (boolval($eachItem->is_active)) {
                    $actions .= ' <a title="Edit" href="/fm/job/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
                    $actions .= ' <a title="Revert to Ticket" href="#" onclick="updateJob(' . $eachItem->id . ', 1);"><i class="material-icons" >fast_rewind</i></a>';
                    $actions .= ' <a title="Mark as Finished" href="#" onclick="updateJob(' . $eachItem->id . ', 2);"><i class="material-icons" >check_circle</i></a>';
                }
            }
            $eachItemData[] = [$eachItem->id, $eachItem->formated_date(),  $eachItem->name, $eachItem->contract_id, nl2br($eachItem->details), nl2br($eachItem->remarks), $eachItem->is_active, '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function ticket_save(Request $request)
    {
        $data = $request->all();

        if ($data['date'] != '')
            $data['date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['date'])));

        $validator = \Validator::make($data, [
            'tenant_id' => ['required', 'integer', 'gt:0'],
            'contract_id' => ['required', 'integer', 'gt:0'],
            'date' => ['required', 'date'],
            'priority' => ['required', 'integer', 'gt:0'],
            'details' => ['required'],
        ], [
            'tenant_id.required' => 'Select a Tenant',
            'contract_id.required' => 'Select a Contract'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Tickets();
            if ($data['id'] > 0) {
                $model = Tickets::find($data['id']);
            }
            $model->fill($data);
            $model->created_by =  Auth::user()->id;
            $model->save();

            return response()->json(['ticket_id' => $model->id, 'message' => 'success']);
        }
    }

    public function jobs_save(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'id' => ['required', 'integer', 'gt:0'],
            'job_category' => ['required'],
        ], [
            'id.required' => 'Save Ticket First!'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Tickets();
            if ($data['id'] > 0) {
                $model = Tickets::find($data['id']);
            }
            $model->fill($data);
            $model->save();
            $model->convertToJob();
            // $model->inProgress();

            return response()->json(['message' => 'success']);
        }
    }

    public function update_job($action =0, $ticket_id =0){
        if( $ticket_id > 0 ){
            $model = Tickets::find($ticket_id);
            if( $action == 1 ){ //Revert
                $model->revertToTicket();
            }
            if( $action == 2 ){ //mark Finished
                $model->markFinished();
            }
        }
        return response()->json(['message' => 'success']);
    }
}
