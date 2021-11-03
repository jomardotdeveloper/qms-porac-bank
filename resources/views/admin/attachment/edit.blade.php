@extends("layouts.admin-master")
@section("title", "Attachments")
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('attachments.index') }}" class="text-primary">Attachments</a></li>
        <li class="breadcrumb-item active"><a href="{{ route('attachments.show', ['attachment' => $attachment]) }}" class="text-primary">{{ $attachment->name }}</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>Edit</span></li>
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
                                        <h4>Editing {{ $attachment->name }}</h4>
                                    </div>
                                </div>
                            </div>
                            <form action="{{ route('attachments.update', ['attachment' => $attachment]) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
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
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label>Name 
                                                <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="name" value="{{ $attachment->name }}" placeholder="Enter Name" required/>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label>Description 
                                                <span class="text-danger">*</span></label>
                                                <textarea name="description" class="form-control" id="description" cols="30" rows="3" required>{{ $attachment->description }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                            <div class="form-group">
                                                <label>Current File : {{ $attachment->file_name }} 
                                                <span class="text-danger">*</span></label>
                                                <input type="file" class="form-control" id="src" name="src"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-footer text-right">
                                    <input type="submit" value="Save Changes" class="btn btn-primary mr-2"/>
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