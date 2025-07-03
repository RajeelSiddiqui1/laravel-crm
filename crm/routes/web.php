<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectManager;

Route::get('/', function () {
    return view('welcome');
});


Route::controller(ProjectManager::class)->group(function () {
    Route::get('/project-manager/register', 'resgisterview')->name('project_manager.register');
    Route::post('/project-manager/register', 'register')->name('project_manager.register.post');
    Route::get('/project-manager/login', 'loginview')->name('project_manager.login');
    Route::post('/project-manager/login', 'login')->name('project_manager.login.post');
});