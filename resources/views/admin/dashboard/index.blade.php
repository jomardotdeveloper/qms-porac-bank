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
                                    @if(in_array("SA", auth()->user()->profile->role->getPermissionCodenamesAttribute()) && in_array("RA", auth()->user()->profile->role->getPermissionCodenamesAttribute()) && in_array("AA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted text-truncate mb-2">Users</p>
                                            <h5 class="mb-0">{{ count($profiles) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted text-truncate mb-2">Accounts</p>
                                            <h5 class="mb-0">{{ count($accounts) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted mb-2">Transactions</p>
                                            <h5 class="mb-0">{{ count($transactions) }}</h5>
                                        </div>
                                    </div>
                                    @elseif(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute()) && auth()->user()->profile->window != null)
                                    <div class="col-4">
                                        @php($timestamp = strtotime(auth()->user()->profile->created_at))
                                        <div>
                                            <p class="text-muted text-truncate mb-2">User since</p>
                                            <h5 class="mb-0">{{ date("Y", $timestamp) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted text-truncate mb-2">My Transactions</p>
                                            <h5 class="mb-0">{{ count(auth()->user()->profile->transactions) }}</h5>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div>
                                            <p class="text-muted mb-2">Services</p>
                                            <h5 class="mb-0">{{ count(auth()->user()->profile->services) }}</h5>
                                        </div>
                                    </div>
                                    @endif
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
                            @if($queue_data["priority"] < 1 && $queue_data["regular"] < 1)
                            <h5 class="text-white">No Pending Tasks</h5>
                            @else
                            <h5 class="text-white">Pending Tasks !</h5>
                            @endif
                            
                            @if($queue_data["priority"] > 0 || $queue_data["regular"] > 0)
                            <p class="blink_me text-white mt-1">Waiting Customers</p>
                            @endif
                            
                            <ul class="pl-3 mb-0">
                                @if($queue_data["priority"] > 0)
                                <li class="py-1">Priority : {{ $queue_data["priority"] }}</li>
                                @endif
                                @if($queue_data["regular"] > 0)
                                <li class="py-1">Regular : {{ $queue_data["regular"] }}</li>
                                @endif
                            </ul>
                            @if($queue_data["priority"] < 1 && $queue_data["regular"] < 1)
                            <p class="text-white mt-1">
                                Queue is empty.
                            </p>
                            @endif
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
    @if(auth()->user()->is_admin)
    <div class="col-6">
        <div class="widget bg-gradient-info">
            <div class="f-100">
                <div class="row">
                    <div class="col-md-7">
                        <div class="text-white">
                            <h5 class="text-white">Feedbacks!</h5>
                            @if(count($feedbacks) < 1)
                            <p class="text-white mt-1">No feedback yet!</p>
                            @else
                            <p class="blink_me text-white mt-1">Recent feedback!</p>
                            @endif

                            @if(count($feedbacks) < 1)
                            <p class="text-white mt-1">There is no recent feedback.</p>
                            @else
                            <p class="text-white mt-1">From : {{ $recent_feedback->email }}</p>
                            <a href="{{ route('feedbacks.show', ['feedback' => $recent_feedback]) }}" class="btn btn-primary">
                                Show details
                            </a>
                            @endif


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
    @if(!auth()->user()->is_admin)
    @if(in_array("SA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
    <div class="col-6">
        <div class="widget bg-gradient-info">
            <div class="f-100">
                <div class="row">
                    <div class="col-md-7">
                        @php($cutoff_today =auth()->user()->profile->branch->cutoff->getDayToday())
                        <div class="text-white">
                            <h5 class="text-white">Cut off</h5>
                            <p class="blink_me text-white mt-1">Today  <strong>({{ $cutoff_today[0] }})</strong></p>
                            <p class="text-white mt-1">
                                @if($cutoff_today[1])
                                {{ $cutoff_today[1] }}
                                @else
                                No cut off set for today
                                @endif
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

<div class="row mt-4">
    <div class="col-4">
        <div class="widget">
            <div class="d-flex align-items-center mb-3">
                <div class="mr-3">
                    <span class="quick-category-icon qc-primary rounded-circle">
                        <i class="las la-sun"></i>
                    </span>
                </div>
                <h5 class="font-size-14 mb-0">Day</h5>
            </div>
            <div class="text-muted mt-3">
                <h5 class="mb-2">{{$period["day"]["now"]}}
                    @if($period["day"]["is_decreased"] == 0)
                    <i class="las la-angle-up text-success-teal"></i>
                    @elseif($period["day"]["is_decreased"] == 1)
                    <i class="las la-angle-down text-success-teal"></i>
                    @endif
                </h5>
                <div class="d-flex">
                    @if($period["day"]["is_decreased"] == 1)
                    <span class="badge badge-danger font-size-12"> 
                        - {{ round($period["day"]["percent"], 2) }} %
                    </span> 
                    @elseif($period["day"]["is_decreased"] == 0)
                    <span class="badge badge-success-teal font-size-12"> 
                        + {{ round($period["day"]["percent"], 2) }} %
                    </span> 
                    @else
                    <span class="badge badge-secondary font-size-12"> 
                         {{ $period["day"]["percent"] }} %
                    </span> 
                    @endif  
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
                        <i class="las la-calendar"></i>
                    </span>
                </div>
                <h5 class="font-size-14 mb-0">Month</h5>
            </div>
            <div class="text-muted mt-3">
                <h5 class="mb-2">{{$period["month"]["now"]}}
                    @if($period["month"]["is_decreased"] == 0)
                    <i class="las la-angle-up text-success-teal"></i>
                    @elseif($period["month"]["is_decreased"] == 1)
                    <i class="las la-angle-down text-success-teal"></i>
                    @endif
                </h5>
                <div class="d-flex">
                    @if($period["month"]["is_decreased"] == 1)
                    <span class="badge badge-danger font-size-12"> 
                        - {{ round($period["month"]["percent"], 2) }} %
                    </span> 
                    @elseif($period["month"]["is_decreased"] == 0)
                    <span class="badge badge-success-teal font-size-12"> 
                        + {{ round($period["month"]["percent"], 2) }} %
                    </span> 
                    @else
                    <span class="badge badge-secondary font-size-12"> 
                            {{ $period["month"]["percent"] }} %
                    </span> 
                    @endif  
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
                        <i class="las la-calendar-check"></i>
                    </span>
                </div>
                <h5 class="font-size-14 mb-0">Year</h5>
            </div>
            <div class="text-muted mt-3">
                <h5 class="mb-2">
                    {{$period["year"]["now"]}}
                    @if($period["year"]["is_decreased"] == 0)
                    <i class="las la-angle-up text-success-teal"></i>
                    @elseif($period["year"]["is_decreased"] == 1)
                    <i class="las la-angle-down text-success-teal"></i>
                    @endif
                </h5>
                <div class="d-flex">
                    @if($period["year"]["is_decreased"] == 1)
                    <span class="badge badge-danger font-size-12"> 
                        - {{ round($period["year"]["percent"], 2) }} %
                    </span> 
                    @elseif($period["year"]["is_decreased"] == 0)
                    <span class="badge badge-success-teal font-size-12"> 
                        + {{ round($period["year"]["percent"], 2) }} %
                    </span> 
                    @else
                    <span class="badge badge-secondary font-size-12"> 
                            {{ $period["year"]["percent"] }} %
                    </span> 
                    @endif  
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
                <h5 class="">Transaction Comparison</h5>
                <ul class="tabs tab-pills">
                    <li>
                        <div class="dropdown  custom-dropdown-icon">
                            <a class="dropdown-toggle" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span id="period">Monthly</span> <i class="las la-angle-down"></i></a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                                <a class="dropdown-item" data-value="Settings" href="javascript:void(0);" onclick="monthly()">Monthly</a>
                                <a class="dropdown-item" data-value="Settings" href="javascript:void(0);" onclick="quarterly()">Quarterly</a>
                                <a class="dropdown-item" data-value="Settings" href="javascript:void(0);" onclick="halfYearly()">Half Yearly</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="widget-content">
                <div class="tabs tab-content">
                    <div id="content_1" class="tabcontent"> 
                        <div id="transaction_chart"></div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-success text-center">
                                                <i class="las la-check-circle pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0"><span data-plugin="counterup">{{ $apex_data["total_success"]["count"] }}</span></h6>
                                                <p class="text-muted mb-1">Successful Transactions</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-danger text-center">
                                                <i class="las la-times-circle pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0"><span data-plugin="counterup">{{ $apex_data["total_drop"]["count"] }}</span></h6>
                                                <p class="text-muted mb-1">Dropped Transactions</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-secondary text-center">
                                                <i class="las la-exclamation pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0"><span data-plugin="counterup">{{ $apex_data["total_un"]["count"] }}</span></h6>
                                                <p class="text-muted mb-1">Unsettled Transactions</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="widget-rounded-circle card-box">
                                    <div class="row">
                                        <div class="col-4 pt-1">
                                            <div class="avatar-sm rounded-circle bg-primary text-center">
                                                <i class="las la-money-bill pt-1 font-25"></i>
                                            </div>
                                        </div>
                                        <div class="col-8 pl-0">
                                            <div class="text-left">
                                                <h6 class="mt-1 mb-0"><span data-plugin="counterup">{{ $apex_data["total_overall"]["count"] }}</span></h6>
                                                <p class="text-muted mb-1 text-truncate">Total Transactions</p>
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
<script>
    var options = {
        series: [{
        name: 'Successful Transactions',
        type: 'area',
        
        data: [100, 5500, 5100]
        },{
        name: 'Dropped Transactions',
        type: 'line',
        data: [5500, 6900, 4500]
        },{
        name: 'Unsettled Transactions',
        type: 'line',
        
        data: [5000, 5000, 4000]
        }],
        chart: {
        type: 'line',
        fontFamily: 'Poppins, sans-serif',
        height: 320,
        dropShadow: {
            enabled: true,
            opacity: 0.1,
            blur: 5,
            left: -7,
            top: 22
        },
        toolbar: {
            show: false
        },
        },
        colors: [  '#8dbf42', '#e7515a', '#5c1ac3'],
        stroke: {
        curve: 'smooth'
        },
        fill: {
        type:'solid',
        opacity: [0.35, 1],
        },
        labels: ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'],
        markers: {
        size: 0
        },
        xaxis: {
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false
        },
        crosshairs: {
            show: true
        },
        labels: {
            offsetX: 0,
            offsetY: 5,
            style: {
                fontSize: '12px',
                fontFamily: 'Poppins, sans-serif',
                cssClass: 'apexcharts-xaxis-title',
            },
        }
        },
        yaxis: {
        labels: {
            formatter: function(value, index) {
            return value
            },
            offsetX: -22,
            offsetY: 0,
            style: {
                fontSize: '12px',
                fontFamily: 'Poppins, sans-serif',
                cssClass: 'apexcharts-yaxis-title',
            },
        }
        },
        legend: {
        position: 'top',
        horizontalAlign: 'right',
        offsetY: -50,
        fontSize: '13px',
        fontFamily: 'Poppins, sans-serif',
        markers: {
            width: 10,
            height: 10,
            strokeWidth: 0,
            strokeColor: '#fff',
            fillColors: undefined,
            radius: 12,
            onClick: undefined,
            offsetX: 0,
            offsetY: 0
        },    
        itemMargin: {
            horizontal: 0,
            vertical: 20
        }
        },
        tooltip: {
        theme: 'dark',
        marker: {
            show: true,
        },
        x: {
            show: false,
        }
        },
    };
    var transaction_chart = new ApexCharts(
        document.querySelector("#transaction_chart"),
        options
    );
    transaction_chart.render();

    var period = $("#period");

    function monthly(){
        period.html("Monthly");
        // options.label =  ['JAN','FEB','MAR','APR','MAY','JUN','JUL','AUG','SEP','OCT','NOV','DEC'];
    }

    function quarterly(){
        period.html("Quarterly");
        
        transaction_chart.destroy();
        // options.labels = ['Q1 First Quarter', 'Q2 Second Quarter', 'Q3 Third Quarter', 'Q4 Fourth Quarter'];
    }

    function halfYearly(){
        period.html("Half Yearly");
        // options.label = ['First Half-Year Period', 'Second Half-Year Period'];
    }

</script>
@endpush