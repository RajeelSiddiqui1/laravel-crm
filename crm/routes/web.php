<?php

use App\Http\Controllers\ProjectOnwer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectManager;
use App\Http\Controllers\TeamLead;


use App\Http\Middleware\CheckUserRoles;

Route::view('/',  'welcome')->name('welcome');

Route::controller(ProjectManager::class)->group(function () {
    Route::get('/project-manager/register', 'resgisterview')->name('project_manager.register');
    Route::post('/project-manager/register', 'register')->name('project_manager.register.post');
    Route::get('/project-manager/login', 'loginview')->name('project_manager.login');
    Route::post('/project-manager/login', 'login')->name('project_manager.login.post');
    Route::get('/project-manager/logout', 'logout')->name('project_manager.logout');
    Route::get('/project-manager/token-login/{token}', 'tokenLogin')->name('project_manager.token.login');

    Route::middleware('check.roles')->group(function () {
        Route::get('/project-manager/home', 'home')->name('project_manager.home');
    });
});

Route::controller(ProjectOnwer::class)->group(function () {
    Route::get('/project-onwer/login', 'loginview')->name('project_owner.login');
    Route::post('/project-onwer/login', 'login')->name('project_owner.login.post');
    Route::get('/project-onwer/logout', 'logout')->name('project_owner.logout');

    Route::middleware('check.roles')->group(function () {
        Route::get('/project-onwer/home', 'home')->name('project_owner.home');
        Route::get('/project-owner/project-manager', 'project_manager_view')->name('project_owner.project_manager_view');
        Route::get('/project-owner/team-leads', 'teamLeadsView')->name('project_owner.team_lead_view');
        Route::get('/project-owner/departments', 'department_view')->name('project_owner.departments');
        Route::get('/project-owner/department/create', 'department_create_view')->name('department.create');
        Route::post('/project-owner/department/create', 'department_create')->name('department.create.post');
        Route::get('/project-owner/department/edit/{id}', 'department_edit_view')->name('department.edit');
        Route::post('/project-owner/department/edit/{id}', 'department_update')->name('department.edit.post');
        Route::delete('/project-owner/department/delete/{id}', 'department_delete')->name('department.delete');
    });
});

Route::controller(TeamLead::class)->group(function () {
    Route::get('/team-lead/register', 'resgisterview')->name('team_lead.register');
    Route::post('/team-lead/register', 'register')->name('team_lead.register.post');
    Route::get('/team-lead/login', 'loginView')->name('team_lead.login');
    Route::post('/team-lead/login', 'login')->name('team_lead.login.post');
    Route::get('/team-lead/login/token/{token}', 'tokenLogin')->name('team_lead.token.login');
    Route::post('/team-lead/logout', 'logout')->name('team_lead.logout');

    Route::middleware('check.roles')->group(function () {
        Route::get('/team-lead/home', 'home')->name('team_lead.home');
    });
});
    

