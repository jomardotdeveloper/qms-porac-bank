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
        <li class="breadcrumb-item" aria-current="page"><span>{{ $role->name }}</span></li>
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
                                        <h4>{{ $role->name }} Role</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Name 
                                            <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ $role->name }}" placeholder="Enter Name" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Branch 
                                            <span class="text-danger">*</span></label>
                                            <select class="form-control basic" name="branch_id" disabled>
                                                <option value="{{ $role->branch->id }}">{{ $role->branch->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Permissions 
                                            <span class="text-danger">*</span></label>
                                            <select class="form-control multiple" multiple="multiple" name="permissions[]" disabled>
                                                @foreach($role->permissions as $permission)
                                                <option value="{{ $permission->id }}" selected>{{ $permission->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-footer text-right">
                                <form action="{{ route('roles.destroy', ['role' => $role]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" value="Delete" class="btn btn-danger mr-2">
                                    <a href="{{ route('roles.edit', ['role' => $role]) }}" class="btn btn-primary mr-2">
                                        Edit
                                    </a>
                                </form>
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
<script src="/admin/plugins/select2/select2.min.js"></script>
<script>
    $(".basic").select2();
    $(".multiple").select2();
</script>
@endpush