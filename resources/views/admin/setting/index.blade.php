@extends("layouts.admin-master")
@section("title", "Accounts")
@section("custom-styles")
<link rel="stylesheet" type="text/css" href="/admin/plugins/dropify/dropify.min.css">
<link href="/admin/assets/css/pages/profile_edit.css" rel="stylesheet" type="text/css">
@endsection
@section("content")
@php($is_window = false)
@php($is_general = false)
@php($is_cutoff = false)
@if($errors->any())
    @if(isset($errors->get("success-window")[0]) || isset($errors->get("failed-window")[0]))
        @php($is_window = true)
    @endif
    @if(isset($errors->get("success")[0]))
        @php($is_general = true)
    @endif
    @if(isset($errors->get("success-cutoff")[0]))
        @php($is_cutoff = true)
    @endif
@endif
<div class="account-settings-container layout-top-spacing">
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
                                            <a class="nav-link {{ $is_general ? 'active' : '' }} {{ $is_general == false && $is_window == false && $is_cutoff == false ? 'active' : '' }}" id="v-border-pills-general-tab" data-toggle="pill" href="#v-border-pills-general" role="tab" aria-controls="v-border-pills-general" aria-selected="true"><i class="las la-info"></i> General Settings</a>
                                            <a class="nav-link {{ $is_window ? 'active' : '' }} text-center" id="v-border-pills-about-tab" data-toggle="pill" href="#v-border-pills-about" role="tab" aria-controls="v-border-pills-about" aria-selected="false"><i class="lar la-user"></i> Windows</a>
                                            <a class="nav-link {{ $is_cutoff ? 'active' : '' }} text-center" id="v-border-pills-domain-tab" data-toggle="pill" href="#v-border-pills-domain" role="tab" aria-controls="v-border-pills-domain" aria-selected="false"><i class="las la-graduation-cap"></i> Cutoff</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile-edit-right">
                                    <div class="tab-content" id="v-border-pills-tabContent">
                                        <div class="tab-pane fade  {{ $is_general ? 'active show' : '' }} {{ $is_general == false && $is_window == false && $is_cutoff == false ? 'active show' : '' }}" id="v-border-pills-general" role="tabpanel" aria-labelledby="v-border-pills-general-tab">
                                            @if($errors->any())
                                                @if(isset($errors->get("success")[0]))
                                                <div class="alert alert-success mb-4" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                                        <i class="las la-times"></i>
                                                    </button> 
                                                    {{$errors->get("success")[0]}}
                                                </div>
                                                @endif
                                            @endif
                                            <form action="{{ route('settings.update', ['setting' => $branch->setting]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="sms_interval">Notification Interval</label>
                                                        <input type="number" class="form-control mb-4" placeholder="Notification Interval" name="sms_interval" value="{{ $branch->setting->sms_interval }}" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group mt-3">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="is_automatic_sms" name="is_automatic_sms" {{ $branch->setting->is_automatic_sms  ? 'checked' : '' }}/>
                                                            <label class="custom-control-label" for="is_automatic_sms">Enable automatic sms notification</label>
                                                        </div>
                                                    </div>  
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="qrcode_interval">QR Code Interval</label>
                                                        <input type="number" class="form-control mb-4" placeholder="QR Code Interval" value="{{ $branch->setting->qrcode_interval }}" name="qrcode_interval" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group mt-3">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" id="is_qrcode_automatic" name="is_qrcode_automatic"  {{ $branch->setting->is_qrcode_automatic  ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="is_qrcode_automatic">Enable automatic qrcode generator</label>
                                                        </div>
                                                    </div>  
                                                </div>

                                                <div class="col-sm-6">
                                                    <input type="submit" value="Save Changes" class="btn btn-primary"/>
                                                </div>
                                                
                                                <div class="col-xl-9 col-lg-12 col-md-12 mt-md-0 mt-4">
                                                    <div class="form">
                                                        <div class="row">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade {{ $is_window ? 'active show' : '' }}" id="v-border-pills-about" role="tabpanel" aria-labelledby="v-border-pills-about-tab">
                                            @if($errors->any())
                                                @if(isset($errors->get("success-window")[0]))
                                                <div class="alert alert-success mb-4" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                                        <i class="las la-times"></i>
                                                    </button> 
                                                    {{$errors->get("success-window")[0]}}
                                                </div>
                                                @endif
                                                @if(isset($errors->get("failed-window")[0]))
                                                <div class="alert alert-danger mb-4" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                                        <i class="las la-times"></i>
                                                    </button> 
                                                    {{$errors->get("failed-window")[0]}}
                                                </div>
                                                @endif
                                            @endif
                                            <form action="{{ route('windows.update', ['window' => $branch->windows[0]]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="id_1" value="{{ $branch->windows[0]->id }}"/>
                                            <input type="hidden" name="id_2" value="{{ $branch->windows[1]->id }}"/>
                                            <input type="hidden" name="id_3" value="{{ $branch->windows[2]->id }}"/>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="window1">Window 1</label>
                                                        <select class="form-control" name="window_1">
                                                            @foreach($branch->profiles as $profile)
                                                                @if($branch->windows[0]->profile_id && $branch->windows[0]->profile_id == $profile->id)
                                                                    <option value="{{ $profile->id }}" selected>
                                                                        {{ $profile->full_name }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $profile->id }}">
                                                                        {{ $profile->full_name }}
                                                                    </option>
                                                                @endif 
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="window2">Window 2</label>
                                                        <select class="form-control" name="window_2">
                                                            @foreach($branch->profiles as $profile)
                                                                @if($branch->windows[1]->profile_id && $branch->windows[1]->profile_id == $profile->id)
                                                                    <option value="{{ $profile->id }}" selected>
                                                                        {{ $profile->full_name }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $profile->id }}">
                                                                        {{ $profile->full_name }}
                                                                    </option>
                                                                @endif 
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="window3">Window 3</label>
                                                        <select class="form-control" name="window_3">
                                                            @foreach($branch->profiles as $profile)
                                                                @if($branch->windows[2]->profile_id && $branch->windows[2]->profile_id == $profile->id)
                                                                    <option value="{{ $profile->id }}" selected>
                                                                        {{ $profile->full_name }}
                                                                    </option>
                                                                @else
                                                                    <option value="{{ $profile->id }}">
                                                                        {{ $profile->full_name }}
                                                                    </option>
                                                                @endif 
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="priority_window">Priority Window</label>
                                                        <select class="form-control" name="priority_window">
                                                            @foreach($branch->windows as $window)
                                                            <option value="{{ $window->id }}" {{ $window->is_priority ? 'selected' : '' }}>
                                                                {{ $window->name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="submit" value="Save Changes" class="btn btn-primary"/>
                                                </div>
                                            </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade {{ $is_cutoff ? 'active show' : '' }}" id="v-border-pills-domain" role="tabpanel" aria-labelledby="v-border-pills-domain-tab">
                                            @if($errors->any())
                                                @if(isset($errors->get("success-cutoff")[0]))
                                                <div class="alert alert-success mb-4" role="alert">
                                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"> 
                                                        <i class="las la-times"></i>
                                                    </button> 
                                                    {{$errors->get("success-cutoff")[0]}}
                                                </div>
                                                @endif
                                            @endif
                                            <form action="{{ route('cutoffs.update', ['cutoff' => $branch->cutoff]) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="m">Monday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="m" value="{{ $branch->cutoff->m }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="t">Tuesday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="t" value="{{ $branch->cutoff->t }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="w">Wednesday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="w" value="{{ $branch->cutoff->w }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="th">Thursday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="th" value="{{ $branch->cutoff->th }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="f">Friday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="f" value="{{ $branch->cutoff->f }}" />
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="s">Saturday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="s" value="{{ $branch->cutoff->s }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label for="sd">Sunday</label>
                                                        <input type="time" class="form-control mb-4" placeholder="Notification Interval" name="sd" value="{{ $branch->cutoff->sd }}"/>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <input type="submit" value="Save Changes" class="btn btn-primary"/>
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
    </div>
</div>
@endsection

@push("custom-scripts")
<script src="/admin/plugins/dropify/dropify.min.js"></script>
<script src="/admin/assets/js/pages/profile_edit.js"></script>
@endpush