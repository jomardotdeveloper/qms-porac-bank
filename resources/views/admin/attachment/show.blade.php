@extends("layouts.admin-master")
@section("title", "Attachments")
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('attachments.index') }}" class="text-primary">Attachments</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>{{ $attachment->name }}</span></li>
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
                                        <h4>{{ $attachment->name }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Name 
                                            <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name" value="{{ $attachment->name }}" placeholder="Enter Name" disabled/>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Description 
                                            <span class="text-danger">*</span></label>
                                            <textarea name="description" class="form-control" id="description" cols="30" rows="3" disabled>{{ $attachment->description }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>File 
                                            <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ $attachment->file_name }}" disabled/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-footer text-right">
                                <form action="{{ route('attachments.destroy', ['attachment' => $attachment]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" value="Delete" class="btn btn-danger mr-2">
                                    <a href="{{ route('attachments.edit', ['attachment' => $attachment]) }}" class="btn btn-primary mr-2">
                                        Edit
                                    </a>
                                    <a href="{{ route('attachments.download', ['attachment' => $attachment]) }}" class="btn btn-info mr-2">
                                        Download
                                    </a>
                                    @if($attachment->is_active)
                                    <a href="{{ route('attachments.active', ['attachment' => $attachment]) }}" class="btn btn-danger mr-2">
                                        Set Inactive
                                    </a>
                                    @else
                                    <a href="{{ route('attachments.active', ['attachment' => $attachment]) }}" class="btn btn-success mr-2">
                                        Set Active
                                    </a>
                                    @endif
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