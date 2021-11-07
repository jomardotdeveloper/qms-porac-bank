@extends("layouts.admin-master")
@section("title", "Branches")
@section("custom-styles")
<link href="/admin/assets/css/dashboard/dashboard_2.css" rel="stylesheet" type="text/css">
<script src="/admin/plugins/sweetalerts/promise-polyfill.js"></script>
<link href="/admin/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/basic-ui/custom_sweetalert.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/animate/animate.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/tables/tables.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css">
@endsection

@section("content")
<input type="hidden" id="window_id" value="{{ auth()->user()->profile->window->id }}"/>
<input type="hidden" id="branch_id" value="{{ auth()->user()->profile->branch->id }}"/>
<div class="layout-top-spacing mb-2">
    <div class="row">   
        <div class="col-md-12">
            <div class="widget ">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="quick-category-icon qc-primary rounded-circle">
                            <i class="las la-user-tie"></i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0 text-primary">Current Serving</h5>
                </div>
                <div class="row">
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Full name</p>
                        <h5 class="mb-0" id="cur_fullname">NONE</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Token</p>
                        <h5 class="mb-0" id="cur_token">NONE</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Account Number</p>
                        <h5 class="mb-0" id="cur_account_number">NONE</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Customer Type</p>
                        <h5 class="mb-0" id="cur_customer_type">NONE</h5>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Reference No.</p>
                        <h5 class="mb-0" id="cur_ref_number">NONE</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Service</p>
                        <h5 class="mb-0"  id="cur_service">NONE</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Amount</p>
                        <h5 class="mb-0"  id="cur_amount">NONE</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6">
            <div class="widget">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="quick-category-icon qc-primary rounded-circle">
                            <i class="las la-hand-point-right"></i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0 text-primary">Next Customer</h5>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Full name</p>
                        <h5 class="mb-0"  id="next_fullname">NONE</h5>
                    </div>
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Token</p>
                        <h5 class="mb-0" id="next_token">NONE</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="widget">
                <div class="d-flex align-items-center mb-3">
                    <div class="mr-3">
                        <span class="quick-category-icon qc-primary rounded-circle">
                            <i class="las la-hand-point-left"></i>
                        </span>
                    </div>
                    <h5 class="font-size-14 mb-0 text-primary">Previous Customer&nbsp;</h5>
                    <span class="badge badge-danger" id="prev_status">NONE</span>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Full name</p>
                        <h5 class="mb-0" id="prev_fulltime">NONE</h5>
                    </div>
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Token</p>
                        <h5 class="mb-0" id="prev_token">NONE</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3">
        <div class="col-2">
            <button type="button" class="btn btn-primary btn-lg h-100 w-100" id="nextCustomer">
                <span class="btn-label" style="background:transparent;"><i class="las la-chevron-circle-right"></i></span>Next
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-danger btn-lg h-100 w-100" id="dropCustomer">
                <span class="btn-label"  style="background:transparent;"><i class="las la-trash"></i></span>Drop
            </button>
        </div>
        <div class="col-2 ">
            <button type="button" class="btn btn-warning btn-lg h-100 w-100 " id="ring">
                <span class="btn-label "  style="background:transparent;"><i class="las la-bell"></i></span>Ring
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-secondary btn-lg h-100 w-100" data-toggle="modal" data-target="#switchModal">
                <span class="btn-label"  style="background:transparent;"><i class="las la-exchange-alt"></i></span>Transfer
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-info btn-lg h-100 w-100" data-toggle="modal" data-target="#listOfCustomer">
                <span class="btn-label"  style="background:transparent;"><i class="las la-clipboard-list"></i></span>List
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-success btn-lg h-100 w-100" id="startQueue">
                <span class="btn-label"  style="background:transparent;"><i class="las la-play-circle"></i></span>Start
            </button>
        </div>
    </div>
</div>
<!-- LIST OF CUSTOMERS -->
<div id="listOfCustomer" class="modal animated fadeInDown" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer List</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table mb-0 text-center" id="listCustomerTable">
                        <thead>
                            <tr>
                                <th>Ref #</th>
                                <th>Token</th>
                                <th>Status</th>
                                <th>Account Number</th>
                                <th>Full Name</th>
                                <th>Customer Type</th>
                                <th class="no-content"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Mark</td>
                                <td>Otto</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Close</button>
            </div>
        </div>
    </div>
</div>

<!-- NOTIFY MODAL -->
<div id="notifyModal" class="modal animated fadeInDown" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Notify Users</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table mb-0 text-center">
                        <thead>
                            <tr>
                                <th>Ref #</th>
                                <th>Token</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">1</th>
                                <td>Mark</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">2</th>
                                <td>Mark</td>
                                <td>Otto</td>
                            </tr>
                            <tr>
                                <th scope="row">3</th>
                                <td>Mark</td>
                                <td>Otto</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Close</button>
            </div>
        </div>
    </div>
</div>

<!-- SWITCH MODAL -->
<div id="switchModal" class="modal animated fadeInDown" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Switch Current Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Switch to Window 
                    <span class="text-danger">*</span></label>
                    <select class="form-control basic" name="window_id" id="window_select" required>
                        @foreach($windows as $window)
                        <option value="{{ $window->id }}">{{ $window->name }}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Close</button>
                <button class="btn btn-primary" id="switch">Switch</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push("custom-scripts")
<script src="/admin/plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="/admin/assets/js/basicui/sweet_alerts.js"></script>
<script src="/admin/plugins/notification/snackbar/snackbar.min.js"></script>
<script src="/admin/assets/js/basicui/notifications.js"></script>
<script src="/admin/assets/js/control/control_v2.js"></script>
@endpush