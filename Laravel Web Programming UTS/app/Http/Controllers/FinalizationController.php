<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FinalizationController extends Controller
{
    public function finalizePost(Request $request)
    {
        
        $tempPath = $request->session()->get('temp_image_path');
        
        $request->session()->forget(['show_image_metadata', 'temp_image_exif_data']);

        if ($tempPath) {
            
            $finalPath = 'post_images/' . basename($tempPath);
            
            Storage::disk('local')->move($tempPath, $finalPath, 'public'); 
            
            $request->session()->forget('temp_image_path');
            
            return redirect()
                ->route('download.trigger')
                ->with('download_path', $finalPath); 
        }
        return back()->with('error', 'Image path was lost from session or corrupted.');
    }
}