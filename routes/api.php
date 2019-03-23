<?php

use Illuminate\Support\Facades\Route;

$nml_class = \ClassicO\NovaMediaLibrary\NML_Controller::class;

Route::post('/get',     $nml_class.'@get');
Route::get('/single',   $nml_class.'@single');
Route::post('/upload',  $nml_class.'@upload');
Route::post('/delete',  $nml_class.'@delete');
Route::post('/update',  $nml_class.'@update');
