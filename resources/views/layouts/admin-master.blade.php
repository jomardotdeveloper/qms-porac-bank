<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>QMS - @yield("title") </title>
    <link rel="icon" type="image/x-icon" href="/admin/assets/img/logooriginal.ico">
    <link href="/admin/css2.css?family=Poppins:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="/admin/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/main.css" rel="stylesheet" type="text/css">
    <link href="/admin/assets/css/structure.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/perfect-scrollbar/perfect-scrollbar.css" rel="stylesheet" type="text/css">
    <link href="/admin/plugins/highlight/styles/monokai-sublime.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="line-awesome.min.css">
    <link href="/admin/assets/css/loader.css" rel="stylesheet" type="text/css">  
    @yield("custom-styles")
    
</head>
<body>
    <!-- Loader Starts -->
    <!-- <div id="load_screen"> 
        <div class="boxes">
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
            <div class="box">
                <div></div><div></div><div></div><div></div>
            </div>
        </div> -->
        <!-- <div class="rotate animate">
            <span letter="P"></span>
        </div> -->
        <!-- <p class="xato-loader-heading">Xato</p>
    </div> -->
    <!--  Loader Ends -->
    <!--  Navbar Starts  -->
    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">
            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                    <a href="index.html">
                        <img src="/admin/assets/img/logooriginal.png" style="margin-left:-1rem;" class="navbar-logo" alt="logo">
                    </a>
                </li>
                <li class="nav-item theme-text">
                    <a href="#" class="nav-link">Porac Bank</a>
                </li>
            </ul>
            <ul class="navbar-item flex-row ml-md-auto">
                <li class="nav-item dropdown fullscreen-dropdown d-none d-lg-flex">
                    <a class="nav-link full-screen-mode" href="javascript:void(0);">
                        <i class="las la-compress" id="fullScreenIcon"></i>
                    </a>
                </li>
                <li class="nav-item dropdown message-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle" id="messageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="las la-envelope"></i>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="messageDropdown">
                        <div class="nav-drop is-notification-dropdown">
                            <div class="inner">
                                <div class="nav-drop-header">
                                      <span class="text-black font-12 strong">3 new mails</span>
                                      <a class="text-muted font-12" href="#">
                                        Mark all read
                                      </a>
                                </div>
                                <div class="nav-drop-body account-items pb-0">
                                    <a class="account-item">
                                        <div class="media">
                                            <div class="user-img">
                                                <img class="rounded-circle avatar-xs" src="/admin/assets/img/profile-11.jpg" alt="profile">
                                            </div>
                                            <div class="media-body">
                                                <div class="">
                                                    <h6 class="text-primary font-13 mb-0 strong">Jennifer Queen</h6>
                                                    <p class="m-0 mt-1 font-10 text-muted">Permission Required</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="account-item marked-read">
                                        <div class="media">
                                            <div class="user-img">
                                                <img class="rounded-circle avatar-xs" src="/admin/assets/img/profile-10.jpg" alt="profile">
                                            </div>
                                            <div class="media-body">
                                                <div class="">
                                                    <h6 class="text-primary font-13 mb-0 strong">Lara Smith</h6>
                                                    <p class="m-0 mt-1 font-10 text-muted">Invoice needed</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="account-item marked-read">
                                        <div class="media">
                                            <div class="user-img">
                                                <img class="rounded-circle avatar-xs" src="assets/img/profile-9.jpg" alt="profile">
                                            </div>
                                            <div class="media-body">
                                                <div class="">
                                                    <h6 class="text-primary font-13 mb-0 strong">Victoria Williamson</h6>
                                                    <p class="m-0 mt-1 font-10 text-muted">Account need to be synced</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                    <hr class="account-divider">
                                    <div class="text-center">
                                        <a class="text-primary strong font-13" href="apps_mail.html">View All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="nav-item dropdown notification-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle position-relative" id="notificationDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="las la-bell"></i>
                        <div class="blink">
                            <div class="circle"></div>
                        </div>
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="notificationDropdown">
                        <div class="nav-drop is-notification-dropdown">
                            <div class="inner">
                                <div class="nav-drop-header">
                                      <span class="text-black font-12 strong">5 Notifications</span>
                                      <a class="text-muted font-12" href="#">
                                        Clear All
                                      </a>
                                </div>
                                <div class="nav-drop-body account-items pb-0">
                                    <a class="account-item" href="apps_ecommerce_orders.html">
                                      <div class="media align-center">
                                          <div class="icon-wrap">
                                            <i class="las la-box font-20"></i>
                                          </div>
                                          <div class="media-content ml-3">
                                              <h6 class="font-13 mb-0 strong">2 New orders placed</h6>
                                              <p class="m-0 mt-1 font-10 text-muted">10 sec ago</p>
                                          </div>
                                      </div>
                                    </a>
                                    <a class="account-item" href="javascript:void(0)">
                                    <div class="media align-center">
                                        <div class="icon-wrap">
                                            <i class="las la-user-plus font-20"></i>
                                        </div>
                                        <div class="media-content ml-3">
                                            <h6 class="font-13 mb-0 strong">New User registered</h6>
                                            <p class="m-0 mt-1 font-10 text-muted">5 minute ago</p>
                                        </div>
                                    </div>
                                    </a>
                                    <a class="account-item" href="apps_tickets.html">
                                      <div class="media align-center">
                                          <div class="icon-wrap">
                                            <i class="las la-grin-beam font-20"></i>
                                          </div>
                                          <div class="media-content ml-3">
                                              <h6 class="font-13 mb-0 strong">21 Queries solved</h6>
                                              <p class="m-0 mt-1 font-10 text-muted">1 hour ago</p>
                                          </div>
                                      </div>
                                    </a>
                                    <a class="account-item" href="javascript:void(0)">
                                        <div class="media align-center">
                                            <div class="icon-wrap">
                                                <i class="las la-cloud-download-alt font-20"></i>
                                            </div>
                                            <div class="media-content ml-3">
                                                <h6 class="font-13 mb-0 strong">New update available</h6>
                                                <p class="m-0 mt-1 font-10 text-muted">1 day ago</p>
                                            </div>
                                        </div>
                                    </a>
                                    <hr class="account-divider">
                                    <div class="text-center">
                                        <a class="text-primary strong font-13" href="pages_notifications.html">View All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
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
                                @endif
                                <div class="nav-drop-body account-items pb-0">
                                    <a id="profile-link" class="account-item" href="pages_profile.html">
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
                                    <a class="account-item" href="pages_profile.html">
                                      <div class="media align-center">
                                          <div class="icon-wrap">
                                            <i class="las la-user font-20"></i>
                                          </div>
                                          <div class="media-content ml-3">
                                              <h6 class="font-13 mb-0 strong">My Account</h6>
                                          </div>
                                      </div>
                                    </a>
                                    <a class="account-item" href="pages_timeline.html">
                                        <div class="media align-center">
                                            <div class="icon-wrap">
                                                <i class="las la-briefcase font-20"></i>
                                            </div>
                                            <div class="media-content ml-3">
                                                <h6 class="font-13 mb-0 strong">My Activity</h6>
                                            </div>
                                        </div>
                                    </a>
                                    <a class="account-item settings">
                                      <div class="media align-center">
                                          <div class="icon-wrap">
                                            <i class="las la-cog font-20"></i>
                                          </div>
                                          <div class="media-content ml-3">
                                              <h6 class="font-13 mb-0 strong">Settings</h6>
                                          </div>
                                      </div>
                                    </a>
                                    <a class="account-item" href="auth_lock_screen_3.html">
                                        <div class="media align-center">
                                            <div class="icon-wrap">
                                                <i class="las la-lock font-20"></i>
                                            </div>
                                            <div class="media-content ml-3">
                                                <h6 class="font-13 mb-0 strong">Lock Screen</h6>
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
            <ul class="navbar-item flex-row">
                <li class="nav-item dropdown header-setting">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle rightbarCollapse" data-placement="bottom">
                        <i class="las la-sliders-h"></i>
                    </a>
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
                    @elseif(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    <li class="menu-title">Utilities</li>
                    @endif

                    @if(auth()->user()->is_admin)
                    <li class="menu">
                        <a href="{{route('attachments.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'attachments.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-mobile"></i>
                                <span>Mobile Application</span>
                            </div>
                        </a>
                    </li>
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
                    <li class="menu">
                        <a href="{{route('controls.index')}}" aria-expanded="false" class="dropdown-toggle" data-active="{{ request()->route()->getName() == 'controls.index' ? 'true' : 'false' }}">
                            <div class="">
                                <i class="las la-tools"></i>
                                <span>Controls</span>
                            </div>
                        </a>
                    </li>
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
            <!-- Copyright Footer Starts -->
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                    <p class="">Copyright © 2021 <a target="_blank" href="../index.htm">QMS</a>, All rights reserved.</p>
                </div>
                <div class="footer-section f-section-2">
                    <p class="">Crafted with extra <i class="las la-heart text-danger"></i></p>
                </div>
            </div>
            <!-- Copyright Footer Ends -->
            <!-- Arrow Starts -->
            <div class="scroll-top-arrow" style="display: none;">
                <i class="las la-angle-up"></i>
            </div>
            <!-- Arrow Ends -->
        </div>
        <!--  Content Area Ends  -->
        <!--  Rightbar Area Starts -->
        <div class="right-bar">
            <div class="h-100">
                <div class="simplebar-wrapper" style="margin: 0px;">
                    <div class="simplebar-mask">
                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                            <div class="simplebar-content-wrapper" style="height: 100%;">
                                <div class="simplebar-content" style="padding: 0px;">
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs nav-bordered nav-justified" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link  active" data-toggle="tab" href="#chat-tab" role="tab" aria-selected="true">
                                                <i class="las la-sms"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " data-toggle="tab" href="#status-tab" role="tab" aria-selected="false">
                                                <i class="las la-tasks"></i>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link " data-toggle="tab" href="#settings-tab" role="tab" aria-selected="false">
                                                <i class="las la-cog"></i>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- Tab panes starts -->
                                    <div class="tab-content pt-0 rightbar-tab-container">
                                        <div class="tab-pane active rightbar-tab" id="chat-tab" role="tabpanel">
                                            <form class="search-bar p-3">
                                                <div class="position-relative">
                                                    <input type="text" class="form-control search-form-control" placeholder="Search">
                                                    <span class="mdi mdi-magnify"></span>
                                                </div>
                                            </form>
                                            <h6 class="right-bar-heading px-3 mt-2 text-uppercase">Chat Groups</h6>
                                            <div class="p-2">
                                                <a href="javascript: void(0);" class="text-reset group-item pl-3 mb-2 d-block">
                                                    <i class="las la-dot-circle mr-1 text-success"></i>
                                                    <span class="mb-0 mt-1 text-success">Backend Team</span>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset group-item pl-3 mb-2 d-block">
                                                    <i class="las la-dot-circle mr-1 text-warning"></i>
                                                    <span class="mb-0 mt-1 text-warning">Frontend Team</span>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset group-item pl-3 mb-2 d-block">
                                                    <i class="las la-dot-circle mr-1 text-danger"></i>
                                                    <span class="mb-0 mt-1 text-danger">Back Office</span>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset group-item pl-3 d-block">
                                                    <i class="las la-dot-circle mr-1 text-info"></i>
                                                    <span class="mb-0 mt-1 text-info">Personal</span>
                                                </a>
                                            </div>
                                            <h6 class="right-bar-heading px-3 mt-2 text-uppercase">My Favourites <a href="javascript: void(0);"><i class="las la-angle-right"></i></a></h6>
                                            <div class="p-2">
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media pt-0">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-1.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Andrew Mackie</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">It will seem like simplified English.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-2.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Sophia Garner</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">Nice and amazing.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-3.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Jackie Smith</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">Send me the .pdf files asap.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <h6 class="right-bar-heading px-3 mt-2 text-uppercase">Chats <a href="javascript: void(0);"><i class="las la-angle-right"></i></a></h6>
                                            <div class="p-2 pb-4">
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media pt-0">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-3.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Owen Hargrieves</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">That's really cool</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-4.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Riyana Giyan</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">When do you send me those files ?</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-5.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Ryan Timberlake</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">Invoice has been generated</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-6.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Julie Roman</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">Thank you so much.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-7.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Gareth Sarkar</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">Thats was awesome</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset">
                                                    <div class="media">
                                                        <div class="position-relative mr-2">
                                                            <img src="assets/img/profile-8.jpg" class="rounded-circle avatar-sm ml-2" alt="user-pic">
                                                            <span class="user-status online"></span>
                                                        </div>
                                                        <div class="media-body overflow-hidden mr-2">
                                                            <h6 class="mt-0 mb-1 font-13">Kylie Roberts</h6>
                                                            <div class="font-12">
                                                                <p class="mb-0 text-truncate">Amazing feature.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </a>
                                                <div class="text-center pt-4">
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary">
                                                        Load more
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane rightbar-tab" id="status-tab" role="tabpanel">
                                            <h6 class="right-bar-heading p-2 px-3 mt-2 text-uppercase">Order Status </h6>
                                            <div class="px-2">
                                                <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                                                    <p class="text-muted mb-0">Order Success<span class="float-right">75%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                                                    <p class="text-muted mb-0">Order Processing<span class="float-right">37%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 37%" aria-valuenow="37" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                                                    <p class="text-muted mb-0">Order Initiated<span class="float-right">52%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 52%" aria-valuenow="52" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>
                                            </div>
                                            <h6 class="font-weight-medium px-3 mb-0 mt-4 text-uppercase">Payment Status</h6>
                                            <div class="p-2">
                                                <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                                                    <p class="text-muted mb-0">Payment Failed<span class="float-right">12%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 12%" aria-valuenow="12" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                                                    <p class="text-muted mb-0">Payment on hold<span class="float-right">67%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: 67%" aria-valuenow="67" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>
                                                <a href="javascript: void(0);" class="text-reset item-hovered d-block p-2">
                                                    <p class="text-muted mb-0">Payment Successful<span class="float-right">84%</span></p>
                                                    <div class="progress mt-2" style="height: 4px;">
                                                        <div class="progress-bar bg-success" role="progressbar" style="width: 84%" aria-valuenow="84" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="text-center pt-2">
                                                <a href="javascript: void(0);" class="btn btn-primary btn-sm">Show All</a>
                                            </div>
                                        </div>
                                        <div class="tab-pane rightbar-tab" id="settings-tab" role="tabpanel">
                                            <h6 class="right-bar-heading p-2 px-3 mt-2 text-uppercase">Account Setting </h6>
                                            <div class="px-2">
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox" checked="">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Sync Contacts</p>
                                                </div>
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Auto Update</p>
                                                </div>
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Recieve Notifications</p>
                                                </div>
                                            </div>
                                            <h6 class="right-bar-heading p-2 px-3 mt-2 text-uppercase">Mail Setting </h6>
                                            <div class="px-2">
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox" checked="">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Mail Auto Responder</p>
                                                </div>
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox" checked="">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Auto Trash Delete</p>
                                                </div>
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Custom Signature</p>
                                                </div>
                                            </div>
                                            <h6 class="right-bar-heading p-2 px-3 mt-2 text-uppercase">Chat Setting </h6>
                                            <div class="px-2">
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox" checked="">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Show Online</p>
                                                </div>
                                                <div class="switch-container mb-3 pl-2">
                                                    <label class="switch">
                                                        <input type="checkbox" checked="">
                                                        <span class="slider round primary-switch"></span>
                                                    </label>
                                                    <p class="ml-3 text-dark">Chat Notifications</p>
                                                </div>
                                            </div>
                                            <div class="px-2 text-center pt-4">
                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger">
                                                    Set Default
                                                </a>
                                                <button class="ripple-button ripple-button-primary btn-sm" type="button">
                                                    <div class="ripple-ripple js-ripple">
                                                      <span class="ripple-ripple__circle"></span>
                                                    </div>
                                                    Ripple Effect
                                                  </button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab panes ends -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--  Rightbar Area Ends -->
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
    <script src="/admin/assets/js/loader.js"></script>
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