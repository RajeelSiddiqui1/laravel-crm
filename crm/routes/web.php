<?php

use App\Http\Controllers\Employee;
use App\Http\Controllers\EmployeeTasks;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProjectOnwer;
use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectManager;
use App\Http\Controllers\TeamLeadController;
use App\Http\Middleware\CheckUserRoles;
use App\Http\Controllers\Chat\GroupChatController;

// Public route for the welcome page, accessible to unauthenticated users
Route::view('/', 'welcome')->name('welcome');

// Project Manager Routes
Route::controller(ProjectManager::class)->group(function () {
    // Public routes (no authentication required)
    Route::get('/project-manager/register', 'resgisterview')->name('project_manager.register');
    Route::post('/project-manager/register', 'register')->name('project_manager.register.post');
    Route::get('/project-manager/login', 'loginview')->name('project_manager.login');
    Route::post('/project-manager/login', 'login')->name('project_manager.login.post');
    Route::get('/project-manager/token-login/{token}', 'tokenLogin')->name('project_manager.token.login');
    Route::get('/project-manager/logout', 'logout')->name('project_manager.logout');

    // Protected routes (require authentication via CheckUserRoles middleware)
    Route::middleware('check.roles')->group(function () {
        Route::get('/project-manager/home', 'home')->name('project_manager.home');
        Route::get('/project-manager/profile', 'profile_view')->name('project_manager.profile');
        Route::put('/project-manager/profile', 'updateProfile')->name('project_manager.profile.update');
        Route::get('/project-manager/owner-tasks', 'onwertask')->name('project_manager.tasks');
        Route::post('/project-manager/tasks/{task}/assign-team-lead', 'assignTeamLead')->name('project_manager.tasks.assign_team_lead');
        Route::get('/project-manager/owner-tasks/{id}/detail', 'onwertask_detail')->name('project_manager.tasks.detail');
        Route::get('/project-manager/notifications', 'notifications')->name('project_manager.notifications');
        Route::get('/project-manager/notifications/{id}', 'viewNotification')->name('project_manager.notifications.view');
        Route::get('/project-manager/subtask_list', 'subtask_list')->name('project_manager.subtask');
        Route::get('/project-manager/subtask/detail/{id}', 'subtask_detail')->name('project_manager.subtask_detail');
        Route::post('/shared-task/{subtaskId}', 'store_shared_task')->name('shared-task.store');
        Route::get('/project-manager/mytasks', 'manager_task_list')->name('project_manager.mytask');
        Route::get('/project-manager/mytasks/detail/{id}', 'my_task_detail')->name('project_manager.my_task_detail');
        Route::get('/project-manager/mytasks/create/{id}', 'create_my_task')->name('project_manager.mytask_create');
        Route::post('/project-manager/mytasks/store/{id}', 'store_my_task')->name('project_manager.mytask_store');
        Route::get('/project-manager/owntasks/create/', 'create_own_task')->name('project_manager.owntask_create');
        Route::post('/project-manager/owntasks/store/', 'store_own_task')->name('project_manager.owntask_store');
        Route::get('/project-manager/mytask/edit/{id}', 'mytask_edit')->name('project_manager.mytask_edit');
        Route::put('/project-manager/mytask/edit/{id}', 'mytask_update')->name('project_manager.mytask_update');
        Route::delete('/mytask/attachment/{id}', 'delete_attachment')->name('project_manager.delete_attachment');
        Route::delete('/project-manager/mytask/delete/{id}', 'mytask_destroy')->name('project_manager.mytask_destroy');
        Route::post('/my-tasks/share/{id}', 'share_task')->name('project_manager.share_task');
        Route::post('/project-manager/shared-tasks', 'shareTask')->name('project_manager.share_task');
        Route::patch('/project-manager/owner-tasks/{id}/status2', 'updateStatus2')->name('project_manager.update_status2');
        Route::patch('/project-manager/owner-tasks/{id}/status3', 'updateStatus3')->name('project_manager.update_status3');
        Route::get('/project-manager/employee/task/show/{id}', 'show_employee_task')->name('project_manager.show_employee_task.view');
        Route::get('/project-manager/employee/sharedtask/show/', 'shared_task_list')->name('project_manager.show_sharedtask_task.view');
        Route::get('/project-manager/shared-task/list', 'showSharedTasks')->name('project_manager.sharedtask.view');
        Route::post('/project-manager/shared-task/{id}/assign-teamlead', 'assign_teamlead_shared_task')->name('project_manager.assign_teamlead_shared_task');
        Route::get('project-manager/pos/{id}',  'showPos')->name('project-manager.shared.pos.detail');
        Route::get('project-manager/account/{id}',  'showAccount')->name('project-manager.shared.account.detail');
        Route::get('/project-manager/teamleads', 'teamleads')->name('project_manager.teamleads');
        Route::get('/project-manager/teamleads/create', 'create_teamlead_view')->name('project_manager.create_teamlead_view');
        Route::post('/project-manager/teamleads/create', 'create_teamlead')->name('project_manager.create_teamlead');
        Route::get('/project-manager/employee', 'employees')->name('project_manager.employee');
        Route::get('/project-manager/employee/create', 'create_employee_view')->name('project_manager.create_employee_view');
        Route::post('/project-manager/employee/create', 'create_employee')->name('project_manager.create_employee');
    });
});

// Project Owner Routes
Route::controller(ProjectOnwer::class)->group(function () {
    // Public routes (no authentication required)
    Route::get('/project-owner/login', 'loginview')->name('project_owner.login');
    Route::post('/project-owner/login', 'login')->name('project_owner.login.post');
    Route::get('/project-owner/logout', 'logout')->name('project_owner.logout');

    // Protected routes (require authentication)
    Route::middleware('check.roles')->group(function () {
        Route::get('/project-owner/home', 'home')->name('project_owner.home');
        Route::get('/project-owner/profile', 'profile_view')->name('project_owner.profile');
        Route::put('/project-owner/profile/update', 'profile_update')->name('project_owner.profile.update'); // Fixed typo in route path
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
        Route::get('/project-owner/get-managers/{id}', 'getManagersByDepartment');
        Route::post('/project-owner/tasks/create', 'tasks_create')->name('project_owner.tasks.post'); // Fixed route name
        Route::get('/project-owner/tasks/{id}/edit', 'edit')->name('project_owner.tasks.edit');
        Route::put('/project-owner/tasks/{id}/update', 'update')->name('project_owner.tasks.update');
        Route::delete('/project-owner/tasks/{id}/delete', 'destroy')->name('project_owner.tasks.delete');
        Route::get('/project-owner/task/full-details/{id}', 'taskFullDetails')->name('project_owner.task.details');
        Route::get('/project-owner/subtasks', 'subtask')->name('project_owner.subtask');
        Route::get('/project-owner/subtask/detail/{id}', 'subtask_detail')->name('project_owner.subtask.detail');
        Route::get('/project-owner/manager-tasks', 'allOwnerTasks')->name('project_owner.manager_tasks');
        Route::get('/project-managers/{departmentId}', 'getProjectManagers');
        Route::get('/project-owner/visitors', 'visitors')->name('project_owner.visitor'); // Fixed route path
        Route::get('/project-owner/create/visitor/view', 'create_visitor_view')->name('project_owner.create.visitor.view');
        Route::post('/project-owner/create/visitor', 'create_visitor')->name('project_owner.create.visitor');
        Route::get('/project-owner/manager-task/{id}', 'manager_task')->name('project_owner.tasks.view');
    });
});

// Team Lead Routes
Route::controller(TeamLeadController::class)->group(function () {
    // Public routes (no authentication required)
    Route::get('/team-lead/register', 'resgisterview')->name('team_lead.register');
    Route::post('/team-lead/register', 'register')->name('team_lead.register.post');
    Route::get('/team-lead/login', 'loginView')->name('team_lead.login');
    Route::post('/team-lead/login', 'login')->name('team_lead.login.post');
    Route::get('/team-lead/login/token/{token}', 'tokenLogin')->name('team_lead.token.login');
    Route::get('/team-lead/logout', 'logout')->name('team_lead.logout');

    // Protected routes (require authentication)
    Route::middleware('check.roles')->group(function () {
        Route::get('/team-lead/home', 'home')->name('team_lead.home');
        Route::get('/team-lead/profile', 'profile_view')->name('team_lead.profile');
        Route::put('/team-lead/profile', 'updateProfile')->name('team_lead.profile.update');
        Route::get('/team-lead/manager-tasks', 'manager_tasks')->name('team_lead.manager_tasks');
        Route::post('/team-lead/tasks/{task}/assign-employees', 'assignEmployees')->name('team_lead.tasks.assign_employees');
        Route::put('/team-lead/subtask-update/{id}', 'subtask_update')->name('team_lead.subtask.update');
        Route::put('/team-lead/tasks/{task}/update-status', 'updateStatus')->name('team_lead.tasks.update_status');
        Route::get('/team-lead/tasks/{id}/detail', 'manager_tasks_detail')->name('team_lead.task_detail');
        Route::get('/team-lead/task/{id}/subtask/create', 'subtask_create')->name('team_lead.subtask.create');
        Route::get('/team-lead/subtask/{id}/list', 'subtask_list')->name('team_lead.subtask.list');
        Route::post('/team-lead/subtask/store', 'subtask_store')->name('team_lead.subtask.store');
        Route::get('/team-lead/subtask/{id}/view', 'subtask_view')->name('team_lead.subtask.view');
        Route::get('/team-lead/subtask/detail/{id}', 'subtask_detail')->name('team_lead.subtask.detail');
        Route::get('/team-lead/subtask/show-more/{id}', 'subtask_show_more')->name('team_subtask_show_more');
        Route::get('/team-lead/subtask/{id}/edit', 'subtask_edit')->name('team_lead.subtask.edit');
        Route::put('/subtask/update/{id}', 'subtask_update')->name('team_lead.subtask.update');
        Route::delete('/team-lead/subtask/{id}/delete', 'subtask_delete')->name('team_lead.subtask.delete');
        Route::patch('/subtask/{id}/status', 'subtask_update_status')->name('team_lead.subtask.update_status');
        Route::get('/team-lead/subtask/{id}', 'EmployeeSubtasks')->name('teamlead.employee_subtask.details');
        Route::get('/team-lead/shared-task/list', 'showSharedTasks')->name('team-lead.sharedtask.view');
        Route::post('/team-lead/shared-task/{id}/assign-employee', 'assign_employee_shared_task')->name('team-lead.assign_employee_shared_task');
        Route::get('team-lead/pos/{id}',  'showPos')->name('team-lead.shared.pos.detail');
        Route::get('team-lead/account/{id}',  'showAccount')->name('team-lead.shared.account.detail');
        Route::get('/team-lead/employees/', 'fetch_employee')->name('team_lead.employees');
        Route::get('/team-lead/message/{id}/employee', 'message_employee')->name('team_lead.message.employee');
        Route::post('/team-lead/message/send', 'send_message')->name('team_lead.message.send');
    });
});

// Employee Routes
Route::controller(Employee::class)->group(function () {
    // Public routes (no authentication required)
    Route::get('/employee/register', 'resgisterview')->name('employee.register');
    Route::post('/employee/register', 'register')->name('employee.register.post');
    Route::get('/employee/login', 'loginView')->name('employee.login');
    Route::post('/employee/login', 'login')->name('employee.login.post');
    Route::get('/employee/login/token/{token}', 'tokenLogin')->name('employee.token.login');
    Route::get('/employee/logout', 'logout')->name('employee.logout');

    // Protected routes (require authentication)
    Route::middleware('check.roles')->group(function () {
        Route::get('/employee/home', 'home')->name('employee.home');
        Route::get('/employee/profile', 'profile_view')->name('employee.profile');
        Route::put('/employee/profile', 'updateProfile')->name('employee.profile.update');
        Route::get('/employee/teamlead-tasks', 'team_task_view')->name('employee.teamlead_task');
        Route::get('/employee/task-detail/{id}', 'teamlead_task_detail')->name('employee.task_detail');
        Route::get('/employee/subtasks', 'subtasks_list')->name('employee.subtasks');
        Route::patch('/employee/subtasks/status/update/{id}', 'subtask_status_update')->name('employee.subtask_status_update');
        Route::patch('/employee/subtask-update/{id}', 'subtask_status_update')->name('employee.subtask.update_status');
        Route::get('/employee/subtask/{subtaskId}', 'employee_task_view')->name('employee.subtask.view');
        Route::put('/employee/subtasks/{id}/update', 'updateSubtask')->name('employee.subtask.update');
        Route::get('/employee/shared-task/list', 'showSharedTasks')->name('employee.sharedtask.view');
        Route::get('employee/lead/info/{id}',  'task_info')->name('employee.task_info.view');
        Route::put('employee/lead/info/{id}',  'update_task_info')->name('employee.task_info.post');
        Route::get('/employee/pos/{id}',  'showPos')->name('employee.shared.pos.detail');
        Route::get('/employee/account/{id}',  'showAccount')->name('employee.shared.account.detail');
        Route::get('/employee/team-lead/', 'fetch_team_leads')->name('team_lead.teamleads');
        Route::get('/employee/message/{id}/team-lead', 'message_teamlead')->name('employee.message.teamlead');
        Route::post('/employee/message/send', 'send_message')->name('employee.message.send');
    });
});

Route::controller(VisitorController::class)->group(function () {
    Route::get('/visitor/login', 'loginview')->name('visitor.login');
    Route::post('/visitor/login', 'login')->name('visitor.login.post');
    Route::post('/visitor/logout', 'logout')->name('visitor.logout');

    Route::middleware('check.roles')->group(function () {
        Route::get('/visitor/home', 'home')->name('visitor.home');
        Route::put('/visitor/profile', 'updateProfile')->name('visitor.profile.update');
        Route::get('/visitor/visit/list', 'showSharedTasks')->name('visitor.sharedtask.view');
        Route::get('shared-tasks/lead/info/{id}',  'lead_info')->name('visitor.lead_info.view');
        Route::put('shared-tasks/lead/info/{id}',  'update_lead_info')->name('visitor.lead_info.post');
        // Route::get('shared-tasks/pos/{id}',  'showPos')->name('visitor.shared.pos.detail');
        // Route::get('shared-tasks/account/{id}',  'showAccount')->name('visitor.shared.account.detail');
    });
});


// Group Chat Routes (protected by middleware)
Route::middleware('check.roles')->group(function () {
    Route::get('/chat/group/{id}', [GroupChatController::class, 'index'])->name('chat.group');
    Route::post('/chat/group/send', [GroupChatController::class, 'send'])->name('chat.group.send');
});

// Notification Routes (protected by middleware)
Route::middleware('check.roles')->group(function () {
    Route::get('/notifications', [NotificationController::class, 'allNotifications'])->name('notifications.index');
    Route::get('/admin/notifications', [NotificationController::class, 'showAllForAdmin'])->name('notifications.admin');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
});
