<?php

use Illuminate\Support\Facades\Route;
use ClassicO\NovaMediaLibrary\Http\Controllers\Tool;

Route::post('/get', Tool::class . '@get');
Route::get('/private', Tool::class . '@private')->name('nml-private-file-admin');
Route::post('/upload', Tool::class . '@upload');
Route::post('/delete', Tool::class . '@delete');
Route::post('/update', Tool::class . '@update');
Route::post('/crop', Tool::class . '@crop');
Route::post('/folder/new', Tool::class . '@folderNew');
Route::post('/folder/del', Tool::class . '@folderDel');
Route::get('/folders', Tool::class . '@folders');
