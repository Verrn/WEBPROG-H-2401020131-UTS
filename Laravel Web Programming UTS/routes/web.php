<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TempImageController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\FinalizationController;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Storage;

Route::get('/', function() {
    return view('welcome');
});

Route::post('/temp/upload', [TempImageController::class, 'tempStore'])->name('image.temp.upload');
Route::post('/metadata/toggle', [MetadataController::class, 'toggleDisplay'])->name('metadata.toggle');
Route::post('/image/manipulate', [TempImageController::class, 'manipulateImage'])->name('image.manipulate');
Route::post('/image/finalize', [FinalizationController::class, 'finalizePost'])->name('image.finalize');
Route::get('/temp/view/{path}', function ($path) {
    
    if (!str_starts_with($path, 'temp_uploads/')) {
        abort(404);
    }

    if (Storage::disk('local')->exists($path)) {
        return Storage::disk('local')->response($path);
    }

    abort(404);
})->where('path', '.*')->name('image.temp.view');

Route::get('/download/trigger', function (Request $request) {
    
    $finalPath = session('download_path');
    
    if ($finalPath) {
        
        if (Storage::disk('local')->exists($finalPath)) {
            
            session()->forget('download_path');
            
            return Storage::disk('local')->download($finalPath, basename($finalPath));
        }
    }
    
    return redirect('/')->with('error', 'Could not locate the file for download.');
    
})->name('download.trigger');