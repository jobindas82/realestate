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


    public function building()
    {
        $queryPrams = Input::all();

        $parent_id = isset($queryPrams['__uuid']) ? UriEncode::decrypt($queryPrams['__uuid']) : 0;

        $from = 1;

        if ((int) $parent_id > 0) {
            $buildingModel = \App\models\Buildings::where('id', $parent_id)->first();
            if (!isset($buildingModel))
                abort(403, 'Unauthorized action.');
        } else
            abort(403, 'Unauthorized action.');

        return view('document.building', ['from' => $from, 'parent' => $parent_id, 'title' => 'Upload Documents for Building ' . $buildingModel->name]);
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

        $file = public_path() . '/uploads/'. $documentModel->filename;

        return response()->download($file, $documentModel->original_name);
    }
}
