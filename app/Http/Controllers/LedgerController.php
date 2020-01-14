<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Essentials\UriEncode;

use App\models\Ledgers;

class LedgerController extends Controller
{
    public function index()
    {
        return view('ledger.index');
    }

    public function create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Ledgers;
        if ($id > 0)
            $model = Ledgers::find($id);

        return view('ledger.create', ['model' => $model]);
    }


    public function list()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $columns = [
            0 => 'id',
            1 => 'name',
            2 => 'name',
            3 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        $isParent = $_POST['is_parent'];
        $query = Ledgers::query()->where('is_parent', $isParent);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('name', 'LIKE', '%' . $keyword . '%');
            });
        }

        $count = $query->count();
        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $count;
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = array();

        $no = $offset + 1;

        foreach ($result as $eachItem) {
            $actions= '';
            if( $eachItem->is_generated == 'N')
                $actions .= '<a title="Edit" href="/ledger/create/' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >create</i></a>';
            $eachItemData[] = $isParent == 'Y' ? [$no, $eachItem->name,  $eachItem->rootNames(), '<div class="text-center">' . $actions . '</div>'] :  [$no, $eachItem->name,  $eachItem->rootNames(), $eachItem->currentBalance(), '<div class="text-center">' . $actions . '</div>'];
            $no++;
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function save(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('ledgers')->ignore((int) $data['id']), 'max:255'],
            'parent_id' => ['required', 'integer', 'gt:0']
        ], [
            'parent_id.gt' => 'Primary Account Reserved for System',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Ledgers();
            if ($data['id'] > 0) {
                $model = Ledgers::find($data['id']);
            }

            $model->fill($data);
            $model->is_parent = 'N';
            $model->addLevel();
            $model->addRoot();
            $model->inheritParent();
            $model->addClass();

            if ($model->is_reached_maximum_level()) {
                $model->save();
                $model->updateEntries();
            } else {
                return response()->json(['message' => 'failed', 'name' => 'Maximum allowable Level is ' . Ledgers::MAX_LEVEL]);
            }

            return response()->json(['ledger_id' => $model->id, 'message' => 'success']);
        }
    }

    public function group_index()
    {
        return view('ledger.group.index');
    }

    public function group_create($key = 0)
    {
        $id = UriEncode::decrypt($key);
        $model = new Ledgers;
        if ($id > 0)
            $model = Ledgers::find($id);

        return view('ledger.group.create', ['model' => $model]);
    }

    public function group_save(Request $request)
    {
        $data = $request->all();

        $validator = \Validator::make($data, [
            'name' => ['required', \Illuminate\Validation\Rule::unique('ledgers')->ignore((int) $data['id']), 'max:255'],
            'parent_id' => ['required', 'integer', 'gt:0']
        ], [
            'parent_id.gt' => 'Primary Account Reserved for System',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {

            $model = new Ledgers();
            if ($data['id'] > 0) {
                $model = Ledgers::find($data['id']);
            }

            $model->fill($data);
            $model->is_parent = 'Y';
            $model->addLevel();
            $model->addRoot();
            $model->inheritParent();
            $model->addClass();

            if ($model->is_reached_maximum_level(true)) {
                $model->save();
            } else {
                return response()->json(['message' => 'failed', 'name' => 'Maximum allowable Level is ' .( Ledgers::MAX_LEVEL - 1 )]);
            }

            return response()->json(['group_id' => $model->id, 'message' => 'success']);
        }
    }
}
