<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;

class ImageController extends Controller
{

    public function create()
    {
        return view('images.create');
    }

    public function store(Request $request)
    {
        $path = $request->file('uploadedFile')->store('images', 's3');

        Storage::disk('s3')->setVisibility($path, 'public');

        $image = Image::create([
            'filename' => basename($path),
            'url' => Storage::disk('s3')->url($path)
        ]);

        // $id = 3;
		// $attachment = Image::find($id);

		// $headers = [

		//     'Content-Type'        => 'application/jpeg',

		//     'Content-Disposition' => 'attachment; filename="'. $attachment->name .'"',

		// ];

		// return \Response::make(Storage::disk('s3')->get('https://planaji-files-bucket.s3.ap-southeast-2.amazonaws.com/images/Z6AqeuQa6oppgZWZ1wPo9aDHpAtmjFxEcPSbGUZs.png'), 200, $headers);
    
        // return $image;

        $file = base64_decode('Z6AqeuQa6oppgZWZ1wPo9aDHpAtmjFxEcPSbGUZs.png');
        $name = basename('Z6AqeuQa6oppgZWZ1wPo9aDHpAtmjFxEcPSbGUZs.png');
        Storage::disk('s3')->download($file, $name);

        // $exists = Storage::disk('s3')->exists('images/Z6AqeuQa6oppgZWZ1wPo9aDHpAtmjFxEcPSbGUZs.png');
        // Storage::disk('s3')->get('images/Z6AqeuQa6oppgZWZ1wPo9aDHpAtmjFxEcPSbGUZs.png');
        return back()->withSuccess('File downloaded successfully');
        
    }

    public function show(Image $image)
    {
        // return $image->url;
        return Storage::disk('s3')->response('images/' . $image->filename);
    }

}