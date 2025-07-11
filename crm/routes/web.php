<?php

use App\Http\Controllers\Employee;
use App\Http\Controllers\ProjectOnwer;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectManager;
use App\Http\Controllers\TeamLeadController;
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
            Route::get('/project-manager/owner-tasks', 'onwertask')->name('project_manager.tasks');
            Route::post(
                '/project-manager/tasks/{task}/assign-team-lead',
                'assignTeamLead'
            )
                ->name('project_manager.tasks.assign_team_lead');
            Route::get('project-manager/owner-tasks/{id}/detail', 'onwertask_detail')->name('project_manager.tasks.detail');
            Route::get('project-manager/notifications',  'notifications')->name('project_manager.notifications');
            Route::get('project-manager/notifications/{id}',  'viewNotification')->name('project_manager.notifications.view');
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
        Route::get('/project-owner/tasks/{id}/edit',  'edit')->name('project_owner.tasks.edit');
        Route::put('/project-owner/tasks/{id}/update',  'update')->name('project_owner.tasks.update');
        Route::delete('/project-owner/tasks/{id}/delete', 'destroy')->name('project_owner.tasks.delete');
        Route::get('project-owner/task/full-details/{id}', 'taskFullDetails')->name('project_owner.task.details');

    });
});

Route::controller(TeamLeadController::class)->group(function () {
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
        Route::get('/team-lead/manager-tasks', 'manager_tasks')->name('team_lead.manager_tasks');
        Route::post('/team-lead/tasks/{task}/assign-employees', 'assignEmployees')->name('team_lead.tasks.assign_employees');
        Route::put('/team-lead/tasks/{task}/update-status', 'updateStatus')->name('team_lead.tasks.update_status');
        Route::get('/team-lead/tasks/{id}/detail', 'manager_tasks_detail')->name('team_lead.task_detail');
        Route::get('/team-lead/task/{task}/subtask/create', 'subtask_create')->name('team_lead.subtask.create');
        Route::get('team-lead/subtask/{id}/list', 'subtask_list')->name('team_lead.subtask.list');
        Route::post('/team-lead/subtask/store', 'subtask_store')->name('team_lead.subtask.store');
        Route::get('/team-lead/subtask/{id}/view',  'subtask_view')->name('team_lead.subtask.detail');
        Route::get('/team-lead/subtask/{id}/edit',  'subtask_edit')->name('team_lead.subtask.edit');
        Route::put('/team-lead/update/{id}/task', 'subtask_update')->name('employee.subtask.update');
        Route::delete('team-lead/subtask/{id}/delete', 'subtask_delete')->name('team_lead.subtask.delete');
        Route::patch('subtask/{id}/status', 'subtask_update_status')->name('team_lead.subtask.update_status');
        Route::get('team-lead/employees/', 'fetch_employee')->name('team_lead.employees');
        Route::get('team-lead/message/{id}/employee', 'message_employee')->name('team_lead.message.employee');
        Route::post('team-lead/message/send',  'send_message')
            ->name('team_lead.message.send');
            
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
        Route::put('/employee/profile', 'updateProfile')->name('employee.profile.update');
        Route::get('/employee/teamlead-tasks', 'team_task_view')->name('employee.teamlead_task');
        Route::get('/employee/task-detail/{id}', 'teamlead_task_detail')->name('employee.task_detail');
        Route::get('/employee/subtasks',  'subtasks_list')->name('employee.subtasks');
        Route::get('/employee/subtask/{id}/edit', 'edit_subtask')->name('employee.subtask.edit');
        Route::put('/employee/subtask/{id}/update', 'update_subtask')->name('employee.subtask.update');
        Route::get('employee/team-lead/', 'fetch_team_leads')->name('team_lead.teamleads');
        Route::get('employee/message/{id}/team-lead', 'message_teamlead')->name('employee.message.teamlead');
        Route::post('employee/message/send',  'send_message')
            ->name('employee.message.send');
    });
});
