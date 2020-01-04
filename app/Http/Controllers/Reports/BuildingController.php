<?php

namespace App\Http\Controllers\Reports;

use App\models\Flats;
use App\models\Contracts;
use App\models\Head;
use App\models\Tickets;

use Collective\Html\FormFacade as Form;

class BuildingController extends \App\Http\Controllers\Controller
{
    public function building_summary_filter()
    {
        return view('reports.filters.building_summary_filter');
    }

    public function flat_response()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'flats.name',
            1 => 'flats.premise_id',
            2 => 'flats.plot_no',
            3 => 'flats.floor',
            4 => 'flats.square_feet',
            5 => 'flats.minimum_value',
            6 => 'flats.owner_name',
            7 => 'flats.landlord_name',
            8 => 'construction_type.name',
            9 => 'flat_types.name',
            10 => 'flats.is_available',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];

        $query = Flats::query()
            ->leftJoin('buildings', 'flats.building_id', 'buildings.id')
            ->leftJoin('flat_types', 'flats.flat_type_id', 'flat_types.id')
            ->leftJoin('construction_type', 'flats.construction_type_id', 'construction_type.id')
            ->where('flats.building_id', $building_id);

        if ($flat_id > 0) {
            $query->where('flats.id', $flat_id);
        }
        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('flats.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.premise_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.plot_no', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.landlord_name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.owner_name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('flats.name as flat_name', 'flats.premise_id', 'flats.plot_no', 'flats.floor', 'flats.minimum_value', 'flats.square_feet', 'flats.owner_name', 'flats.landlord_name', 'construction_type.name as construction_type', 'flat_types.name as flat_types', 'flats.is_available')
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
            $eachItemData[] = [$eachItem->flat_name, $eachItem->premise_id,  $eachItem->plot_no, $eachItem->floor, $eachItem->square_feet, $eachItem->minimum_value, $eachItem->owner_name, $eachItem->landlord_name, $eachItem->construction_type, $eachItem->flat_types, $eachItem->is_available];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function flats_drop($building_id = 0)
    {
        return Form::select('flat_id', \App\models\Flats::allFlats($building_id), '', ['class' => 'form-control show-tick', 'onchange' => 'updateFlatFilter(this.value)']);
    }

    public function contracts_drop($flat_id = 0)
    {
        return Form::select('contract_id', \App\models\Contracts::allContractsFlat($flat_id), '', ['class' => 'form-control show-tick', 'onchange' => 'updateContractFilter(this.value)']);
    }

    public function contract_response()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'contracts.id',
            1 => 'tenants.name',
            2 => 'flats.name',
            3 => 'contracts.from_date',
            4 => 'contracts.to_date',
            5 => 'contracts.is_renewed',
            6 => 'contracts.previous_contract',
            7 => 'contracts.id',
            8 => 'contracts.id',
            9 => 'contracts.is_active',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];

        $query = Contracts::query()
            ->leftJoin('tenants', 'tenants.id', 'contracts.tenant_id')
            ->leftJoin('flats', 'flats.id', 'contracts.flat_id')
            ->where('contracts.building_id', $building_id);

        if ($flat_id > 0)
            $query->where('contracts.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('contracts.id', $contract_id);


        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('flats.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.premise_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('contracts.id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('contracts.id', 'contracts.is_active', 'tenants.name AS tenant_name', 'flats.name AS flat_name', 'contracts.from_date', 'contracts.to_date', 'contracts.is_renewed', 'contracts.previous_contract')
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

        $no = $offset + 1;

        foreach ($result as $eachItem) {
            $renewalStatus = 'New';
            $previousContract = NULL;
            if ($eachItem->isRenewed()) {
                $renewalStatus = 'Renewed';
                $previousContract = $eachItem->previous_contract;
            }
            $eachItemData[] = [$eachItem->id, $eachItem->tenant_name, $eachItem->flat_name, $eachItem->formated_from_date(), $eachItem->formated_to_date(),  $renewalStatus,  $previousContract, $eachItem->taxAmount(), $eachItem->grossAmount(), $eachItem->is_active];
            $no++;
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function tenant_response()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'tenants.id',
            1 => 'tenants.name',
            2 => 'tenants.mobile',
            3 => 'tenants.land_phone',
            4 => 'tenants.email',
            5 => 'tenants.emirates_id',
            6 => 'tenants.passport_number',
            7 => 'tenants.trn_number',
            8 => 'tenants.is_available',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];

        $query = Contracts::query()
            ->join('tenants', 'tenants.id', 'contracts.tenant_id')
            ->where('contracts.building_id', $building_id);

        if ($flat_id > 0)
            $query->where('contracts.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('contracts.id', $contract_id);


        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.email', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.mobile', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.trn_number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.passport_number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.emirates_id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query->select('contracts.tenant_id')->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->groupBy('contracts.tenant_id')->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as $eachItem) {
            $eachItemData[] = [$eachItem->tenant_id, $eachItem->tenant->name,  $eachItem->tenant->mobile, $eachItem->tenant->land_phone, $eachItem->tenant->email, $eachItem->tenant->emirates_id, $eachItem->tenant->passport_number, $eachItem->tenant->trn_number, $eachItem->tenant->is_available];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function finance_list($type = 0)
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'finance.number',
            1 => 'finance.date',
            2 => 'finance.contract_id',
            3 => 'finance.method',
            4 => 'finance.cheque_no',
            5 => 'finance.cheque_date',
            6 => 'tenants.name',
            7 => 'finance.narration',
            8 => 'finance.id',
            9 => 'finance.id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];


        $query = Head::query()
            ->leftJoin('tenants', 'tenants.id', 'finance.tenant_id')
            ->where('finance.type', $type)
            ->where('building_id', $building_id);

        if ($flat_id > 0)
            $query->where('finance.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('finance.contract_id', $contract_id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.number', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('finance.cheque_no', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('finance.type', 'finance.cheque_status', 'finance.number', 'finance.date', 'finance.contract_id', 'finance.cheque_no', 'finance.cheque_date', 'tenants.name', 'finance.id', 'finance.is_posted', 'finance.is_cancelled', 'finance.method', 'finance.narration')
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
            $eachItemData[] = [$eachItem->number, $eachItem->formated_date(), $eachItem->contract_id,  $eachItem->paymentMethod(), $eachItem->cheque_no, $eachItem->formated_cheque_date(), $eachItem->name, $eachItem->narration,  $eachItem->debitSum(true), $eachItem->chequeStatus()];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function ticket_response()
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
            4 => 'ticket.job_category',
            5 => 'ticket.priority',
            6 => 'ticket.details',
            7 => 'ticket.remarks',
            8 => 'ticket.is_active'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];

        $query = Tickets::query()
            ->leftJoin('tenants', 'tenants.id', 'ticket.tenant_id')
            ->leftJoin('contracts', 'contracts.id', 'ticket.contract_id')
            ->where('contracts.building_id', $building_id);

        if ($flat_id > 0)
            $query->where('contracts.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('ticket.contract_id', $contract_id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ticket.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ticket.contract_id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('ticket.id', 'ticket.date', 'tenants.name', 'ticket.contract_id', 'ticket.details', 'ticket.job_type', 'ticket.is_active', 'ticket.remarks', 'ticket.job_category', 'ticket.priority')
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
            $eachItemData[] = [$eachItem->id, $eachItem->formated_date(),  $eachItem->name, $eachItem->contract_id, $eachItem->whichCategory(), $eachItem->whichPriority(), nl2br($eachItem->details), nl2br($eachItem->remarks), $eachItem->ticketStatus()];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function ledger_response()
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
            4 => 'ticket.job_category',
            5 => 'ticket.priority',
            6 => 'ticket.details',
            7 => 'ticket.remarks',
            8 => 'ticket.is_active'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];

        $query = Tickets::query()
            ->leftJoin('tenants', 'tenants.id', 'ticket.tenant_id')
            ->leftJoin('contracts', 'contracts.id', 'ticket.contract_id')
            ->where('contracts.building_id', $building_id);

        if ($flat_id > 0)
            $query->where('contracts.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('ticket.contract_id', $contract_id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ticket.contract_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('ticket.contract_id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('ticket.id', 'ticket.date', 'tenants.name', 'ticket.contract_id', 'ticket.details', 'ticket.job_type', 'ticket.is_active', 'ticket.remarks', 'ticket.job_category', 'ticket.priority')
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
            $eachItemData[] = [$eachItem->id, $eachItem->formated_date(),  $eachItem->name, $eachItem->contract_id, $eachItem->whichCategory(), $eachItem->whichPriority(), nl2br($eachItem->details), nl2br($eachItem->remarks), $eachItem->ticketStatus()];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }
}
