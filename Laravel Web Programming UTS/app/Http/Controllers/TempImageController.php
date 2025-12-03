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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $folder = 'temp_uploads'; 
            $filename = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            
            $path = $image->storeAs($folder, $filename, 'local'); 
            $absolutePath = Storage::disk('local')->path($path);
            
            $exifData = @exif_read_data($absolutePath); 
            
            if (!empty($exifData)) {
                $message = "Image uploaded successfully. Set dimensions below to resize.";
            } else {
                $message = "Image uploaded successfully. No EXIF metadata detected. Set dimensions below to resize.";
            }
            
            $request->session()->put('temp_image_path', $path);
            $request->session()->put('temp_image_exif_data', $exifData); 
            $request->session()->forget('show_image_metadata'); 

            
            return back()->with('success', $message);
        }

        return back()->with('error', 'No image file uploaded.');
    }

    public function manipulateImage(Request $request)
    {
       
        $request->validate([
            'custom_width' => 'required|integer|min:1|max:3000',
            'custom_height' => 'required|integer|min:1|max:3000',
        ]);

        $path = $request->session()->get('temp_image_path');
        if (!$path) {
            return back()->with('error', 'No temporary image found to manipulate.');
        }

        $forcedWidth = (int) $request->input('custom_width');
        $forcedHeight = (int) $request->input('custom_height');
        
        $absolutePath = Storage::disk('local')->path($path);
        
        [$originalWidth, $originalHeight, $imageType] = @getimagesize($absolutePath);
        
        $imageResource = match ($imageType) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($absolutePath),
            IMAGETYPE_PNG  => imagecreatefrompng($absolutePath),
            IMAGETYPE_GIF  => imagecreatefromgif($absolutePath),
            default => null,
        };
        
        if ($imageResource) {

            $newImage = imagecreatetruecolor($forcedWidth, $forcedHeight);

            if ($imageType == IMAGETYPE_PNG) {
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
            }
 
            imagecopyresampled(
                $newImage, $imageResource, 
                0, 0, 0, 0, 
                $forcedWidth, $forcedHeight,  
                $originalWidth, $originalHeight
            );
            
            if ($imageType == IMAGETYPE_JPEG) {
                imagejpeg($newImage, $absolutePath, 90);
            } elseif ($imageType == IMAGETYPE_PNG) {
                imagepng($newImage, $absolutePath);
            } elseif ($imageType == IMAGETYPE_GIF) {
                imagegif($newImage, $absolutePath);
            }
            
            imagedestroy($imageResource);
            imagedestroy($newImage);
            
            $exifData = @exif_read_data($absolutePath);
            $request->session()->put('temp_image_exif_data', $exifData);
        }

        return back()->with('success', "Image successfully resized to {$forcedWidth}x{$forcedHeight}.");
    }
}