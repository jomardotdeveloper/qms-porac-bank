@extends("layouts.admin-master")
@section("title", "Users")
@section("custom-styles")
<link rel="stylesheet" type="text/css" href="/admin/plugins/select2/select2.min.css">
<link href="/admin/assets/css/forms/form-widgets.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="{{ route('profiles.index') }}" class="text-primary">Users</a></li>
        <li class="breadcrumb-item" aria-current="page"><span>{{ $profile->full_name }}</span></li>
    </ol>
</nav>
@endsection
@section("content")
<div class="layout-top-spacing mb-2">
    <div class="col-md-12">
        <div class="row">
            <div class="container p-0">
                <input type="hidden" id="profile_id" value="{{ $profile->id }}"/>
                <button class="btn btn-success" onclick="sink()">Sync</button>
                <div class="row layout-top-spacing">
                    <div class="col-lg-12 layout-spacing">
                        <div class="statbox widget box box-shadow mb-4">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <h4>{{ $profile->full_name }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="row mb-3">
                                    <div class="col">
                                        @if($profile->photo)
                                        <img class="rounded-circle" style="height:7rem;width:7rem;" src="{{$profile->photo}}" alt="">
                                        @else
                                        <img class="rounded-circle" style="height:7rem;width:7rem;" src="/admin/assets/img/default.png" alt="">
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Username
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="username" value="{{ $profile->user->username }}" placeholder="Enter Username" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Password
                                                <span class="text-danger">*</span></label>
                                            <input type="password" class="form-control" name="password" value="*********" placeholder="Enter Password" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                        <div class="form-group">
                                            <label>First Name
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="first_name" value="{{ $profile->first_name }}" placeholder="Enter First Name" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                        <div class="form-group">
                                            <label>Last Name
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="last_name" value="{{ $profile->last_name }}" placeholder="Enter Last Name" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-md-4 col-sm-4 col-4">
                                        <div class="form-group">
                                            <label>Middle Name
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="middle_name" value="{{ $profile->middle_name }}" placeholder="Enter Middle Name" disabled />
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Branch
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control basic" name="branch_id" disabled>
                                                <option value="{{ $profile->branch->id }}" selected>{{ $profile->branch->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="form-group">
                                            <label>Role
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control basic" name="role_id" disabled>
                                                <option value="{{ $profile->role->id }}" selected>{{ $profile->role->name }}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xl-12 col-md-12 col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Services
                                                <span class="text-danger">*</span></label>
                                            <select class="form-control multiple" multiple="multiple" name="services[]" disabled>
                                                @foreach($profile->services as $service)
                                                <option value="{{ $service->id }}" selected>{{ $service->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-footer text-right">
                                <form action="{{ route('profiles.destroy', ['profile' => $profile]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <input type="submit" value="Delete" class="btn btn-danger mr-2">
                                    <a href="{{ route('profiles.edit', ['profile' => $profile]) }}" class="btn btn-primary mr-2">
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
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    $(".basic").select2();
    $(".multiple").select2();
</script>
<script>
    var profile = $("#profile_id").val();

    async function getCurrent() {
        var res = (await axios.get("/api/profile/get_current/" + profile)).data;
        return res;
    }

    async function action_sync(user){
        var res = (await axios.post("http://poracbankqms.com/api/profile/sink", {
            user: user
        })).data;
    }

    async function sink() {
        var current = await getCurrent();
        action_sync(current);
    }
    
</script>
@endpush