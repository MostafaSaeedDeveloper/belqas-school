<?php

use Illuminate\Support\Facades\Route;

Route::get('/users', function () {
    return response('Users index');
})->name('users.index');

