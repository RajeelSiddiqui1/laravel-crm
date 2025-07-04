<?php

use App\Http\Controllers\Employee;
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
    Route::get('/project-manager/token-login/{token}', 'tokenLogin')->name('project_manager.token.login');
    Route::get('/project-manager/logout', 'logout')->name('project_manager.logout');
    Route::middleware('check.roles')->group(
        function () {
            Route::get('/project-manager/home', 'home')->name('project_manager.home');
            Route::get('/project-manager/profile', 'profile_view')->name('project_manager.profile');
            Route::put('/project-manager/profile', action: 'updateProfile')->name('project_manager.profile.update');
        }
    );

    Route::middleware('check.roles')->group(function () {
        Route::get('/project-manager/home', 'home')->name('project_manager.home');
    });
});

Route::controller(ProjectOnwer::class)->group(function () {
    Route::get('/project-owner/login', 'loginview')->name('project_owner.login');
    Route::post('/project-owner/login', 'login')->name('project_owner.login.post');
    Route::get('/project-owner/logout', 'logout')->name('project_owner.logout');

    Route::middleware('check.roles')->group(function () {
        Route::get('/project-owner/home', 'home')->name('project_owner.home');
        Route::get('/project-owner/project-manager', 'project_manager_view')->name('project_owner.project_manager_view');
        Route::get('/project-owner/team-leads', 'teamLeadsView')->name('project_owner.team_lead_view');
        Route::get('/project-owner/employees', 'employee_view')->name('project_owner.employee_view');
        Route::get('/project-owner/departments', 'department_view')->name('project_owner.departments');
        Route::get('/project-owner/department/create', 'department_create_view')->name('department.create');
        Route::post('/project-owner/department/create', 'department_create')->name('department.create.post');
        Route::get('/project-owner/department/edit/{id}', 'department_edit_view')->name('department.edit');
        Route::post('/project-owner/department/edit/{id}', 'department_update')->name('department.edit.post');
        Route::delete('/project-owner/department/delete/{id}', 'department_delete')->name('department.delete');
        Route::get('/project-owner/task', 'task_view')->name('project_owner.task');
        Route::get('/project-owner/task_detail/{id}', 'task_detail')->name('project_owner.task_detail');
        Route::get('/project-owner/create', 'tasks_createview')->name('project_owner.tasks.createview');
        Route::post('/project-owner/tasks/create', 'tasks_create')->name('project_manager.tasks.post');
        Route::get('/project-manager/tasks/{id}/edit',  'edit')->name('project_manager.tasks.edit');
        Route::put('/project-manager/tasks/{id}/update',  'update')->name('project_manager.tasks.update');
        Route::delete('/project-manager/tasks/{id}/delete', 'destroy')->name('project_manager.tasks.delete');
    });
});

Route::controller(TeamLead::class)->group(function () {
    Route::get('/team-lead/register', 'resgisterview')->name('team_lead.register');
    Route::post('/team-lead/register', 'register')->name('team_lead.register.post');
    Route::get('/team-lead/login', 'loginView')->name('team_lead.login');
    Route::post('/team-lead/login', 'login')->name('team_lead.login.post');
    Route::get('/team-lead/login/token/{token}', 'tokenLogin')->name('team_lead.token.login');
    Route::get('/team-lead/logout', 'logout')->name('team_lead.logout');

    Route::middleware('check.roles')->group(function () {
        Route::get('/team-lead/home', 'home')->name('team_lead.home');
        Route::get('/team-lead/profile', 'profile_view')->name('team_lead.profile');
        Route::put('/team-lead/profile', action: 'updateProfile')->name('team_lead.profile.update');
    });
});


Route::controller(Employee::class)->group(function () {
    Route::get('/employee/register', 'resgisterview')->name('employee.register');
    Route::post('/employee/register', 'register')->name('employee.register.post');
    Route::get('/employee/login', 'loginView')->name('employee.login');
    Route::post('/employee/login', 'login')->name('employee.login.post');
    Route::get('/employee/login/token/{token}', 'tokenLogin')->name('employee.token.login');
    Route::get('/employee/logout', 'logout')->name('employee.logout');

    Route::middleware('check.roles')->group(function () {
        Route::get('/employee/home', 'home')->name('employee.home');
        Route::get('/employee/profile', 'profile_view')->name('employee.profile');
        Route::put('/employee/profile', action: 'updateProfile')->name('employee.profile.update');
    });
});
