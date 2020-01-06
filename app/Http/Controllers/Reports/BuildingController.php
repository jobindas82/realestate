<?php

namespace App\Http\Controllers\Reports;

use App\models\Buildings;
use App\models\Flats;
use App\models\Contracts;
use App\models\Head;
use App\models\Tickets;
use App\models\Entries;

use Collective\Html\FormFacade as Form;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

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
        return Form::select('flat_id', \App\models\Flats::allFlats($building_id), '', ['class' => 'form-control show-tick', 'onchange' => 'updateFlatFilter(this.value)', 'id' => 'summary_flat_id']);
    }

    public function contracts_drop($flat_id = 0)
    {
        return Form::select('contract_id', \App\models\Contracts::allContractsFlat($flat_id), '', ['class' => 'form-control show-tick', 'onchange' => 'updateContractFilter(this.value)', 'id' => 'summary_contract_id']);
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

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];

        $query = Entries::query()
            ->leftJoin('ledgers', 'ledgers.id', 'entries.ledger_id')
            ->where('entries.building_id', $building_id);

        if ($flat_id > 0)
            $query->where('entries.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('entries.contract_id', $contract_id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ledgers.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('ledgers.name', 'ledgers.type', DB::raw('SUM(entries.amount) as amount'), 'ledgers.id')
            ->skip($offset)
            ->take($limit)
            ->groupBy('ledgers.id')
            ->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        foreach ($result as $i => $eachItem) {
            $eachItemData[] = [($i + $offset + 1), $eachItem->name, $eachItem->accountBase(), \App\Essentials\FormatAmount::format($eachItem->amount, $eachItem->id)->onBase()];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function flatSheet($building_id = 0, $flat_id = 0, $keyword = NULL, $worksheet = 0, $returnSheet =false)
    {
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

        $items = $query
            ->select('flats.name as flat_name', 'flats.premise_id', 'flats.plot_no', 'flats.floor', 'flats.minimum_value', 'flats.square_feet', 'flats.owner_name', 'flats.landlord_name', 'construction_type.name as construction_type', 'flat_types.name as flat_types', 'flats.is_available')
            ->get();

        $excelFile = new \App\Essentials\ExcelBuilder('flat_list');
        $excelFile->setWorkSheetTitle('Flats');
        //Update worksheet
        if ($worksheet > 0) {
            $excelFile->setWorkSheet($worksheet);
        }
        $excelFile->mergeCenterCells('A1', 'K1');
        $excelFile->setCell('A1', 'Flat List', ['makeBold' => true, 'fontSize' => 20]);
        $excelFile->setCell('B2', 'Building', ['makeBold' => true]);
        $buildingModel = Buildings::find($building_id);
        $buildingName =  isset($buildingModel->id) && $buildingModel->id > 0 ? $buildingModel->name : '';
        $excelFile->setCell('C2', $buildingName);
        $row = 3;

        if ($flat_id > 0) {
            $excelFile->setCell('B' . $row, 'Flat', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, Flats::find($flat_id)->name);
            $row++;
        }

        if ($keyword != '') {
            $excelFile->setCell('B' . $row, 'Filter', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, '%' . $keyword . '%');
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Flat', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Premise', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Plot No', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Floor', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Square Feet', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Minimum Value', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Owner Name', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Landlord Name', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Construction Type', ['makeBold' => true, 'autoWidthIndex' => 8]],
            ['J' . $row, 'Flat Type', ['makeBold' => true, 'autoWidthIndex' => 9]],
            ['K' . $row, 'Availability', ['makeBold' => true, 'autoWidthIndex' => 10]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'K' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {
                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->flat_name],
                    ['B' . $row, $eachItem->premise_id],
                    ['C' . $row, $eachItem->plot_no],
                    ['D' . $row, $eachItem->floor],
                    ['E' . $row, $eachItem->square_feet],
                    ['F' . $row, $eachItem->minimum_value],
                    ['G' . $row, $eachItem->owner_name],
                    ['H' . $row, $eachItem->landlord_name],
                    ['I' . $row, $eachItem->construction_type],
                    ['J' . $row, $eachItem->flat_types],
                    ['K' . $row, $eachItem->occupancy()]
                ]);
            }
        }

        if( !$returnSheet )
            $excelFile->output();
        else
           return $excelFile;
    }

    public function export_flat()
    {
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $keyword =  trim(Input::get('query'));

        $this->flatSheet($building_id, $flat_id, $keyword);
    }

    public function contractSheet($building_id = 0, $flat_id = 0, $contract_id = 0, $keyword = NULL, $worksheet = 0, $excelObject = NULL)
    {
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

        $items = $query
            ->select('contracts.id', 'contracts.is_active', 'tenants.name AS tenant_name', 'flats.name AS flat_name', 'contracts.from_date', 'contracts.to_date', 'contracts.is_renewed', 'contracts.previous_contract')
            ->get();

        $excelFile = $excelObject != NULL ? $excelObject : new \App\Essentials\ExcelBuilder('contracts_list');
        $excelFile->setWorkSheetTitle('Contracts');
        //Update worksheet
        if ($worksheet > 0) {
            $excelFile->setWorkSheet($worksheet);
        }
        $excelFile->mergeCenterCells('A1', 'J1');
        $excelFile->setCell('A1', 'Contracts List', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('B2', 'Building', ['makeBold' => true]);
        $buildingModel = Buildings::find($building_id);
        $buildingName =  isset($buildingModel->id) && $buildingModel->id > 0 ? $buildingModel->name : '';
        $excelFile->setCell('C2', $buildingName);

        $row = 3;

        if ($flat_id > 0) {
            $excelFile->setCell('B' . $row, 'Flat', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, Flats::find($flat_id)->name);
            $row++;
        }

        if ($contract_id > 0) {
            $excelFile->setCell('B' . $row, 'Contract', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, $contract_id);
            $row++;
        }

        if ($keyword != '') {
            $excelFile->setCell('B' . $row, 'Filter', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, '%' . $keyword . '%');
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Contract #', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Flat', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'From', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'To', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Renewed', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Previous Contract#', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Tax', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Gross Amount', ['makeBold' => true, 'autoWidthIndex' => 8]],
            ['J' . $row, 'Active', ['makeBold' => true, 'autoWidthIndex' => 9]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'J' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {

                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->id],
                    ['B' . $row, $eachItem->tenant_name],
                    ['C' . $row, $eachItem->flat_name],
                    ['D' . $row, $eachItem->formated_from_date()],
                    ['E' . $row, $eachItem->formated_to_date()],
                    ['F' . $row, $eachItem->isRenewed() ? 'Renewed' : 'New'],
                    ['G' . $row, $eachItem->previous_contract],
                    ['H' . $row, $eachItem->taxAmount()],
                    ['I' . $row, $eachItem->grossAmount()],
                    ['J' . $row, $eachItem->status()]
                ]);
            }
        }
        $excelFile->output();
    }

    public function export_contract()
    {
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $contract = (int) Input::get('contract');
        $keyword =  trim(Input::get('query'));

        $this->contractSheet($building_id, $flat_id, $contract, $keyword);
    }

    public function tenantSheet($building_id = 0, $flat_id = 0, $contract_id = 0, $keyword = NULL, $worksheet = 0)
    {
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

        $items = $query
            ->select('contracts.tenant_id')
            ->groupBy('contracts.tenant_id')
            ->get();

        $excelFile = new \App\Essentials\ExcelBuilder('tenant_list');
        $excelFile->setWorkSheetTitle('Tenants');
        //Update worksheet
        if ($worksheet > 0) {
            $excelFile->setWorkSheet($worksheet);
        }
        $excelFile->mergeCenterCells('A1', 'I1');
        $excelFile->setCell('A1', 'Tenant List', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('B2', 'Building', ['makeBold' => true]);
        $buildingModel = Buildings::find($building_id);
        $buildingName =  isset($buildingModel->id) && $buildingModel->id > 0 ? $buildingModel->name : '';
        $excelFile->setCell('C2', $buildingName);

        $row = 3;

        if ($flat_id > 0) {
            $excelFile->setCell('B' . $row, 'Flat', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, Flats::find($flat_id)->name);
            $row++;
        }

        if ($contract_id > 0) {
            $excelFile->setCell('B' . $row, 'Contract', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, $contract_id);
            $row++;
        }

        if ($keyword != '') {
            $excelFile->setCell('B' . $row, 'Filter', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, '%' . $keyword . '%');
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Name', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Mobile', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Phone', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Email', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Emirates ID', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Passport No', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'TRN No', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Status', ['makeBold' => true, 'autoWidthIndex' => 8]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'I' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {

                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->tenant_id],
                    ['B' . $row, $eachItem->tenant->name],
                    ['C' . $row, $eachItem->tenant->mobile],
                    ['D' . $row, $eachItem->tenant->land_phone],
                    ['E' . $row, $eachItem->tenant->email],
                    ['F' . $row, $eachItem->tenant->emirates_id],
                    ['G' . $row, $eachItem->tenant->passport_number],
                    ['H' . $row, $eachItem->tenant->trn_number],
                    ['I' . $row, $eachItem->tenant->status()]
                ]);
            }
        }
        $excelFile->output();
    }

    public function export_tenant()
    {
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $contract = (int) Input::get('contract');
        $keyword =  trim(Input::get('query'));

        $this->tenantSheet($building_id, $flat_id, $contract, $keyword);
    }

    public function financeSheet($type = 1, $building_id = 0, $flat_id = 0, $contract_id = 0, $keyword = NULL, $worksheet = 0)
    {
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

        $items = $query
            ->select('finance.type', 'finance.cheque_status', 'finance.number', 'finance.date', 'finance.contract_id', 'finance.cheque_no', 'finance.cheque_date', 'tenants.name', 'finance.id', 'finance.is_posted', 'finance.is_cancelled', 'finance.method', 'finance.narration')
            ->get();

        $fileName = $type == 1 ? 'receipt_list' : 'payment_list';
        $workSheetName = $type == 1 ? 'receipts' : 'payments';
        $heading = $type == 1 ? 'Receipt List' : 'Payment List';

        $excelFile = new \App\Essentials\ExcelBuilder($fileName);
        $excelFile->setWorkSheetTitle($workSheetName);
        //Update worksheet
        if ($worksheet > 0) {
            $excelFile->setWorkSheet($worksheet);
        }
        $excelFile->mergeCenterCells('A1', 'J1');
        $excelFile->setCell('A1', $heading, ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('B2', 'Building', ['makeBold' => true]);
        $buildingModel = Buildings::find($building_id);
        $buildingName =  isset($buildingModel->id) && $buildingModel->id > 0 ? $buildingModel->name : '';
        $excelFile->setCell('C2', $buildingName);

        $row = 3;

        if ($flat_id > 0) {
            $excelFile->setCell('B' . $row, 'Flat', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, Flats::find($flat_id)->name);
            $row++;
        }

        if ($contract_id > 0) {
            $excelFile->setCell('B' . $row, 'Contract', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, $contract_id);
            $row++;
        }

        if ($keyword != '') {
            $excelFile->setCell('B' . $row, 'Filter', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, '%' . $keyword . '%');
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, '#', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Date', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Contract #', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Method', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Cheque No', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Cheque Date', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Narration', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Amount', ['makeBold' => true, 'autoWidthIndex' => 9]],
            ['J' . $row, 'Cheque Status', ['makeBold' => true, 'autoWidthIndex' => 10]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'J' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {

                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->number],
                    ['B' . $row, $eachItem->formated_date()],
                    ['C' . $row, $eachItem->contract_id],
                    ['D' . $row, $eachItem->paymentMethod()],
                    ['E' . $row, $eachItem->cheque_no],
                    ['F' . $row, $eachItem->formated_cheque_date()],
                    ['G' . $row, $eachItem->name],
                    ['H' . $row, $eachItem->narration],
                    ['I' . $row, $eachItem->debitSum(true)],
                    ['J' . $row, $eachItem->chequeStatus()]
                ]);
            }
        }
        $excelFile->output();
    }

    public function export_finance()
    {
        $type = (int) Input::get('type');
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $contract = (int) Input::get('contract');
        $keyword =  trim(Input::get('query'));

        $this->financeSheet($type, $building_id, $flat_id, $contract, $keyword);
    }

    public function ticketSheet($building_id = 0, $flat_id = 0, $contract_id = 0, $keyword = NULL, $worksheet = 0)
    {
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


        $items = $query
            ->select('ticket.id', 'ticket.date', 'tenants.name', 'ticket.contract_id', 'ticket.details', 'ticket.job_type', 'ticket.is_active', 'ticket.remarks', 'ticket.job_category', 'ticket.priority')
            ->get();

        $excelFile = new \App\Essentials\ExcelBuilder('ticket_list');
        $excelFile->setWorkSheetTitle('Tickets');
        //Update worksheet
        if ($worksheet > 0) {
            $excelFile->setWorkSheet($worksheet);
        }
        $excelFile->mergeCenterCells('A1', 'I1');
        $excelFile->setCell('A1', 'Ticket List', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('B2', 'Building', ['makeBold' => true]);
        $buildingModel = Buildings::find($building_id);
        $buildingName =  isset($buildingModel->id) && $buildingModel->id > 0 ? $buildingModel->name : '';
        $excelFile->setCell('C2', $buildingName);

        $row = 3;

        if ($flat_id > 0) {
            $excelFile->setCell('B' . $row, 'Flat', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, Flats::find($flat_id)->name);
            $row++;
        }

        if ($contract_id > 0) {
            $excelFile->setCell('B' . $row, 'Contract', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, $contract_id);
            $row++;
        }

        if ($keyword != '') {
            $excelFile->setCell('B' . $row, 'Filter', ['makeBold' => true]);
            $excelFile->setCell('C' . $row, '%' . $keyword . '%');
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Ticket #', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Date', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Tenant', ['makeBold' => true, 'autoWidthIndex' => 2]],
            ['D' . $row, 'Contract #', ['makeBold' => true, 'autoWidthIndex' => 3]],
            ['E' . $row, 'Category', ['makeBold' => true, 'autoWidthIndex' => 4]],
            ['F' . $row, 'Priority', ['makeBold' => true, 'autoWidthIndex' => 5]],
            ['G' . $row, 'Details', ['makeBold' => true, 'autoWidthIndex' => 6]],
            ['H' . $row, 'Remarks', ['makeBold' => true, 'autoWidthIndex' => 7]],
            ['I' . $row, 'Status', ['makeBold' => true, 'autoWidthIndex' => 8]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'I' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {

                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->id],
                    ['B' . $row, $eachItem->formated_date()],
                    ['C' . $row, $eachItem->name],
                    ['D' . $row, $eachItem->contract_id],
                    ['E' . $row, $eachItem->whichCategory()],
                    ['F' . $row, $eachItem->whichPriority()],
                    ['G' . $row, $eachItem->details],
                    ['H' . $row, $eachItem->remarks],
                    ['I' . $row, $eachItem->ticketStatus()]
                ]);
            }
        }
        $excelFile->output();
    }

    public function export_ticket()
    {
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $contract = (int) Input::get('contract');
        $keyword =  trim(Input::get('query'));

        $this->ticketSheet($building_id, $flat_id, $contract, $keyword);
    }

    public function ledgerSheet($building_id = 0, $flat_id = 0, $contract_id = 0, $keyword = NULL, $worksheet = 0)
    {
        $query = Entries::query()
            ->leftJoin('ledgers', 'ledgers.id', 'entries.ledger_id')
            ->where('entries.building_id', $building_id);

        if ($flat_id > 0)
            $query->where('entries.flat_id', $flat_id);

        if ($contract_id > 0)
            $query->where('entries.contract_id', $contract_id);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('ledgers.name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $items = $query
            ->select('ledgers.name', 'ledgers.type', DB::raw('SUM(entries.amount) as amount'), 'ledgers.id')
            ->groupBy('ledgers.id')
            ->get();

        $excelFile = new \App\Essentials\ExcelBuilder('ledgers_list');
        $excelFile->setWorkSheetTitle('Ledgers');
        //Update worksheet
        if ($worksheet > 0) {
            $excelFile->setWorkSheet($worksheet);
        }
        $excelFile->mergeCenterCells('A1', 'C1');
        $excelFile->setCell('A1', 'Ledger Balance', ['makeBold' => true, 'fontSize' => 20]);

        $excelFile->setCell('A2', 'Building', ['makeBold' => true]);
        $buildingModel = Buildings::find($building_id);
        $buildingName =  isset($buildingModel->id) && $buildingModel->id > 0 ? $buildingModel->name : '';
        $excelFile->setCell('B2', $buildingName);

        $row = 3;

        if ($flat_id > 0) {
            $excelFile->setCell('A' . $row, 'Flat', ['makeBold' => true]);
            $excelFile->setCell('B' . $row, Flats::find($flat_id)->name);
            $row++;
        }

        if ($contract_id > 0) {
            $excelFile->setCell('A' . $row, 'Contract', ['makeBold' => true]);
            $excelFile->setCell('B' . $row, $contract_id);
            $row++;
        }

        if ($keyword != '') {
            $excelFile->setCell('A' . $row, 'Filter', ['makeBold' => true]);
            $excelFile->setCell('B' . $row, '%' . $keyword . '%');
            $row++;
        }

        $row++;
        $excelFile->setCellMultiple([
            ['A' . $row, 'Ledger', ['makeBold' => true, 'autoWidthIndex' => 0]],
            ['B' . $row, 'Type', ['makeBold' => true, 'autoWidthIndex' => 1]],
            ['C' . $row, 'Balance', ['makeBold' => true, 'autoWidthIndex' => 2]]
        ]);

        $excelFile->setBackgroundColorRange('A' . $row, 'C' . $row, 'A9DEFB');
        if ($items->count() > 0) {
            foreach ($items as $eachItem) {

                $row++;
                $excelFile->setCellMultiple([
                    ['A' . $row, $eachItem->name],
                    ['B' . $row, $eachItem->accountBase()],
                    ['C' . $row, \App\Essentials\FormatAmount::format($eachItem->amount, $eachItem->id)->onBase()]
                ]);
            }
        }
        $excelFile->output();
    }

    public function export_ledger()
    {
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $contract = (int) Input::get('contract');
        $keyword =  trim(Input::get('query'));

        $this->ledgerSheet($building_id, $flat_id, $contract, $keyword);
    }

    public function export_all()
    {
        $building_id = (int) Input::get('building');
        $flat_id = (int) Input::get('flat');
        $contract = (int) Input::get('contract');
        $keyword =  trim(Input::get('query'));

        $excelFile = $this->flatSheet($building_id, $flat_id, $keyword, 0, true);
        $this->contractSheet($building_id, $flat_id, $contract, $keyword, 1, $excelFile);
    }
}
