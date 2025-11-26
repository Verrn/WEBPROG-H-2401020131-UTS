<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MetadataController extends Controller
{
    public function toggleDisplay(Request $request)
    {
        
        $shouldShow = $request->session()->get('show_image_metadata', false);
        
        
        $request->session()->put('show_image_metadata', !$shouldShow);
        
        return back(); 
    }
}