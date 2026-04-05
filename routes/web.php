<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;

Route::get('/',[EventController::class,'index']);
Route::post('/event',[EventController::class,'store']);
Route::put('/event/update/{id}',[EventController::class,'update']);
Route::get('/download',[EventController::class,'download']);
Route::delete('/event/delete/{id}', [EventController::class, 'destroy']);


