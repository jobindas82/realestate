<?php

namespace App\Http\Controllers\Reports;

use Illuminate\Http\Request;

use App\models\Buildings;
use App\models\Flats;
use App\models\Contracts;

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
            2 => 'buildings.name',
            3 => 'flats.name',
            4 => 'contracts.from_date',
            5 => 'contracts.to_date',
            6 => 'contracts.id',
            7 => 'contracts.is_active',
            8 => 'contracts.id',
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $building_id = (int) $_POST['building_id'];
        $flat_id = (int) $_POST['flat_id'];
        $contract_id = (int) $_POST['contract_id'];

        $query = Contracts::query()
            ->leftJoin('tenants', 'tenants.id', 'contracts.tenant_id')
            ->leftJoin('buildings', 'buildings.id', 'contracts.building_id')
            ->leftJoin('flats', 'flats.id', 'contracts.flat_id')
            ->where('building_id', $building_id);

        if ($status != '')
            $query->where('is_active', $status);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->where('flats.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('flats.premise_id', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('buildings.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('tenants.name', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('contracts.id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $result = $query
            ->select('contracts.id', 'contracts.is_active', 'tenants.name AS tenant_name', 'buildings.name AS building_name', 'flats.name AS flat_name', 'contracts.from_date', 'contracts.to_date')
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
            $actions = '';
            if ($eachItem->is_active == 1) {
                $actions .= ' <a title="Edit" href="/contract/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
                $actions .= ' <a title="Add Cheques" href="/contract/cheques/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >add_circle</i></a>';
                $actions .= ' <a title="Settlement" href="/contract/settlement/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >low_priority</i></a>';
            }
            if ($eachItem->is_active == 0) {
                $actions .= ' <a title="Renew Contract" href="/contract/renew/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >autorenew</i></a>';
            }
            $actions .= ' <a title="View Receipts" href="#" onclick="window.open(\'cheques/list/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\', \'location=yes,height=0,width=0,scrollbars=yes,status=yes\');"><i class="material-icons">euro_symbol</i></a>';
            $actions .= ' <a title="Export Contract" href="#" onclick="window.open(\'/contract/export/' . UriEncode::encrypt($eachItem->id) . '\', \'_blank\')"><i class="material-icons" >picture_as_pdf</i></a>';

            $eachItemData[] = [$eachItem->id, $eachItem->tenant_name,  $eachItem->building_name, $eachItem->flat_name, $eachItem->formated_from_date(), $eachItem->formated_to_date(),  $eachItem->grossAmount(), $eachItem->status(), '<div class="text-center">' . $actions . '</div>'];
            $no++;
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }
}
