<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app'); // nukreipia į resources/views/app.blade.php
});
