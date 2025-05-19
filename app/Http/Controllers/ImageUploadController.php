<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    // public function upload(Request $request)
    // {
    //     if ($request->hasFile('upload')) {
    //         $file = $request->file('upload');
    //         $filename = time().'_'.$file->getClientOriginalName();
    //         $path = $file->storeAs('uploads', $filename, 'public');

    //         return response()->json([
    //             'uploaded' => 1,
    //             'fileName' => $filename,
    //             'url' => '/storage/' . $path
    //         ]);
    //     }

    //     return response()->json(['uploaded' => 0]);
    // }
   
    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->move(public_path('uploads'), $filename);

            $url = url('uploads/' . $filename);

            return response()->json([
                'uploaded' => 1,
                'fileName' => $filename,
                'url' => $url,
            ]);
        } else {
            return response()->json([
                'uploaded' => 0,
                'error' => [
                    'message' => 'Không tìm thấy file upload!'
                ]
            ]);
        }
    }
    
}
