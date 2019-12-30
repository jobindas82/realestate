<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use \App\models\Tickets;

class FmController extends Controller
{
    public function tickets()
    {
        return view('fm.ticket.index');
    }

    public function ticket_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Tenants;
        if ($id > 0)
            $model = Tenants::find($id);

        return view('tenant.create', ['model' => $model, 'from' => 3]);
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
            5 => 'ticket.id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $jobType = $_POST['job_type'];
        $status = $_POST['status'];

        $query = Tickets::query()
            ->leftJoin('tenants', 'tenants.id', 'ticket.tenant_id')
            ->where('job_type', $jobType);

        if ($status >= 0) {
            $query->where('is_active', $status);
        }

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ticket.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('ticket.id', 'ticket.date', 'tenants.name', 'ticket.contract_id', 'ticket.details', 'ticket.job_type')
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
            //Edit Button
            $actions = '<a title="Edit" href="/tenant/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            if ($eachItem->job_type == 1) {
                $actions .= ' <a title="Convert to Job" href="#" onclick="convertJob(' . $eachItem->id . ', 2);"><i class="material-icons" >fast_forward</i></a>';
            }
            if ($eachItem->job_type == 2) {
                $actions .= ' <a title="Revert to Ticket" href="#" onclick="block_tenant(' . $eachItem->id . ', 1);"><i class="material-icons" >fast_rewind</i></a>';
            }

            $eachItemData[] = [$eachItem->id, $eachItem->formated_date(),  $eachItem->name, $eachItem->contract_id, $eachItem->details, '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function ticket_save(Request $request)
    {
        //Input Data
        $data = $request->all();

        //Validation of Request
        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('tenants')->ignore((int) $data['id']), 'max:255'],
            'mobile' => ['required', \Illuminate\Validation\Rule::unique('tenants')->ignore((int) $data['id'])],
            'emirates_id' => ['required', \Illuminate\Validation\Rule::unique('tenants')->ignore((int) $data['id'])]
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Tenants();
            if ($data['id'] > 0) {
                $model = Tenants::find($data['id']);
            }
            $model->fill($data);
            $model->save();

            return response()->json(['tenant_id' => $model->id, 'message' => 'success', 'tenant_id_encrypted' => $model->encoded_key()]);
        }
    }

    public function status(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['_ref'] > 0) {
            $model = Tenants::find($data['_ref']);
            if ($model->id > 0 && $model->is_available != 2) {
                $model->is_available = $data['status'];
                $model->save();
                return response()->json(['message' => 'success'], 200);
            }
        }
        return response()->json(['message' => 'failed']);
    }
}
