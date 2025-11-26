<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class TempImageController extends Controller
{
    public function tempStore(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $folder = 'temp_uploads'; 
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            $path = $image->storeAs($folder, $filename, 'local'); 
            
            
            $absolutePath = Storage::disk('local')->path($path);
            
            
            $exifData = @exif_read_data($absolutePath); 
            
            
            if (!empty($exifData)) {
                $message = "Image uploaded successfully. Metadata found. Click 'Show Metadata' to view details.";
            } else {
                
                $message = "Image uploaded successfully, but no EXIF metadata was detected.";
            }
            
           
            $request->session()->put('temp_image_path', $path);
            $request->session()->put('temp_image_exif_data', $exifData); 

            
            return back()->with('success', $message);
        }

        return back()->with('error', 'No image file uploaded.');
    }
}