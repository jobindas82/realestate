<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use App\models\Session;
use App\models\Contracts;
use App\models\Tickets;

class PortalController extends Controller
{
    public function index(Request $request)
    {
        if ($request->session()->has('e')) {
            $encryptedTenant = $request->session()->get('e');
            if (Session::isActive($encryptedTenant)) {
                return redirect()->route('portal.home');
            } else {
                $request->session()->forget('e');
                $request->session()->flush();
                Session::clear($encryptedTenant);
            }
        }

        return view('portal.index');
    }

    public function open(Request $request)
    {
        $data = $request->all();

        $request->validate([
            'emirates_id' => ['required', new \App\Rules\haveContracts((int) $data['mobile_no'])],
            'mobile_no' => ['required']
        ]);

        $tenantModel = \App\models\Tenants::where('is_available', 2)
            ->where('emirates_id', $data['emirates_id'])
            ->where('mobile', $data['mobile_no'])
            ->first();

        Session::set($tenantModel->encoded_key());
        $request->session()->put('e', $tenantModel->encoded_key());
        return redirect()->route('portal.home');
    }

    public function home()
    {
        return view('portal.home');
    }

    public function logout(Request $request)
    {
        if ($request->session()->has('e')) {
            $encryptedTenant = $request->session()->get('e');
            if (Session::isActive($encryptedTenant)) {
                $request->session()->forget('e');
                $request->session()->flush();
                Session::clear($encryptedTenant);
            }
        }
        return redirect()->route('portal');
    }

    public function contract_list()
    {

        $draw   = $_POST['draw'];
        $query = Contracts::query()
            ->where('is_active', 1)
            ->where('tenant_id', request()->tenantModel->id);

        $result = $query->orderBy('from_date', 'ASC')->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as $eachItem) {
            $actions = ' <a title="Create Ticket" href="/portal/create/ticket/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >build</i></a>';
            $eachItemData[] = [$eachItem->id,  $eachItem->building->name, $eachItem->flat->name, $eachItem->formated_from_date(), $eachItem->formated_to_date(),  $eachItem->grossAmount(), '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function create_ticket($key = 0)
    {
        $contract_id = UriEncode::decrypt($key);
        $model = new Tickets;
        $contractModel = Contracts::findOrFail($contract_id);
        return view('portal.create_ticket', ['model' => $model, 'contractModel' => $contractModel]);
    }

    public function save_ticket(Request $request)
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
            $model->fill($data);
            $model->save();

            return response()->json(['ticket_id' => $model->id, 'message' => 'success']);
        }
    }

    public function tickets()
    {
        return view('portal.tickets');
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
            2 => 'ticket.contract_id',
            3 => 'ticket.details',
            4 => 'ticket.is_active'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $query = Tickets::query()
            ->leftJoin('contracts', 'contracts.id', 'ticket.contract_id')
            ->where('contracts.is_active', 1)
            ->where('ticket.tenant_id', request()->tenantModel->id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ticket.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ticket.details', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('ticket.id', 'ticket.date', 'ticket.contract_id', 'ticket.details', 'ticket.is_active')
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
            $eachItemData[] = [$eachItem->id, $eachItem->formated_date(), $eachItem->contract_id, nl2br($eachItem->details), $eachItem->ticketStatus()];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }
}
