<?php

use Illuminate\Support\Facades\Route;
use ClassicO\NovaMediaLibrary\Core\Controller;

Route::post('/get',     Controller::class . '@get');
Route::post('/upload',  Controller::class . '@upload');
Route::post('/crop',    Controller::class . '@crop');
Route::post('/delete',  Controller::class . '@delete');
Route::post('/update',  Controller::class . '@update');
