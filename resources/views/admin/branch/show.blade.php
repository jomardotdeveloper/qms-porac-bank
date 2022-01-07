@extends("layouts.admin-master")
@section("title", "Branches")
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('branches.index') }}" class="text-primary">Branches</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>{{ $branch->name }}</span></li>
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
                                        <h4>{{ $branch->name }} Branch</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Name
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ $branch->name }}" placeholder="Enter Name" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Product Key
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="product_key" value="{{ $branch->product_key }}" placeholder="Enter Product Key" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Email
                                                <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email" value="{{ $branch->email }}" placeholder="Enter Email" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-footer text-right">
                                <form action="{{ route('branches.destroy', ['branch' => $branch]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" value="Delete" class="btn btn-danger mr-2">
                                    <a href="{{ route('branches.edit', ['branch' => $branch]) }}" class="btn btn-primary mr-2">
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