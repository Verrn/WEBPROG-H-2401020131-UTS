<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\infoTemanController;

Route::get('/', function() {
    return view('welcome');
});
Route::get ('/info', [infoTemanController::class, "index"])->name('infoteman');
Route::get ('/info/{id}/detail', [infoTemanController::class, "detail"])->name('infoteman.detail');
Route::get ('info/create',[infoTemanController::class, "create"])->name('infoteman.create');
Route::post('/info',[infoTemanController::class,'store'])->name('infoteman.store');