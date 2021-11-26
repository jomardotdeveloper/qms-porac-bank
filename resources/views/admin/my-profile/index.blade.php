@extends("layouts.admin-master")
@section("title", "Dashboard")
@section("custom-styles")
<link href="/admin/assets/css/pages/profile.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <!-- <li class="breadcrumb-item"><a href="javascript:void(0);">Branches</a></li> -->
        <li class="breadcrumb-item" aria-current="page"><span>Profile</span></li>
    </ol>
</nav>
@endsection
@section("content")
<div class="row layout-spacing pt-4">
    <div class="col-xl-3 col-lg-4 col-md-4 mb-4">
        <div class="profile-left">
            <div class="image-area">
                @if(auth()->user()->profile->photo)
                <img class="user-image" src="{{auth()->user()->profile->photo}}">
                @else
                <img class="user-image" src="/admin/assets/img/default.png">
                @endif
                <a href="{{ route('my_profile.edit', ['my_profile' => auth()->user()->profile]) }}" class="follow-area">
                    <i class="las la-pen"></i> 
                </a>
            </div>
            <div class="info-area">
                <h6>{{ auth()->user()->profile->full_name }}</h6>
                @if(auth()->user()->is_admin)
                <p>Administrator</p>
                @else
                <p>{{ auth()->user()->profile->role->name }}</p>
                @endif
            </div>
            <div class="profile-tabs">
                <div class="nav flex-column nav-pills mb-sm-0 mb-3 mx-auto" id="v-border-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link active" id="v-border-pills-home-tab" data-toggle="pill" href="#v-border-pills-home" role="tab" aria-controls="v-border-pills-home" aria-selected="true">General Information</a>
                    @if(!auth()->user()->is_admin)
                    @if(in_array("UA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
                    <a class="nav-link" id="v-border-pills-team-tab" data-toggle="pill" href="#v-border-pills-team" role="tab" aria-controls="v-border-pills-team" aria-selected="false">Team</a>
                    @endif
                    @endif
                    @if(!auth()->user()->is_admin)
                    @if(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute()))
                    @if(auth()->user()->profile->window != null) 
                    <a class="nav-link" id="v-border-pills-team-tab" data-toggle="pill" href="#v-border-pills-window" role="tab" aria-controls="v-border-pills-team" aria-selected="false">Windows Information</a>
                    @endif
                    @endif
                    @endif
                    @if(!auth()->user()->is_admin)
                    <a class="nav-link" id="v-border-pills-team-tab" data-toggle="pill" href="#v-border-pills-my-branch" role="tab" aria-controls="v-border-pills-team" aria-selected="false">Branch Information</a>
                    @endif
                    @if(auth()->user()->is_admin)
                    <a class="nav-link" id="v-border-pills-orders-tab" data-toggle="pill" href="#v-border-pills-orders" role="tab" aria-controls="v-border-pills-orders" aria-selected="false">Branches</a>
                    <a class="nav-link" id="v-border-pills-products-tab" data-toggle="pill" href="#v-border-pills-products" role="tab" aria-controls="v-border-pills-products" aria-selected="false">Top branches</a>
                    @endif
                   
                </div>
            </div>
            <div class="highlight-info">
                <img src="/admin/assets/img/appicon123.png">
                <div class="highlight-desc">
                    <p>PORAC BANK QMS</p>
                    @if(auth()->user()->is_admin)
                    <p>Admin Profile</p>
                    @else
                    <p>{{ auth()->user()->profile->role->name }}&nbsp;Profile</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-lg-8 col-md-8">
        <div class="cover-image-area">
            <img src="/admin/assets/img/porac-cover.jpg" class="cover-img"/>
        </div>
        <div class="row tab-area-content">
            <div class="col-xl-12 col-lg-12 col-md-12 mb-4">
                <div class="tab-content" id="v-border-pills-tabContent">
                    <div class="tab-pane fade show active" id="v-border-pills-home" role="tabpanel" aria-labelledby="v-border-pills-home-tab">
                        <div class="profile-info">
                            <h5>General Information</h5>
                            <div class="single-profile-info">
                                <h6>First Name</h6>
                                <p>{{ auth()->user()->profile->first_name }}</p>
                            </div>
                            <div class="single-profile-info">
                                <h6>Last Name</h6>
                                <p>{{ auth()->user()->profile->last_name }}</p>
                            </div>
                            <div class="single-profile-info">
                                <h6>Middle Name</h6>
                                <p>{{ auth()->user()->profile->middle_name }}</p>
                            </div>
                            <div class="single-profile-info">
                                <h6>Username</h6>
                                <p>{{ auth()->user()->username }}</p>
                            </div>
                            <div class="single-profile-info">
                                <h6>Role</h6>
                                @if(auth()->user()->is_admin)
                                <p>Admin</p>
                                @else
                                <p>{{ auth()->user()->profile->role->name }}</p>
                                @endif
                            </div>
                            @if(!auth()->user()->is_admin)
                            <div class="single-profile-info">
                                <h6>Branch</h6>
                                <p>{{ auth()->user()->profile->branch->name }}</p>
                            </div>
                            @endif
                            @if(!auth()->user()->is_admin)
                            <div class="single-profile-info">
                                <h6>Services</h6>
                                @foreach(auth()->user()->profile->services as $service)
                                <span class="badge badge-primary mt-2"> {{$service->name}} </span>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    </div>
                    @if(!auth()->user()->is_admin)
                    @if(in_array("UA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
                    <div class="tab-pane fade" id="v-border-pills-team" role="tabpanel" aria-labelledby="v-border-pills-team-tab">
                        <div class="media">
                            <div class="profile-shadow w-100">
                                <h6 class="font-15 mb-3">Team</h6>
                                @foreach($users as $user)
                                <div class="single-team">
                                    <div class="d-flex">
                                        <div class="team-left">
                                            @if($user->photo)
                                            <img src="{{$user->photo}}">
                                            @else
                                            <img src="/admin/assets/img/default.png">
                                            @endif
                                        </div>
                                        <div class="team-right">
                                            <h6>{{ $user->full_name }}</h6>
                                            @php($timestamp = strtotime($user->created_at))
                                            <p>USER SINCE {{ date("Y", $timestamp) }} </p>
                                            <span class="badge badge-info"> {{$user->role->name}} </span>
                                            <ul>
                                                <li class="text-success-teal"><strong>Success</strong> : {{ count($user->getSuccessfulTransactionsAttribute()) }} Transactions</li>
                                                <li class="text-danger"><strong>Drop</strong> :{{ count($user->getDropTransactionsAttribute()) }} Transactions</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @if(!auth()->user()->is_admin)
                    @if(in_array("CA", auth()->user()->profile->role->getPermissionCodenamesAttribute())) 
                    @if(auth()->user()->profile->window != null) 
                    <div class="tab-pane fade" id="v-border-pills-window" role="tabpanel" aria-labelledby="v-border-pills-team-tab">
                        <div class="media">
                            <div class="profile-shadow w-100">
                                <h6 class="font-15 mb-3">
                                    {{ auth()->user()->profile->window->name }} 
                                    @if(auth()->user()->profile->window->is_priority)
                                    (Priority Window)
                                    @endif
                                </h6> 
                                <div class="row">
                                    <div class="col-4">
                                        <div class="card text-center">
                                            <div class="card-body ">
                                                <h5 class="card-title text-primary">Successful transaction</h5>
                                                <h2>{{ $window_data["success"]  }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h5 class="card-title text-danger">Dropped transaction</h5>
                                                <h2>{{ $window_data["drop"]  }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="card text-center">
                                            <div class="card-body">
                                                <h5 class="card-title text-warning">Unsettled transaction</h5>
                                                <h2>{{ $window_data["unsettled"]  }}</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @endif
                    @if(!auth()->user()->is_admin)
                    <div class="tab-pane fade" id="v-border-pills-my-branch" role="tabpanel" aria-labelledby="v-border-pills-team-tab">
                        <div class="media">
                            <div class="profile-shadow w-100">
                                <h6 class="font-15 mb-3">{{ auth()->user()->profile->branch->name }} Branch</h6>
                                <div class="progress-list">
                                    <div class="single-progress">
                                        <div class="progress-details">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-2 font-12 text-success">Successful Transactions</h6>
                                                <p class="mb-2 font-12 text-success strong">{{ $branch_data['success']['value'] }}</p>
                                            </div>
                                            <div class="progress-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-success position-relative" role="progressbar" style="width:{{ $branch_data['success']['percentage'] }}%" aria-valuenow="69" aria-valuemin="0" aria-valuemax="100"><span class="success-teal"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-progress">
                                        <div class="progress-details">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-2 font-12 text-danger">Dropped Transactions</h6>
                                                <p class="mb-2 font-12 text-danger strong">{{ $branch_data['drop']['value'] }}</p>
                                            </div>
                                            <div class="progress-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-danger position-relative" role="progressbar" style="width: {{ $branch_data['drop']['percentage'] }}%" aria-valuenow="69" aria-valuemin="0" aria-valuemax="100"><span class="success-teal"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-progress">
                                        <div class="progress-details">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h6 class="mb-2 font-12 text-secondary">Unsettled Transactions</h6>
                                                <p class="mb-2 font-12 text-secondary strong">{{ $branch_data['unsettled']['value'] }}</p>
                                            </div>
                                            <div class="progress-stats">
                                                <div class="progress">
                                                    <div class="progress-bar bg-gradient-secondary position-relative" role="progressbar" style="width: {{ $branch_data['unsettled']['percentage'] }}%" aria-valuenow="69" aria-valuemin="0" aria-valuemax="100"><span class="success-teal"></span></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(auth()->user()->is_admin)
                    <div class="tab-pane fade" id="v-border-pills-orders" role="tabpanel" aria-labelledby="v-border-pills-orders-tab">
                        <div class="media">
                            <div class="profile-shadow w-100">
                                <h6 class="font-15 mb-3">Orders</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th><div class="th-content">Name</div></th>
                                                <th><div class="th-content">Product Key</div></th>
                                                <th><div class="th-content">Number of Transactions</div></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($branches as $branch)
                                            <tr>
                                                <td>
                                                    {{ $branch->name }}
                                                </td>
                                                <td>
                                                    {{ $branch->product_key }}
                                                </td>
                                                <td>
                                                    {{ count($branch->transactions) }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <p class="font-13 text-center mt-4 mb-1 text-muted">
                                        All branches of Porac Bank
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if(auth()->user()->is_admin)
                    <div class="tab-pane fade" id="v-border-pills-products" role="tabpanel" aria-labelledby="v-border-pills-products-tab">
                        <div class="searchable-items card-box grid">
                            <div class="items">
                                <div class="item-content">
                                    <div class="product-info">
                                        <div class="user-meta-info">
                                            <p class="product-name">Top 1</p>
                                            <h6>Arayat</h6>
                                        </div>
                                    </div>
                                    <div class="product-rating">
                                        <p class="product-rating-inner"><span>Successful Transactions: </span>
                                            <a class="d-flex align-center">
                                                5 
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push("custom-scripts")

@endpush