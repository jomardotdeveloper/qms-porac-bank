@extends("layouts.admin-master")
@section("title", "Roles")
@section("custom-styles")
<link rel="stylesheet" type="text/css" href="/admin/plugins/select2/select2.min.css">
<link href="/admin/assets/css/forms/form-widgets.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('roles.index') }}" class="text-primary">Roles</a></li>
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
                                        <h4>Create Role</h4>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('roles.store') }}" method="POST">
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
                                    <div class="row">
                                        <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                            <div class="form-group">
                                                <label>Name 
                                                <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="name" value="{{ request()->old('name') }}" placeholder="Enter Name" required/>
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
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label>Permissions 
                                                <span class="text-danger">*</span></label>
                                                <select class="form-control multiple" multiple="multiple" name="permissions[]" required>
                                                    @foreach($permissions as $permission)
                                                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
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
<script src="/admin/plugins/select2/select2.min.js"></script>
<script>
    $(".basic").select2();
    $(".multiple").select2();
</script>
@endpush