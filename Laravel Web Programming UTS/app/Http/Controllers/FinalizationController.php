<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Storage;
use App\Models\Post; 


class FinalizationController extends Controller
{
    public function finalizePost(Request $request)
    {
        
        $tempPath = $request->session()->get('temp_image_path');

        if ($tempPath) {
            
            $finalPath = 'post_images/' . basename($tempPath);

            
            Storage::move($tempPath, $finalPath);
            
           
            $request->session()->forget('temp_image_path');

            
            $post = Post::create([
                
                'image_path' => $finalPath,
            ]);

            return redirect('/posts/' . $post->id)->with('success', 'Post saved!');
        }
        
        
        return back()->with('error', 'Image not found.');
    }
}