@extends("layouts.admin-master")
@section("title", "Users")
@section("custom-styles")
<link rel="stylesheet" type="text/css" href="/admin/plugins/dropify/dropify.min.css">
<link href="/admin/assets/css/pages/profile_edit.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/admin/plugins/select2/select2.min.css">
<link href="/admin/assets/css/forms/form-widgets.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('profiles.index') }}" class="text-primary">Users</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>Create</span></li>
    </ol>
</nav>
@endsection
@section("content")
<div class="layout-top-spacing mb-2">
    <div class="col-md-12">
        <div class="row">
            <div class="container p-0">
                <div class="row layout-top-spacing">
                    <div class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow mb-4">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>Create User</h4>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('profiles.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="widget-content widget-content-area">
                                    @if($errors->any())
                                    <div class="alert alert-danger mb-4" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                            <i class="las la-times"></i>
                                        </button> 
                                        <ul>
                                            @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @endif
                                    <div class="row mb-3">
                                        <div class="col-xl-3 col-md-3 col-sm-3 col-3">
                                            <div class="upload text-center img-thumbnail">
                                                <input type="file" id="input-file-max-fs" class="dropify" name="photo" data-default-file="/admin/assets/img/default.png" data-max-file-size="2M">
                                                <p class="mt-2 text-primary font-weight-bold"><i class="flaticon-cloud-upload mr-1"></i> Upload Picture</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <div class="form-group">
                                                <label>Username
                                                <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="username" value="{{ request()->old('username') }}" placeholder="Enter Username" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <div class="form-group">
                                                <label>Password
                                                <span class="text-danger">*</span></label>
                                                <input type="password" class="form-control" name="password" value="{{ request()->old('password') }}" placeholder="Enter Password" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                            <div class="form-group">
                                                <label>First Name 
                                                <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="first_name" value="{{ request()->old('first_name') }}" placeholder="Enter First Name" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                            <div class="form-group">
                                                <label>Last Name 
                                                <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="last_name" value="{{ request()->old('last_name') }}" placeholder="Enter Last Name" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                            <div class="form-group">
                                                <label>Middle Name 
                                                <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="middle_name" value="{{ request()->old('middle_name') }}" placeholder="Enter Middle Name"/>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <div class="form-group">
                                                <label>Branch 
                                                <span class="text-danger">*</span></label>
                                                <select class="form-control basic" name="branch_id" required>
                                                    @foreach($branches as $branch)
                                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <div class="form-group">
                                                <label>Role 
                                                <span class="text-danger">*</span></label>
                                                <select class="form-control basic" name="role_id" required>
                                                    @foreach($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label>Services 
                                                <span class="text-danger">*</span></label>
                                                <select class="form-control multiple" multiple="multiple" name="services[]" required>
                                                    @foreach($services as $service)
                                                    <option value="{{ $service->id }}">{{ $service->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-footer text-right">
                                    <input type="submit" value="Submit" class="btn btn-primary mr-2"/>
                                </div>
                            </form>
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
<script src="/admin/plugins/select2/select2.min.js"></script>
<script>
    $(".basic").select2();
    $(".multiple").select2();
</script>
@endpush