<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return ['Laravel' => app()->version()];
});

require __DIR__.'/auth.php';
