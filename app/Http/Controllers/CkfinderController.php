<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CkfinderController extends Controller
{
    public function browser(Request $request)
    {
        $path = public_path('uploads');
        $files = File::files($path);

        $images = [];

        foreach ($files as $file) {
            $images[] = [
                'url' => url('uploads/' . $file->getFilename()),
                'name' => $file->getFilename(),
                'size' => $file->getSize()
            ];
        }

        return response()->json($images);
    }
}
