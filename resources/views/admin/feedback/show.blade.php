@extends("layouts.admin-master")
@section("title", "Feedbacks")
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('feedbacks.index') }}" class="text-primary">Feedbacks</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>{{ $feedback->full_name }}</span></li>
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
                                        <h4>Feedback From {{ $feedback->full_name }} </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Full Name 
                                            <span class="text-danger">*</span></label>
                                            <input class="form-control" value="{{ $feedback->full_name }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Email
                                            <span class="text-danger">*</span></label>
                                            <input class="form-control" value="{{ $feedback->email }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Phone Number
                                            <span class="text-danger">*</span></label>
                                            <input class="form-control" value="{{ $feedback->phone_number }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Date
                                            <span class="text-danger">*</span></label>
                                            <input class="form-control" value="{{ $feedback->created_at }}" readonly/>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Message
                                            <span class="text-danger">*</span></label>
                                            <textarea class="form-control" cols="30" rows="10" readonly>{{ $feedback->message }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-footer text-right">
                                
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection