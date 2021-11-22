@extends("layouts.admin-master")
@section("title", "Dashboard")
@section("custom-styles")
<link href="/admin/assets/css/pages/profile_edit.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/admin/plugins/dropify/dropify.min.css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('my_profile.index') }}" class="text-primary">Profile</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>Edit</span></li>
    </ol>
</nav>
@endsection
@section("content")
<div class="account-content">
    <div class="scrollspy-example" data-spy="scroll" data-target="#account-settings-scroll" data-offset="-100">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 layout-spacing">
                <div id="general-info" class="section general-info">
                    <div class="info">
                        <div class="d-flex mt-2">
                            <div class="profile-edit-left">
                                <div class="tab-options-list">
                                    <div class="nav flex-column nav-pills mb-sm-0 mb-3   text-center mx-auto" id="v-border-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link {{ !$errors->any() ? 'active' : '' }}" id="v-border-pills-general-tab" data-toggle="pill" href="#v-border-pills-general" role="tab" aria-controls="v-border-pills-general" aria-selected="true"><i class="las la-info"></i> General Information</a>
                                        <a class="nav-link  text-center {{ $errors->any() ? 'active' : '' }}" id="v-border-pills-security-tab" data-toggle="pill" href="#v-border-pills-security" role="tab" aria-controls="v-border-pills-security" aria-selected="false"><i class="las la-lock"></i> Security</a>
                                    </div>
                                </div>
                            </div>
                            <div class="profile-edit-right">
                                <div class="tab-content" id="v-border-pills-tabContent">
                                    <div class="tab-pane fade {{ !$errors->any() ? 'show active' : '' }}" id="v-border-pills-general" role="tabpanel" aria-labelledby="v-border-pills-general-tab">
                                        <div class="row">
                                            <div class="col-xl-3 col-lg-12 col-md-12">
                                                <div class="upload text-center img-thumbnail">
                                                    @if(auth()->user()->profile->photo)
                                                    <input type="file" id="input-file-max-fs" class="dropify" data-default-file="{{ auth()->user()->profile->photo }}" data-max-file-size="2M">
                                                    @else
                                                    <input type="file" id="input-file-max-fs" class="dropify" data-default-file="/admin/assets/img/default.png" data-max-file-size="2M">
                                                    @endif
                                                    <p class="mt-2"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>
                                                </div>
                                            </div>
                                            <div class="col-xl-9 col-lg-12 col-md-12 mt-md-0 mt-4">
                                                <form action="{{ route('my_profile.update', ['my_profile' => auth()->user()->profile ]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="is_profile" value="1"/>
                                                <div class="form">
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="first_name">First Name</label>
                                                                <input type="text" class="form-control mb-4" name="first_name" placeholder="First Name" value="{{ auth()->user()->profile->first_name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="last_name">Last Name</label>
                                                                <input type="text" class="form-control mb-4" name="last_name" placeholder="Last Name" value="{{ auth()->user()->profile->last_name }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label for="middle_name">Middle Name</label>
                                                                <input type="text" class="form-control mb-4" name="middle_name" placeholder="Middle Name" value="{{ auth()->user()->profile->middle_name }}">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <button class="btn btn-primary" type="submit">Save Changes</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade {{ $errors->any() ? 'show active' : '' }}" id="v-border-pills-security" role="tabpanel" aria-labelledby="v-border-pills-security-tab">
                                    <form action="{{ route('my_profile.update', ['my_profile' => auth()->user()->profile ]) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        @if($errors->any())
                                        <div class="alert alert-danger mb-4" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                                <i class="las la-times"></i>
                                            </button> 
                                            @foreach($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="old_password">Old Password</label>
                                                    <input type="password" class="form-control mb-4" name="old_password" placeholder="Old Password" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="new_password">New Password</label>
                                                    <input type="password" class="form-control mb-4" name="new_password" placeholder="New Password" required>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label for="confirm_password">Confirm Password</label>
                                                    <input type="password" class="form-control mb-4" name="confirm_password" placeholder="Confirm Password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                            </div>
                                        </div>
                                    </div>
                                    </form>
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
<script src="/admin/plugins/dropify/dropify.min.js"></script>
<script src="/admin/assets/js/pages/profile_edit.js"></script>
@endpush