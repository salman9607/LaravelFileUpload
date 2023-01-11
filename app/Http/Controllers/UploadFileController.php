<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UploadFileController extends Controller
{
    public function store(Request $request)
    {
        if($request->hasFile('fileImage')) {
            $file_post = $request->file('fileImage');
            $file_extension = $file_post->extension();
            $file_name = $file_post->getClientOriginalName();
            $folder = uniqid() . '-' . now()->timestamp;

            $file_post->storeAs('images/tmp' . $folder, $file_name, 'public' );

            return $folder;
        }
        return '';
    }
}
