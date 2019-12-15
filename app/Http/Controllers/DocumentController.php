<?php

namespace App\Http\Controllers;

use App\models\Documents;
use App\Essentials\UriEncode;

use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
// use Intervention\Image\Facades\Image;

class DocumentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    private $upload_path;

    public function __construct()
    {
        $this->middleware('auth');

        $this->upload_path = public_path('/uploads');
    }


    public function create()
    {
        $queryPrams = Input::all();
        $parent_id = isset($queryPrams['__uuid']) ? UriEncode::decrypt($queryPrams['__uuid']) : 0;
        $from = isset($queryPrams['__from']) ? (int) $queryPrams['__from'] : 0;

        $models = [
            1 => new \App\models\Buildings(),
            2 => new \App\models\Flats(),
            3 => new \App\models\Tenants()
        ];

        if (!in_array($from, [1, 2, 3]))
            abort(403, 'Unauthorized action.');

        if ((int) $parent_id > 0) {
            $model = $models[$from]::where('id', $parent_id)->first();

            if (!isset($model))
                abort(403, 'Unauthorized action.');

            $labels = [
                1 => 'Building | ' . $model->name,
                2 => 'Flat | ' . $model->name,
                3 => 'Tenant | ' . $model->name
            ];

            $label = $labels[$from];
        } else {
            abort(403, 'Unauthorized action.');
        }

        return view('document.create', ['from' => $from, 'parent' => $parent_id, 'title' => 'Upload Documents for ' . $label]);
    }

    /**
     * Saving images uploaded through XHR Request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        $files = $request->file('file');
        $parent = $request->get('parent');
        $from = $request->get('from');

        if (!is_array($files)) {
            $files = [$files];
        }

        if (!is_dir($this->upload_path)) {
            mkdir($this->upload_path, 0777);
        }

        for ($i = 0; $i < count($files); $i++) {
            $file = $files[$i];
            $name = sha1(date('YmdHis') . str_random(30));
            $save_name = $name . '.' . $file->getClientOriginalExtension();

            // Image::make($file)
            //     ->resize(250, null, function ($constraints) {
            //         $constraints->aspectRatio();
            //     })
            //     ->save($this->upload_path . '/' . $resize_name);

            $file->move($this->upload_path, $save_name);

            $upload = new Documents();
            $upload->original_name = basename($file->getClientOriginalName());
            $upload->parent_id = $parent;
            $upload->title = $upload->original_name;
            $upload->filename = $save_name;
            $upload->from = $from;
            $upload->save();
        }
        return Response::json([
            'message' => 'File Uploaded'
        ], 200);
    }

    /**
     * Remove the images from the storage.
     *
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $filename = $request->id;
        $uploaded_file = Documents::where('original_name', basename($filename))->first();

        if (empty($uploaded_file)) {
            return Response::json(['message' => 'Sorry file does not exist'], 400);
        }

        $file_path = $this->upload_path . '/' . $uploaded_file->filename;
        // $resized_file = $this->upload_path . '/' . $uploaded_file->resized_name;

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        // if (file_exists($resized_file)) {
        //     unlink($resized_file);
        // }

        if (!empty($uploaded_file)) {
            $uploaded_file->delete();
        }

        return Response::json(['message' => 'File successfully delete'], 200);
    }

    public function destroy_with_id(Request $request)
    {
        $id = (int) $request->get('_ref');
        $uploaded_file = Documents::where('id', $id)->first();

        if (empty($uploaded_file)) {
            return Response::json(['message' => 'Sorry file does not exist'], 400);
        }

        $file_path = $this->upload_path . '/' . $uploaded_file->filename;

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        if (!empty($uploaded_file)) {
            $uploaded_file->delete();
        }

        return Response::json(['message' => 'File successfully delete'], 200);
    }

    public function download()
    {
        $ref = Input::get('__token');

        $key = (int) UriEncode::decrypt($ref);

        if ($key == 0)
            abort(403, 'Unauthorized action.');

        $documentModel = Documents::where('id', $key)->first();

        if (!isset($documentModel->id))
            abort(500, 'Not Found.');

        $file = public_path() . '/uploads/' . $documentModel->filename;

        return response()->download($file, $documentModel->original_name);
    }

    public function get_documents()
    {

        $draw   = $_POST['draw'];
        $offset = $_POST['start'];
        $limit  = $_POST['length'];
        $keyword = trim($_POST['search']['value']);

        $parent  = (int) $_POST['parent'];
        $from  = (int) $_POST['from'];

        $columns = [
            // datatable column index  => database column name
            0 => 'id',
            1 => 'title',
            2 => 'expiry_date',
            3 => 'id'
        ];

        $filterColumn = $columns[$_POST['order'][0]['column']];
        $filterOrder  = $_POST['order'][0]['dir'];

        //Eloquent Result
        $query = Documents::query()->where('from', $from)->where('parent_id', $parent);

        if ($keyword != "") {
            $query->where(function ($q) use ($keyword) {
                $q->orWhere('title', 'LIKE', '%' . $keyword . '%')
                    ->orWhere('original_name', 'LIKE', '%' . $keyword . '%');
            });
        }
        //Result
        $result = $query->skip($offset)->take($limit)->orderBy($filterColumn, $filterOrder)->get();

        $recordsTotal = $result->count();
        $recordsFiltered = $recordsTotal;
        $data['draw'] = $draw;
        $data['recordsTotal'] = $recordsTotal;
        $data['recordsFiltered'] = $recordsFiltered;
        $eachItemData = [];

        foreach ($result as $i => $eachItem) {
            $no = $i + $offset + 1;
            //Edit Button

            $actions = '<a title="Download" href="/document/download/?__token=' . UriEncode::encrypt($eachItem->id) . '"><i class="material-icons" >file_download</i></a>';
            $actions .= '<a title="Save" href="#" onclick="save_doc(' . $eachItem->id . ');"><i class="material-icons" >save</i></a>';
            $actions .= '<a title="Remove" href="#" onclick="remove_doc(' . $eachItem->id . ');"><i class="material-icons" >delete_sweep</i></a>';

            $eachItemData[] = [$no, '<input type="text" class="form-control" value="' . $eachItem->title . '" id="doc_title_' . $eachItem->id . '" />', '<input type="text" class="form-control datepicker" value="' . $eachItem->formated_expiry_date() . '" id="doc_exp_' . $eachItem->id . '" />', '<div class="text-center">' . $actions . '</div>'];
        }
        $data['data'] = $eachItemData;

        return response()->json($data);
    }

    public function update_document(Request $request)
    {
        //Input Data
        $data = $request->all();

        if ($data['expiry_date'] != '')
            $data['expiry_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $data['expiry_date'])));

        //Validation of Request
        $validator = \Validator::make($data, [
            '_ref' => ['required', 'integer', 'gt:0'],
            'expiry_date' => ['nullable', 'date']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->messages(), 200);
        } else {
            $model = Documents::find($data['_ref']);
            $model->title = $data['title'];
            $model->expiry_date = $data['expiry_date'];
            $model->save();

            return response()->json(['message' => 'success']);
        }
    }

    public function document_all($from=0, $parent=0)
    {

        $parent_id = UriEncode::decrypt($parent);

        $models = [
            1 => new \App\models\Buildings(),
            2 => new \App\models\Flats(),
            3 => new \App\models\Tenants()
        ];

        if (!in_array($from, [1, 2, 3]))
            abort(403, 'Unauthorized action.');

        if ((int) $parent_id > 0) {
            $model = $models[$from]::where('id', $parent_id)->first();

            if (!isset($model))
                abort(403, 'Unauthorized action.');

            $view = 'document.all';
            return view($view, ['model' => $model, 'from' => $from]);
        } else {
            abort(403, 'Unauthorized action.');
        }
    }
}
