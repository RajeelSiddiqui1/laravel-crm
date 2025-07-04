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
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/sidebar-menu.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/app-style.css') }}" rel="stylesheet" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous"> --}}
</head>

<body class="bg-theme bg-theme1">
    <div id="wrapper">

        @if (Auth::guard('project_manager')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="index.html">
                        <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Project Manager</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">

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
                        <li class="nav-item dropdown-lg"><a class="nav-link dropdown-toggle waves-effect"
                                data-toggle="dropdown" href="javascript:void();"><i
                                    class="fa fa-envelope-open-o"></i></a></li>
                        <li class="nav-item dropdown-lg"><a class="nav-link dropdown-toggle waves-effect"
                                data-toggle="dropdown" href="javascript:void();"><i class="fa fa-bell-o"></i></a></li>
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                                <span class="user-profile">
                                    <img src="{{ asset('images/project_managers/' . Auth::guard('project_manager')->user()->image) }}"
                                        class="img-circle" alt="user avatar">
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item user-details">
                                    <a href="javascript:void();">
                                        <div class="media">
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
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
                                <li class="dropdown-item"><a href="{{ url('/project-manager/profile') }}"
                                        class="icon-wallet mr-2"></i> Profile</a>
                                <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><i class="icon-power mr-2"></i> <a
                                        href="{{ route('project_manager.logout') }}">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </header>
        @endif

        @if (Auth::guard('project_owner')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="index.html">
                        <img src="assets/images/logo-icon.png" class="logo-icon" alt="logo icon">
                        <h5 class="logo-text">Dashtreme Admin</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="sidebar-header">MAIN NAVIGATION</li>
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

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                                <span class="user-profile">
                                    <img src="{{ Auth::guard('project_owner')->user()->image ? asset('images/project_owner/' . Auth::guard('project_owner')->user()->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                        class="img-circle" alt="user avatar">
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item user-details">
                                    <a href="javascript:void();">
                                        <div class="media">
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
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
                                <li class="dropdown-item"><i class="icon-wallet mr-2"></i> Account</li>
                                <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><i class="icon-power mr-2"></i> <a
                                        href="{{ route('project_owner.logout') }}">Logout</a></li>
                            </ul>
                        </li>
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
                    <li class="sidebar-header">MAIN NAVIGATION</li>
                    {{-- <li><a href="{{ url('/project-owner/project-manager') }}"><i
                                class="zmdi zmdi-view-dashboard"></i><span>Project Managers</span></a></li>
                    <li><a href="{{ url('/project-owner/team-leads') }}"><i
                                class="zmdi zmdi-invert-colors"></i><span>Team Leads</span></a></li>
                    <li><a href="{{ url('/project-owner/departments') }}"><i
                                class="zmdi zmdi-invert-colors"></i><span>Departments</span></a></li> --}}
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown"
                                href="#">
                                <span class="user-profile">
                                    <img src="{{ asset('images/team_leads/' . Auth::guard('team_lead')->user()->image) }}"
                                        class="img-circle" alt="user avatar">
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item user-details">
                                    <a href="javascript:void();">
                                        <div class="media">
                                            <div class="avatar">
                                                <img src="{{ asset('images/team_leads/' . Auth::guard('team_lead')->user()->image) }}"
                                                    </div>
                                                <div class="media-body">
                                                    <h6 class="mt-2 user-title">
                                                        {{ Auth::guard('team_lead')->user()->name }}</h6>
                                                    <p class="user-subtitle">
                                                        {{ Auth::guard('team_lead')->user()->email }}
                                                    </p>
                                                </div>
                                            </div>
                                    </a>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><a href="{{ url('/team-lead/profile') }}"
                                        class="icon-wallet mr-2"></i> Profile</a>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item">
                                    <i class="icon-power mr-2"></i>
                                    <a href="{{ route('team_lead.logout') }}">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </header>
        @endif

        @if (Auth::guard('employee')->check())
            <div id="sidebar-wrapper" data-simplebar="" data-simplebar-auto-hide="true">
                <div class="brand-logo">
                    <a href="#">

                        <h5 class="logo-text">Employee</h5>
                    </a>
                </div>
                <ul class="sidebar-menu do-nicescrol">
                    <li class="sidebar-header">MAIN NAVIGATION</li>
                    {{-- <li><a href="{{ url('/project-owner/project-manager') }}"><i
                                class="zmdi zmdi-view-dashboard"></i><span>Project Managers</span></a></li>
                    <li><a href="{{ url('/project-owner/team-leads') }}"><i
                                class="zmdi zmdi-invert-colors"></i><span>Team Leads</span></a></li>
                    <li><a href="{{ url('/project-owner/departments') }}"><i
                                class="zmdi zmdi-invert-colors"></i><span>Departments</span></a></li> --}}
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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown"
                                href="#">
                                <span class="user-profile">
                                    <img src="{{ Auth::guard('employee')->user()->image ? asset('images/employees/' . Auth::guard('employee')->user()->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                        class="img-circle" alt="user avatar">
                                </span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right">
                                <li class="dropdown-item user-details">
                                    <a href="javascript:void();">
                                        <div class="media">
                                            <div class="avatar">
                                                <img src="{{ Auth::guard('employee')->user()->image ? asset('images/employees/' . Auth::guard('employee')->user()->image) : 'https://avatar.iran.liara.run/public/28' }}"
                                                    class="img-circle" alt="user avatar">
                                            </div>
                                            <div class="media-body">
                                                <h6 class="mt-2 user-title">
                                                    {{ Auth::guard('employee')->user()->name }}</h6>
                                                <p class="user-subtitle">{{ Auth::guard('employee')->user()->email }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                                <li class="dropdown-item"><i class="icon-envelope mr-2"></i> Inbox</li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><a href="{{ url('/employee/profile') }}"
                                        class="icon-wallet mr-2"></i> Profile</a>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item"><i class="icon-settings mr-2"></i> Setting</li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item">
                                    <i class="icon-power mr-2"></i>
                                    <a href="{{ route('employee.logout') }}" class="text-decoration-none">Logout</a>
                                </li>
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
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script> --}}

</body>

</html>
