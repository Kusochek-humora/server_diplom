<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/projects',[\App\Http\Controllers\ProjectController::class,'index']);
Route::post('/project/create',[\App\Http\Controllers\ProjectController::class,'store']);
Route::post('/project/{id}',[\App\Http\Controllers\ProjectController::class,'show']);

Route::post('/application/create',[\App\Http\Controllers\ApplicationController::class,'store']);
Route::get('/applications',[\App\Http\Controllers\ApplicationController::class,'index']);
Route::post('/application/{id}',[\App\Http\Controllers\ApplicationController::class,'show']);

Route::get('/services',[\App\Http\Controllers\ServiceController::class,'index']);
Route::post('/service/create',[\App\Http\Controllers\ServiceController::class,'store']);
Route::post('/service/{id}',[\App\Http\Controllers\ServiceController::class,'show']);
Route::post('/service/destroy/{id}',[\App\Http\Controllers\ServiceController::class,'destroy']);
Route::put('/services/{id}',[\App\Http\Controllers\ServiceController::class,'update']);


