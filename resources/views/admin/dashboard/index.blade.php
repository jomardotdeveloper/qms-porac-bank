@extends("layouts.admin-master")
@section("title", "Dashboard")
@section("custom-styles")
<link href="/admin/assets/css/dashboard/dashboard_2.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/elements/tooltip.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
@endsection
@section("content")
<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget top-welcome">
        <div class="f-100">
            <div class="row">
                <div class="col-lg-4">
                    <div class="media">
                        <div class="mr-3">
                            <img src="/admin/assets/img/profile-16.jpg" alt="" class="avatar-md rounded-circle img-thumbnail">
                        </div>
                        <div class="align-self-center media-body">
                            <div class="text-muted">
                                <p class="mb-2 text-primary">Welcome to dashboard</p>
                                <h5 class="mb-1">Saira Lampano</h5>
                                <p class="mb-0">Teller</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="align-self-center col-lg-5">
                    <div class="text-lg-center mt-4 mt-lg-0">
                        <div class="row">
                            <div class="col-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Projects</p>
                                    <h5 class="mb-0">48</h5>
                                </div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Team</p>
                                    <h5 class="mb-0">40</h5>
                                </div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Clients</p>
                                    <h5 class="mb-0">18</h5>
                                </div>
                            </div>
                            <div class="col-3">
                                <div>
                                    <p class="text-muted text-truncate mb-2">Sellers</p>
                                    <h5 class="mb-0">98</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-none d-lg-flex col-lg-3 align-items-end justify-content-center flex-column">
                    <button class="btn btn-primary">
                        Settings
                    </button>
                    <button class="btn btn-info mt-2">
                        My Chat
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="widget bg-gradient-danger">
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
<div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 layout-spacing">
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="widget">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="quick-category-icon qc-primary rounded-circle">
                            <i class="las la-shopping-cart"></i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">Orders</h5>
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
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="widget">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="quick-category-icon qc-primary rounded-circle">
                            <i class="las la-hand-holding-usd"></i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">Profit</h5>
                </div>
                <div class="text-muted mt-3">
                    <h5 class="mb-2">$200 
                        <i class="las la-angle-down text-danger"></i>
                    </h5>
                    <div class="d-flex">
                        <span class="badge badge-danger font-size-12"> - 5.4% </span> 
                        <span class="ml-2 text-truncate">From last month</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
            <div class="widget">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="quick-category-icon qc-primary rounded-circle">
                            <i class="las la-user"></i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0">Customer</h5>
                </div>
                <div class="text-muted mt-3">
                    <h5 class="mb-2">9,887 
                        <i class="las la-angle-up text-success-teal"></i>
                    </h5>
                    <div class="d-flex">
                        <span class="badge badge-success-teal font-size-12"> + 25% </span> 
                        <span class="ml-2 text-truncate">From last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push("custom-scripts")
@endpush