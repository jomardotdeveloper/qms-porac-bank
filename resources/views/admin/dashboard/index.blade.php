@extends("layouts.admin-master")
@section("title", "Dashboard")
@section("custom-styles")
<link href="/admin/assets/css/dashboard/dashboard_2.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/elements/tooltip.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
@endsection
@section("content")
<div class="row">
    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
        <div class="widget top-welcome">
            <div class="f-100">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="media">
                            <div class="mr-3">
                                @if(auth()->user()->profile->photo)
                                <img src="{{auth()->user()->profile->photo}}" alt="" class="avatar-md rounded-circle img-thumbnail">
                                @else
                                <img src="/admin/assets/img/default.png" alt="" class="avatar-md rounded-circle img-thumbnail">
                                @endif
                            </div>
                            <div class="align-self-center media-body">
                                <div class="text-muted">
                                    <p class="mb-2 text-primary">Welcome to dashboard</p>
                                    <h5 class="mb-1">{{ auth()->user()->profile->full_name }}</h5>
                                    @if(auth()->user()->is_admin)
                                    <p class="mb-0">Admin</p>
                                    @else
                                    <p class="mb-0">{{auth()->user()->profile->role->name}}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="align-self-center col-lg-5">
                        <div class="text-lg-center mt-4 mt-lg-0">
                            <div class="row">
                                @if(auth()->user()->is_admin)
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Branches</p>
                                        <h5 class="mb-0">{{ count($branches) }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Users</p>
                                        <h5 class="mb-0">{{ count($profiles) }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Accounts</p>
                                        <h5 class="mb-0">{{ count($accounts) }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted mb-2">Transactions</p>
                                        <h5 class="mb-0">{{ count($transactions) }}</h5>
                                    </div>
                                </div>
                                @else
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Users</p>
                                        <h5 class="mb-0">{{ count($profiles) }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted text-truncate mb-2">Accounts</p>
                                        <h5 class="mb-0">{{ count($accounts) }}</h5>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div>
                                        <p class="text-muted mb-2">Transactions</p>
                                        <h5 class="mb-0">{{ count($transactions) }}</h5>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    @if(!auth()->user()->is_admin)
    @if(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
    @if(auth()->user()->profile->window != null) 
    <div class="col-6">
        <div class="widget bg-gradient-success">
            <div class="f-100">
                <div class="row">
                    <div class="col-md-7">
                        <div class="text-white">
                            <h5 class="text-white">Pending Tasks !</h5>
                            <p class="blink_me text-white mt-1">Waiting Customers</p>
                            <ul class="pl-3 mb-0">
                                <li class="py-1">Priority : 1</li>
                                <li class="py-1">Regular : 3</li>
                            </ul>
                        </div>
                    </div>
                    <div class="align-self-end col-md-5">
                        <img src="/admin/assets/img/dashboard-image-uw.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
    @endif
    <div class="col-6">
        <div class="widget bg-gradient-danger">
            <div class="f-100">
                <div class="row">
                    <div class="col-md-7">
                        <div class="text-white">
                            <h5 class="text-white">Random Quotes!</h5>
                            <p class="blink_me text-white mt-1">Be inspired</p>
                            <p class="text-white mt-1">
                                {{ $quotes}}
                            </p>
                        </div>
                    </div>
                    <div class="align-self-end col-md-5">
                        <img src="/admin/assets/img/dashboard-image-uw.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!auth()->user()->is_admin)
    @if(in_array("SA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
    <div class="col-6">
        <div class="widget bg-gradient-info">
            <div class="f-100">
                <div class="row">
                    <div class="col-md-7">
                        <div class="text-white">
                            <h5 class="text-white">Announcement</h5>
                            <p class="blink_me text-white mt-1">Title</p>
                            <p class="text-white mt-1">
                                Body
                            </p>
                        </div>
                    </div>
                    <div class="align-self-end col-md-5">
                        <img src="/admin/assets/img/dashboard-image-uw.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>

@if(!auth()->user()->is_admin)
@if(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
<div class="row mt-4">
    <div class="col-6">
        <div class="widget bg-gradient-info">
            <div class="f-100">
                <div class="row">
                    <div class="col-md-7">
                        <div class="text-white">
                            <h5 class="text-white">Announcement</h5>
                            <p class="blink_me text-white mt-1">Title</p>
                            <p class="text-white mt-1">
                                Body
                            </p>
                        </div>
                    </div>
                    <div class="align-self-end col-md-5">
                        <img src="/admin/assets/img/dashboard-image-uw.png" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endif

<div class="row mt-4">
    <div class="col-4">
        <div class="widget">
            <div class="d-flex align-items-center mb-3">
                <div class="mr-3">
                    <span class="quick-category-icon qc-primary rounded-circle">
                        <i class="las la-shopping-cart"></i>
                    </span>
                </div>
                <h5 class="font-size-14 mb-0">Day</h5>
            </div>
            <div class="text-muted mt-3">
                <h5 class="mb-2">{{ $period["day"]["now"] }} 
                    <i class="las la-angle-up text-success-teal"></i>
                </h5>
                <div class="d-flex">
                    <span class="badge badge-success-teal font-size-12"> + 0.2% </span> 
                    <span class="ml-2 text-truncate">From last day</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="widget">
            <div class="d-flex align-items-center mb-3">
                <div class="mr-3">
                    <span class="quick-category-icon qc-primary rounded-circle">
                        <i class="las la-shopping-cart"></i>
                    </span>
                </div>
                <h5 class="font-size-14 mb-0">Month</h5>
            </div>
            <div class="text-muted mt-3">
                <h5 class="mb-2">1,452 
                    <i class="las la-angle-up text-success-teal"></i>
                </h5>
                <div class="d-flex">
                    <span class="badge badge-success-teal font-size-12"> + 0.2% </span> 
                    <span class="ml-2 text-truncate">From last month</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="widget">
            <div class="d-flex align-items-center mb-3">
                <div class="mr-3">
                    <span class="quick-category-icon qc-primary rounded-circle">
                        <i class="las la-shopping-cart"></i>
                    </span>
                </div>
                <h5 class="font-size-14 mb-0">Year</h5>
            </div>
            <div class="text-muted mt-3">
                <h5 class="mb-2">1,452 
                    <i class="las la-angle-up text-success-teal"></i>
                </h5>
                <div class="d-flex">
                    <span class="badge badge-success-teal font-size-12"> + 0.2% </span> 
                    <span class="ml-2 text-truncate">From last year</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="widget widget-chart-one">
            <div class="widget-heading">
                <h5 class="">Revenue Comparison</h5>
                <ul class="tabs tab-pills">
                    <li>
                        <div class="dropdown  custom-dropdown-icon">
                            <a class="dropdown-toggle" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>Options</span> <i class="las la-angle-down"></i></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                                <a class="dropdown-item" data-value="Settings" href="javascript:void(0);">Quarterly</a>
                                <a class="dropdown-item" data-value="Settings" href="javascript:void(0);">Half Yearly</a>
                                <a class="dropdown-item" data-value="Mail" href="javascript:void(0);">Mail</a>
                                <a class="dropdown-item" data-value="Print" href="javascript:void(0);">Print</a>
                                <a class="dropdown-item" data-value="Download" href="javascript:void(0);">Download</a>
                                <a class="dropdown-item" data-value="Share" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="widget-content">
                <div class="tabs tab-content">
                    <div id="content_1" class="tabcontent"> 
                        <div id="revenue"></div>
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-primary text-center">
                                                <i class="las la-chart-line pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0">$<span data-plugin="counterup">58,947</span></h6>
                                                <p class="text-muted mb-1 text-truncate">Total Sales</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-success text-center">
                                                <i class="las la-file-invoice-dollar pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0">$<span data-plugin="counterup">45,458</span></h6>
                                                <p class="text-muted mb-1 text-truncate">Total Revenue</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-danger text-center">
                                                <i class="las la-bullseye pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0">$<span data-plugin="counterup">58,000</span></h6>
                                                <p class="text-muted mb-1 text-truncate">Target Revenue</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push("custom-scripts")
<script src="/admin/plugins/apex/apexcharts.min.js"></script>
<script src="/admin/assets/js/dashboard/dashboard_3.js"></script>
@endpush