@include('sweetalert2::index')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashtreme Admin</title>

    <link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/pace.min.js') }}"></script>
    <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
    <link href="{{ asset('assets/plugins/vectormap/jquery-jvectormap-2.0.2.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/sidebar-menu.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app-style.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    @yield('styles')
    <style>
        body {
            background: url('https://source.unsplash.com/random/1920x1080/?abstract') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Inter', sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
        }

        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }



        .user-profile-sidebar {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px 0;
            margin-bottom: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .user-profile-sidebar .avatar img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #fff;
        }

        .user-profile-sidebar .media-body {
            text-align: center;
            margin-top: 10px;
        }

        .user-profile-sidebar .user-title {
            color: #fff;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .user-profile-sidebar .user-subtitle {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.85rem;
        }
    </style>

</head>

<body class="bg-theme bg-theme1">
    <div id="particles-js"></div>
    <div id="wrapper">


        @if (Auth::guard('project_manager')->check())
            @php
                $manager = Auth::guard('project_manager')->user();
                $managerDeptIds = is_array($manager->department_ids)
                    ? $manager->department_ids
                    : json_decode($manager->department_ids, true);

                $managerDepartments = \App\Models\Department::whereIn('id', $managerDeptIds)->pluck('name')->toArray();
            @endphp
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="{{ url(path: '/project-manager/home/') }}">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Project Manager</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="user-profile-sidebar">
                        <div class="avatar">
                            <img src="{{ asset('images/project_managers/' . Auth::guard('project_manager')->user()->image) }}"
                                class="img-circle" alt="user avatar">
                        </div>
                        <div class="media-body">
                            <h6 class="mt-2 user-title">
                                {{ Auth::guard('project_manager')->user()->name }}</h6>
                            <p class="user-subtitle">
                                {{ Auth::guard('project_manager')->user()->email }}</p>
                        </div>
                    </li>
                    <li class="sidebar-header">MAIN NAVIGATION</li>
                    <li>
                        <a href="{{ url('/project-manager/profile') }}">
                            <i class="zmdi zmdi-account-circle mr-2"></i> <span>Profile</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('project_manager.logout') }}">
                            <i class="zmdi zmdi-power mr-2"></i> <span>Logout</span>
                        </a>
                    </li>
                    @if (in_array('Visitor', $managerDepartments))
                        <li>
                            <a href="{{ route('project_manager.sharedtask.view') }}">
                                <i class="zmdi zmdi-accounts mr-2"></i>
                                <span>Manager Shared Task List</span>
                            </a>
                        </li>
                    @else
                        <li>
                            <a href="{{ route('project_manager.tasks') }}">
                                <i class="zmdi zmdi-assignment mr-2"></i> <span>Owner Tasks</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('project_manager.mytask') }}">
                                <i class="zmdi zmdi-check-square mr-2"></i> <span>My Tasks</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('project_manager.subtask') }}">
                                <i class="zmdi zmdi-format-list-bulleted mr-2"></i> <span>Subtask</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('project_manager.teamleads') }}">
                                <i class="zmdi zmdi-accounts-alt mr-2"></i> <span>TeamLeads</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('project_manager.employee') }}">
                                <i class="zmdi zmdi-accounts mr-2"></i> <span>Employees</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('project_manager.show_sharedtask_task.view') }}">
                                <i class="zmdi zmdi-accounts mr-2"></i> <span>Shared Task</span>
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('project_manager.operation.task.view') }}">
                                <i class="zmdi zmdi-accounts mr-2"></i> <span>Signed Task</span>
                            </a>
                        </li>
                    @endif
                    {{-- 🔔 Notification Icon --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center"
                            href="{{ route('notifications.index') }}">
                            <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>
                            <span id="notif-badge"
                                class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm"
                                style="display:none;">
                                0
                            </span>
                        </a>
                    </li>

                    {{-- 🔊 Audio --}}
                    <audio id="notif-sound" preload="auto">
                        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
                    </audio>

                    {{-- 🌐 Pusher via CDN (no npm) --}}
                    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
                    <script>
                        /* 1. Identify current user/guard */
                        @php
                            $user = null;
                            $guard = null;
                            foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
                                if (Auth::guard($g)->check()) {
                                    $user = Auth::guard($g)->user();
                                    $guard = $g;
                                    break;
                                }
                            }
                        @endphp

                        /* 2. Initial unread count */
                        let unreadCount = 0;

                        function fetchCount() {
                            fetch('{{ route('notifications.unread-count') }}')
                                .then(r => r.json())
                                .then(d => {
                                    unreadCount = d.count;
                                    updateBadge();
                                });
                        }
                        fetchCount();

                        /* 3. Badge DOM update */
                        function updateBadge() {
                            const badge = document.getElementById('notif-badge');
                            badge.style.display = unreadCount > 0 ? 'block' : 'none';
                            badge.textContent = unreadCount > 99 ? '99+' : unreadCount;
                        }

                        /* 4. Connect to Pusher and listen */
                        @if ($user)
                            (function() {
                                const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
                                    cluster: '{{ env('PUSHER_APP_CLUSTER') }}'
                                });

                                const channel = pusher.subscribe('notifications.{{ $user->id }}.{{ $guard }}');

                                channel.bind('NewNotification', () => {
                                    unreadCount += 1;
                                    updateBadge();
                                    document.getElementById('notif-sound').play().catch(() => {});
                                });
                            })();
                        @endif
                    </script>
                </ul>
            </div>

            <header class="topbar-nav">
                <nav class="navbar navbar-expand fixed-top">
                    <ul class="navbar-nav mr-auto align-items-center">
                        <li class="nav-item"><a class="nav-link toggle-menu" href="javascript:void();"><i
                                    class="icon-menu menu-icon"></i></a></li>
                        <li class="nav-item">
                            <form class="search-bar">
                                <input type="text" class="form-control" placeholder="Enter keywords">
                                <a href="javascript:void();"><i class="icon-magnifier"></i></a>
                            </form>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link position-relative d-flex align-items-center"
                                href="{{ route('project_manager.notifications') }}" aria-label="View notifications">
                                <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>

                                @if (isset($pmNotificationCount) && $pmNotificationCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                                        {{ $pmNotificationCount > 99 ? '99+' : $pmNotificationCount }}
                                        <span class="visually-hidden">unread notifications</span>
                                    </span>
                                @endif
                            </a>
                        </li>

                        @php
                            $user = null;
                            $guard = null;

                            foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
                                if (Auth::guard($g)->check()) {
                                    $user = Auth::guard($g)->user();
                                    $guard = $g;
                                    break;
                                }
                            }

                            $notificationCount = 0;

                            if ($user && $guard) {
                                $notificationCount = \App\Models\Notification::where('user_id', $user->id)
                                    ->where('user_type', $guard)
                                    ->where('is_read', false)
                                    ->count();
                            }
                        @endphp

                        <!-- 🔔 Notification Icon -->
                        <li class="nav-item">
                            <a class="nav-link position-relative d-flex align-items-center"
                                href="{{ route('notifications.index') }}"
                                @if ($notificationCount > 0) onclick="playNotificationSound()" @endif>
                                <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>
                                @if ($notificationCount > 0)
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                                        {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                        <span class="visually-hidden">unread notifications</span>
                                    </span>
                                @endif
                            </a>
                        </li>

                        <!-- 🔊 Audio Element -->
                        <audio id="notif-sound" preload="auto">
                            <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
                        </audio>

                        <!-- 📜 JS Script -->
                        <script>
                            function playNotificationSound() {
                                const audio = document.getElementById('notif-sound');
                                if (audio) {
                                    audio.currentTime = 0;
                                    audio.play().catch(err => {
                                        console.warn("Sound failed:", err);
                                    });
                                }
                            }
                        </script>
                    </ul>

                </nav>
            </header>
        @endif

        @if (Auth::guard('project_owner')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="index.html">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Dashtreme Admin</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="user-profile-sidebar">
                        <div class="avatar">
                            <img src="{{ Auth::guard('project_owner')->user()->image ? asset('images/project_owner/' . Auth::guard('project_owner')->user()->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                class="img-circle" alt="user avatar">
                        </div>
                        <div class="media-body">
                            <h6 class="mt-2 user-title">
                                {{ Auth::guard('project_owner')->user()->name }}</h6>
                            <p class="user-subtitle">
                                {{ Auth::guard('project_owner')->user()->email }}</p>
                        </div>
                    </li>
                    <li class="sidebar-header">MAIN NAVIGATION</li>
                    <li>
                        <a href="{{ route('project_owner.profile') }}">
                            <i class="icon-wallet mr-2"></i> <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('project_owner.logout') }}">
                            <i class="icon-power mr-2"></i> <span>Logout</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/project-manager') }}">
                            <i class="zmdi zmdi-account-circle"></i>
                            <span>Project Managers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/team-leads') }}">
                            <i class="zmdi zmdi-accounts-alt"></i>
                            <span>Team Leads</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/employees') }}">
                            <i class="zmdi zmdi-accounts-outline"></i>
                            <span>Employees</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/departments') }}">
                            <i class="zmdi zmdi-city"></i>
                            <span>Departments</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/task') }}">
                            <i class="zmdi zmdi-city"></i>
                            <span>Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/manager-tasks') }}">
                            <i class="zmdi zmdi-city"></i>
                            <span>Managers Task</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/project-owner/subtasks') }}">
                            <i class="zmdi zmdi-city"></i>
                            <span>Sub Tasks</span>
                        </a>
                    </li>
                    @php
                        $adminNotificationCount = 0;

                        if (Illuminate\Support\Facades\Auth::guard('project_owner')->check()) {
                            $admin = Illuminate\Support\Facades\Auth::guard('project_owner')->user();

                            $adminNotificationCount = \App\Models\Notification::where('user_id', $admin->id)
                                ->where('user_type', 'project_owner')
                                ->where('is_read', false)
                                ->count();
                        }
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center"
                            href="{{ route('notifications.admin') }}">
                            <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>
                            <span>All Notification</span>
                            @if ($adminNotificationCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                                    {{ $adminNotificationCount > 99 ? '99+' : $adminNotificationCount }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>
                    </li>


                </ul>
            </div>
            <header class="topbar-nav">
                <nav class="navbar navbar-expand fixed-top">
                    <ul class="navbar-nav mr-auto align-items-center">
                        <li class="nav-item"><a class="nav-link toggle-menu" href="javascript:void();"><i
                                    class="icon-menu menu-icon"></i></a></li>
                        <li class="nav-item">
                            <form class="search-bar">
                                <input type="text" class="form-control" placeholder="Enter keywords">
                                <a href="javascript:void();"><i class="icon-magnifier"></i></a>
                            </form>
                        </li>
                    </ul>
                    <ul class="navbar-nav align-items-center right-nav-link">

                    </ul>
                </nav>
            </header>
        @endif

        @if (Auth::guard('team_lead')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="#">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Team Lead</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="user-profile-sidebar">
                        <div class="avatar">
                            <img src="{{ asset('images/team_leads/' . Auth::guard('team_lead')->user()->image) }}"
                                class="img-circle" alt="user avatar">
                        </div>
                        <div class="media-body">
                            <h6 class="mt-2 user-title">
                                {{ Auth::guard('team_lead')->user()->name }}</h6>
                            <p class="user-subtitle">
                                {{ Auth::guard('team_lead')->user()->email }}
                            </p>
                        </div>
                    </li>

                    <li class="sidebar-header">MAIN NAVIGATION</li>

                    <li>
                        <a href="{{ url('/team-lead/profile') }}">
                            <i class="fas fa-user-circle mr-2"></i> <span>Profile</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('team_lead.manager_tasks') }}">
                            <i class="fas fa-tasks mr-2"></i> <span>Manager Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team-lead.sharedtask.view') }}">
                            <i class="fas fa-tasks mr-2"></i> <span>Manager Shared Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team_lead.operation.task.view') }}">
                            <i class="fas fa-tasks mr-2"></i> <span>Signed Tasks</span>
                        </a>
                    </li>

                    @php
                        $user = null;
                        $guard = null;

                        foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
                            if (Auth::guard($g)->check()) {
                                $user = Auth::guard($g)->user();
                                $guard = $g;
                                break;
                            }
                        }

                        $notificationCount = 0;

                        if ($user && $guard) {
                            $notificationCount = \App\Models\Notification::where('user_id', $user->id)
                                ->where('user_type', $guard)
                                ->where('is_read', false)
                                ->count();
                        }
                    @endphp

                    <!-- 🔔 Notification Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center"
                            href="{{ route('notifications.index') }}"
                            @if ($notificationCount > 0) onclick="playNotificationSound()" @endif>
                            <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>
                            @if ($notificationCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- 🔊 Audio Element -->
                    <audio id="notif-sound" preload="auto">
                        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
                    </audio>

                    <!-- 📜 JS Script -->
                    <script>
                        function playNotificationSound() {
                            const audio = document.getElementById('notif-sound');
                            if (audio) {
                                audio.currentTime = 0;
                                audio.play().catch(err => {
                                    console.warn("Sound failed:", err);
                                });
                            }
                        }
                    </script>



                    <li>
                        <a href="{{ route('team_lead.logout') }}">
                            <i class="fas fa-sign-out-alt mr-2"></i> <span>Logout</span>
                        </a>
                    </li>
                </ul>

            </div>

            <header class="topbar-nav">
                <nav class="navbar navbar-expand fixed-top">
                    <ul class="navbar-nav mr-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link toggle-menu" href="javascript:void();"><i
                                    class="icon-menu menu-icon"></i></a>
                        </li>
                        <li class="nav-item">
                            <form class="search-bar">
                                <input type="text" class="form-control" placeholder="Enter keywords">
                                <a href="javascript:void();"><i class="icon-magnifier"></i></a>
                            </form>
                        </li>
                    </ul>

                </nav>
            </header>
        @endif

        @if (Auth::guard('employee')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="#">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Employee</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="user-profile-sidebar">
                        <div class="avatar">
                            <img src="{{ Auth::guard('employee')->user()->image ? asset('images/employees/' . Auth::guard('employee')->user()->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                class="img-circle" alt="user avatar">
                        </div>
                        <div class="media-body">
                            <h6 class="mt-2 user-title">{{ Auth::guard('employee')->user()->name }}</h6>
                            <p class="user-subtitle">{{ Auth::guard('employee')->user()->email }}</p>
                        </div>
                    </li>
                    <li class="sidebar-header">MAIN NAVIGATION</li>
                    <li>
                        <a href="{{ url('/employee/profile') }}">
                            <i class="icon-wallet mr-2"></i> <span>Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/employee/teamlead-tasks') }}">
                            <i class="icon-wallet mr-2"></i> <span>Teamlead Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/employee/subtasks') }}">
                            <i class="icon-wallet mr-2"></i> <span>Subtask</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('team_lead.teamleads') }}">
                            <i class="fas fa-users mr-2"></i> <span>TeamLeads</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employee.sharedtask.view') }}">
                            <i class="fas fa-tasks mr-2"></i> <span>TeamLead Shared Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employee.signed_task.view') }}">
                            <i class="fas fa-tasks mr-2"></i> <span>Signed Tasks</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('employee.logout') }}">
                            <i class="icon-power mr-2"></i> <span>Logout</span>
                        </a>
                    </li>
                    @php
                        $user = null;
                        $guard = null;

                        foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
                            if (Auth::guard($g)->check()) {
                                $user = Auth::guard($g)->user();
                                $guard = $g;
                                break;
                            }
                        }

                        $notificationCount = 0;

                        if ($user && $guard) {
                            $notificationCount = \App\Models\Notification::where('user_id', $user->id)
                                ->where('user_type', $guard)
                                ->where('is_read', false)
                                ->count();
                        }
                    @endphp

                    <!-- 🔔 Notification Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center"
                            href="{{ route('notifications.index') }}"
                            @if ($notificationCount > 0) onclick="playNotificationSound()" @endif>
                            <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>
                            @if ($notificationCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- 🔊 Audio Element -->
                    <audio id="notif-sound" preload="auto">
                        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
                    </audio>

                    <!-- 📜 JS Script -->
                    <script>
                        function playNotificationSound() {
                            const audio = document.getElementById('notif-sound');
                            if (audio) {
                                audio.currentTime = 0;
                                audio.play().catch(err => {
                                    console.warn("Sound failed:", err);
                                });
                            }
                        }
                    </script>
                </ul>
            </div>

            <header class="topbar-nav">
                <nav class="navbar navbar-expand fixed-top">
                    <ul class="navbar-nav mr-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link toggle-menu" href="javascript:void();"><i
                                    class="icon-menu menu-icon"></i></a>
                        </li>
                        <li class="nav-item">
                            <form class="search-bar">
                                <input type="text" class="form-control" placeholder="Enter keywords">
                                <a href="javascript:void();"><i class="icon-magnifier"></i></a>
                            </form>
                        </li>
                    </ul>
                    <ul class="navbar-nav align-items-center right-nav-link">
                        <li class="nav-item dropdown-lg">
                            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
                                href="javascript:void();"><i class="fa fa-envelope-open-o"></i></a>
                        </li>
                        <li class="nav-item dropdown-lg">
                            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
                                href="javascript:void();"><i class="fa fa-bell-o"></i></a>
                        </li>
                        <li class="nav-item language">
                            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
                                href="javascript:void();"><i class="fa fa-flag"></i></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item"><i class="flag-icon flag-icon-gb mr-2"></i> English</li>
                                <li class="dropdown-item"><i class="flag-icon flag-icon-fr mr-2"></i> French</li>
                                <li class="dropdown-item"><i class="flag-icon flag-icon-cn mr-2"></i> Chinese</li>
                                <li class="dropdown-item"><i class="flag-icon flag-icon-de mr-2"></i> German</li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </header>
        @endif


        @if (Auth::guard('visitor')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="#">
                        <img src="{{ asset('assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Employee</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="user-profile-sidebar">
                        <div class="avatar">
                            <img src="{{ Auth::guard('visitor')->user()->image ? asset('images/visitors/' . Auth::guard('visitor')->user()->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                class="img-circle" alt="user avatar">
                        </div>
                        <div class="media-body">
                            <h6 class="mt-2 user-title">{{ Auth::guard('visitor')->user()->name }}</h6>
                            <p class="user-subtitle">{{ Auth::guard('visitor')->user()->email }}</p>
                        </div>
                    </li>
                    <li class="sidebar-header">MAIN NAVIGATION</li>
                    <li>
                        <a href="{{ route('visitor.sharedtask.view') }}">
                            <i class="icon-wallet mr-2"></i> <span>Visit List</span>
                        </a>
                    </li>

                    @php
                        $user = null;
                        $guard = null;

                        foreach (['project_owner', 'project_manager', 'team_lead', 'employee'] as $g) {
                            if (Auth::guard($g)->check()) {
                                $user = Auth::guard($g)->user();
                                $guard = $g;
                                break;
                            }
                        }

                        $notificationCount = 0;

                        if ($user && $guard) {
                            $notificationCount = \App\Models\Notification::where('user_id', $user->id)
                                ->where('user_type', $guard)
                                ->where('is_read', false)
                                ->count();
                        }
                    @endphp

                    <!-- 🔔 Notification Icon -->
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center"
                            href="{{ route('notifications.index') }}"
                            @if ($notificationCount > 0) onclick="playNotificationSound()" @endif>
                            <i class="zmdi zmdi-notifications zmdi-hc-lg"></i>
                            @if ($notificationCount > 0)
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow-sm">
                                    {{ $notificationCount > 99 ? '99+' : $notificationCount }}
                                    <span class="visually-hidden">unread notifications</span>
                                </span>
                            @endif
                        </a>
                    </li>

                    <!-- 🔊 Audio Element -->
                    <audio id="notif-sound" preload="auto">
                        <source src="{{ asset('sounds/notification.mp3') }}" type="audio/mpeg">
                    </audio>

                    <!-- 📜 JS Script -->
                    <script>
                        function playNotificationSound() {
                            const audio = document.getElementById('notif-sound');
                            if (audio) {
                                audio.currentTime = 0;
                                audio.play().catch(err => {
                                    console.warn("Sound failed:", err);
                                });
                            }
                        }
                    </script>
                </ul>
            </div>

            <header class="topbar-nav">
                <nav class="navbar navbar-expand fixed-top">
                    <ul class="navbar-nav mr-auto align-items-center">
                        <li class="nav-item">
                            <a class="nav-link toggle-menu" href="javascript:void();"><i
                                    class="icon-menu menu-icon"></i></a>
                        </li>
                        <li class="nav-item">
                            <form class="search-bar">
                                <input type="text" class="form-control" placeholder="Enter keywords">
                                <a href="javascript:void();"><i class="icon-magnifier"></i></a>
                            </form>
                        </li>
                    </ul>
                    <ul class="navbar-nav align-items-center right-nav-link">
                        <li class="nav-item dropdown-lg">
                            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
                                href="javascript:void();"><i class="fa fa-envelope-open-o"></i></a>
                        </li>
                        <li class="nav-item dropdown-lg">
                            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
                                href="javascript:void();"><i class="fa fa-bell-o"></i></a>
                        </li>
                        <li class="nav-item language">
                            <a class="nav-link dropdown-toggle waves-effect" data-toggle="dropdown"
                                href="javascript:void();"><i class="fa fa-flag"></i></a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item"><i class="flag-icon flag-icon-gb mr-2"></i> English</li>
                                <li class="dropdown-item"><i class="flag-icon flag-icon-fr mr-2"></i> French</li>
                                <li class="dropdown-item"><i class="flag-icon flag-icon-cn mr-2"></i> Chinese</li>
                                <li class="dropdown-item"><i class="flag-icon flag-icon-de mr-2"></i> German</li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </header>
        @endif

        <div class="clearfix"></div>

        <div class="content-wrapper">
            @yield('content')
        </div>

        <div class="right-sidebar">
            <div class="switcher-icon"><i class="zmdi zmdi-settings zmdi-hc-spin"></i></div>
            <div class="right-sidebar-content">
                <p class="mb-0">Gaussion Texture</p>
                <hr>
                <ul class="switcher">
                    <li id="theme1"></li>
                    <li id="theme2"></li>
                    <li id="theme3"></li>
                    <li id="theme4"></li>
                    <li id="theme5"></li>
                    <li id="theme6"></li>
                </ul>
                <p class="mb-0">Gradient Background</p>
                <hr>
                <ul class="switcher">
                    <li id="theme7"></li>
                    <li id="theme8"></li>
                    <li id="theme9"></li>
                    <li id="theme10"></li>
                    <li id="theme11"></li>
                    <li id="theme12"></li>
                    <li id="theme13"></li>
                    <li id="theme14"></li>
                    <li id="theme15"></li>
                </ul>
            </div>
        </div>

    </div>

    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/simplebar/js/simplebar.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.loading-indicator.js') }}"></script>
    <script src="{{ asset('assets/js/app-script.js') }}"></script>
    <script src="{{ asset('assets/plugins/Chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('assets/js/index.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        integrity="sha384-7qAoOXltbVP82dhxHAUje59V5r2YsVfBafyUDxEdApLPmcdhBPg1DKg1ERo0BZlK" crossorigin="anonymous">
    </script>
    @section('scripts')
        <script>
            particlesJS('particles-js', {
                particles: {
                    number: {
                        value: 80,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: '#ffffff'
                    },
                    shape: {
                        type: 'circle'
                    },
                    opacity: {
                        value: 0.5,
                        random: true
                    },
                    size: {
                        value: 3,
                        random: true
                    },
                    line_linked: {
                        enable: true,
                        distance: 150,
                        color: '#ffffff',
                        opacity: 0.4,
                        width: 1
                    },
                    move: {
                        enable: true,
                        speed: 6,
                        direction: 'none',
                        random: false,
                        straight: false
                    }
                },
                interactivity: {
                    detect_on: 'canvas',
                    events: {
                        onhover: {
                            enable: true,
                            mode: 'repulse'
                        },
                        onclick: {
                            enable: true,
                            mode: 'push'
                        }
                    },
                    modes: {
                        repulse: {
                            distance: 100
                        },
                        push: {
                            particles_nb: 4
                        }
                    }
                },
                retina_detect: true
            });

            $(document).ready(function() {
                $('.dropdown-toggle').dropdown();
                $('.toggle-menu').on('click', function() {
                    $('#wrapper').toggleClass('toggled');
                });
            });

            document.addEventListener("DOMContentLoaded", function() {
                const toggleButton = document.querySelectorAll('.toggle-menu');
                const sidebar = document.getElementById('sidebar-wrapper');

                toggleButton.forEach(btn => {
                    btn.addEventListener('click', function() {
                        sidebar.classList.toggle('sidebar-collapsed');
                    });
                });
            });
        </script>

    </body>

    </html>
