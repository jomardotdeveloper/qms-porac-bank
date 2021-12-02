<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>QMS - @yield("title") </title>
    <link rel="icon" type="image/x-icon" href="/admin/assets/img/appicon2.ico">
    <link href="/admin/css2.css?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/main.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/structure.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/highlight/styles/monokai-sublime.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    @yield("custom-styles")
</head>
<body>
    <!--  Navbar Starts  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="#">
                        <img src="/admin/assets/img/LATEST.png" class="navbar-logo" style="width:100%;margin-left:-1rem;" alt="logo">
                    </a>
                </li>
                <!-- <li class="nav-item theme-text">
                    <a href="#" class="nav-link" style="margin-left:-1rem;">PBQMS</a>
                </li> -->
            </ul>
            <ul class="navbar-item flex-row ml-md-auto">
                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if(auth()->user()->profile->photo)
                        <img src="{{auth()->user()->profile->photo}}" alt="avatar">
                        @else
                        <img src="/admin/assets/img/default.png" alt="avatar">
                        @endif
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="nav-drop is-account-dropdown">
                            <div class="inner">
                                @if(auth()->user()->is_admin)
                                <div class="nav-drop-header">
                                      <span class="text-primary font-15">Welcome Admin !</span>
                                </div>
                                @else
                                <div class="nav-drop-header">
                                      <span class="text-primary font-15">Welcome {{auth()->user()->profile->first_name}} !</span>
                                </div>
                                @endif
                                <div class="nav-drop-body account-items pb-0">
                                    <a id="profile-link" class="account-item" href="{{route('my_profile.index')}}">
                                        <div class="media align-center">
                                            <div class="media-left">
                                                <div class="image">
                                                    @if(auth()->user()->profile->photo)
                                                    <img class="rounded-circle avatar-xs" src="{{auth()->user()->profile->photo}}" alt="">
                                                    @else
                                                    <img class="rounded-circle avatar-xs" src="/admin/assets/img/default.png" alt="">
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="media-content ml-3">
                                                <h6 class="font-13 mb-0 strong">{{ auth()->user()->profile->first_name }}</h6>
                                                <small>{{ auth()->user()->profile->last_name }}</small>
                                            </div>
                                            <div class="media-right">
                                                <i data-feather="check"></i>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="account-item" href="{{route('my_profile.index')}}">
                                      <div class="media align-center">
                                          <div class="icon-wrap">
                                            <i class="las la-user font-20"></i>
                                          </div>
                                          <div class="media-content ml-3">
                                              <h6 class="font-13 mb-0 strong">My Account</h6>
                                          </div>
                                      </div>
                                    </a>
                                    <hr class="account-divider">
                                    <form action="{{route('logout')}}" method="POST" id="logout">
                                        @csrf
                                        <a class="account-item" id="fakeanchor">
                                            <div class="media align-center">
                                                <div class="icon-wrap">
                                                    <i class="las la-sign-out-alt font-20"></i>
                                                </div>
                                                <div class="media-content ml-3">
                                                    <h6 class="font-13 mb-0 strong ">Logout</h6>
                                                </div>
                                            </div>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </header>
    </div>
    <!--  Navbar Ends  -->
    <!--  Main Container Starts  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>
        <div class="rightbar-overlay"></div>
        <!--  Sidebar Starts  -->
        <div class="sidebar-wrapper sidebar-theme">
            <nav id="sidebar">
                <ul class="list-unstyled menu-categories" id="accordionExample">
                    <li class="menu-title">Dashboard</li>
                    <li class="menu">
                        <a href="{{route('dashboards.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'dashboards.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-home"></i>
                                <span>Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- STARTS HERE -->
                    @if(auth()->user()->is_admin)
                    <li class="menu-title">Branches</li>
                    <li class="menu">
                        <a href="{{route('branches.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'branches.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-building"></i>
                                <span>Branches</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->is_admin)
                    <li class="menu-title">Users</li>
                    @elseif(in_array("RLA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu-title">Users</li>
                    @elseif(in_array("UA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu-title">Users</li>
                    @elseif(in_array("AA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu-title">Users</li>
                    @endif
                    @if(auth()->user()->is_admin || in_array("RLA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu">
                        <a href="{{route('roles.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'roles.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-user"></i>
                                <span>Roles</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->is_admin || in_array("UA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu">
                        <a href="{{route('profiles.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'profiles.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-users"></i>
                                <span>Users</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->is_admin || in_array("AA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu">
                        <a href="{{route('accounts.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'accounts.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-piggy-bank"></i>
                                <span>Accounts</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if(auth()->user()->is_admin)
                    <li class="menu-title">Utilities</li>
                    @elseif(in_array("SA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu-title">Utilities</li>
                    @elseif(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute()) && auth()->user()->profile->window != null)
                    <li class="menu-title">Utilities</li>
                    @endif

                    @if(auth()->user()->is_admin)
                   
                    @endif

                    @if(!auth()->user()->is_admin && in_array("SA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu">
                        <a href="{{route('settings.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'settings.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-cog"></i>
                                <span>Settings</span>
                            </div>
                        </a>
                    </li>
                    @endif

                    @if(!auth()->user()->is_admin && in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    @if(auth()->user()->profile->window != null)
                    <li class="menu">
                        <a href="{{route('controls.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'controls.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-tools"></i>
                                <span>Controls</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    @endif

                    @if(auth()->user()->is_admin || in_array("RA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu-title">Reports</li>
                    @endif
                    @if(auth()->user()->is_admin || in_array("RA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu">
                        <a href="{{route('notifications.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'notifications.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-envelope"></i>
                                <span>Notifications</span>
                            </div>
                        </a>
                    </li>
                    <li class="menu">
                        <a href="{{route('transactions.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'transactions.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-file-invoice"></i>
                                <span>Transactions</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    
                    @if(auth()->user()->is_admin)
                    <li class="menu-title">Feedbacks</li>
                    <li class="menu">
                        <a href="{{route('feedbacks.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'feedbacks.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-comment"></i>
                                <span>Feedbacks</span>
                            </div>
                        </a>
                    </li>
                    @endif
                    <!-- ENDS HERE -->
                </ul>                
            </nav>
        </div>
        <!--  Sidebar Ends  -->
        <!--  Content Area Starts  -->
        <div id="content" class="main-content">
            <!--  Navbar Starts / Breadcrumb Area  -->
            <div class="sub-header-container">
                <header class="header navbar navbar-expand-sm">
                    <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom">
                        <i class="las la-bars"></i>
                    </a>
                    <ul class="navbar-nav flex-row">
                        <li>
                            <div class="page-header">
                                @yield("breadcrumbs")
                            </div>
                        </li>
                    </ul>
                </header>
            </div>
            <!--  Navbar Ends / Breadcrumb Area  -->
            <!-- Main Body Starts -->
            <div class="layout-px-spacing">
                <div class="layout-top-spacing mb-2">
                    <!-- DITO MAGSISIMULA -->
                    @yield("content")
                    <!-- DITO NAMAN MATATAPOS -->
                </div>
            </div>
            <!-- Main Body Ends -->
            <div class="responsive-msg-component">
                <p>
                    <a class="close-msg-component"><i class="las la-times"></i></a>
                    Please reload the page to view the responsive functionalities
                </p>
            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © 2021 <a target="_blank" href="../index.htm">QMS</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Crafted with extra <i class="las la-heart text-danger"></i></p>
                </div>
            </div>
            <div class="scroll-top-arrow" style="display: none;">
                <i class="las la-angle-up"></i>
            </div>
        </div>
    </div>
    <!-- Main Container Ends -->
    <!-- Common Script Starts -->
    <script src="/admin/assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="/admin/bootstrap/js/popper.min.js"></script>
    <script src="/admin/bootstrap/js/bootstrap.min.js"></script>
    <script src="/admin/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/admin/assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="/admin/assets/js/custom.js"></script>
    <script>
        $("a#fakeanchor").click(function()
        {
            $("#logout").submit();
            return false;
        });
    </script>
    @stack("custom-scripts")
</body>
</html>