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
<div id="app">
    <input type="hidden" id="window_id" value="{{ auth()->user()->profile->window->id }}"/>
    <input type="hidden" id="branch_id" value="{{ auth()->user()->profile->branch->id }}"/>
    <input type="hidden" id="window_name" value="{{ auth()->user()->profile->window->name }}"/>
    <input type="hidden" id="window_order" value="{{ auth()->user()->profile->window->order }}"/>
    <div class="layout-top-spacing mb-2">
        <div class="row">
            <div class="col-md-4">
                <div class="widget">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <span class="quick-category-icon qc-primary rounded-circle">
                                <i class="las la-user"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0 text-primary">
                            {{ auth()->user()->profile->window->name }} 
                            @if(auth()->user()->profile->window->is_priority)
                            (Priority)
                            @endif
                        </h5>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted text-truncate mb-2">User</p>
                            <h5 class="mb-0"  id="my_user">{{ auth()->user()->profile->full_name }}</h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <span class="quick-category-icon qc-primary rounded-circle">
                                <i class="las la-check"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0 text-primary">
                            Transactions 
                        </h5>&nbsp;
                        <span class="badge badge-success">Success</span>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted text-truncate mb-2">Successful Transactions</p>
                            <h5 class="mb-0"  id="total_success">
                                @{{ total_success }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="widget">
                    <div class="d-flex align-items-center mb-3">
                        <div class="mr-3">
                            <span class="quick-category-icon qc-primary rounded-circle">
                                <i class="las la-times"></i>
                            </span>
                        </div>
                        <h5 class="font-size-14 mb-0 text-primary">
                            Transactions
                        </h5>&nbsp;
                        <span class="badge badge-danger">Drop</span>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <p class="text-muted text-truncate mb-2">Dropped Transactions</p>
                            <h5 class="mb-0"  id="total_drop">
                                @{{ total_drop }}
                            </h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">   
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
                            <h5 class="mb-0" id="cur_fullname" v-if="current != null"> @{{ current.account.first_name + " " + current.account.middle_name + " " + current.account.last_name }} </h5>
                            <h5 class="mb-0" id="cur_fullname" v-else> NONE </h5>
                        </div>
                        <div class="col-3">
                            <p class="text-muted text-truncate mb-2">Token</p>
                            <h5 class="mb-0" id="cur_token" v-if="current != null"> @{{ current.token }} </h5>
                            <h5 class="mb-0" id="cur_token" v-else>NONE</h5>
                        </div>
                        <div class="col-3">
                            <p class="text-muted text-truncate mb-2">Account Number</p>
                            <h5 class="mb-0" id="cur_account_number" v-if="current != null"> @{{ current.account.account_number }}</h5>
                            <h5 class="mb-0" id="cur_token" v-else>NONE</h5>
                        </div>
                        <div class="col-3">
                            <p class="text-muted text-truncate mb-2">Customer Type</p>
                            <h5 class="mb-0" id="cur_customer_type" v-if="current != null"> @{{ current.account.customer_type }}</h5>
                            <h5 class="mb-0" id="cur_token" v-else>NONE</h5>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-3">
                            <p class="text-muted text-truncate mb-2" >Reference No.</p>
                            <h5 class="mb-0" id="cur_customer_type" v-if="current != null"> @{{ current.id }}</h5>
                            <h5 class="mb-0" id="cur_ref_number" v-else>NONE</h5>
                        </div>
                        <div class="col-3">
                            <p class="text-muted text-truncate mb-2">Service</p>
                            <h5 class="mb-0" id="cur_customer_type" v-if="current != null"> @{{ current.service.name }}</h5>
                            <h5 class="mb-0"  id="cur_service" v-else>NONE</h5>
                        </div>
                        <div class="col-3">
                            <p class="text-muted text-truncate mb-2">Amount</p>
                            <h5 class="mb-0" id="cur_customer_type" v-if="current != null"> @{{ current.amount }}</h5>
                            <h5 class="mb-0"  id="cur_amount" v-else>NONE</h5>
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
                            <p class="text-muted text-truncate mb-2" >Full name</p>
                            <h5 class="mb-0"  id="next_fullname" v-if="next != null">@{{ next.account.first_name + " " + next.account.middle_name + " " + next.account.last_name }}</h5>
                            <h5 class="mb-0"  id="next_fullname" v-else>NONE</h5>
                        </div>
                        <div class="col-6">
                            <p class="text-muted text-truncate mb-2">Token</p>
                            <h5 class="mb-0" id="next_token" v-if="next != null">@{{ next.token }}</h5>
                            <h5 class="mb-0"  id="next_fullname" v-else>NONE</h5>
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
                        <template v-if="prev != null">
                            <span class="badge badge-danger" id="prev_status" v-if="prev.state == 'drop'">Drop</span>
                            <span class="badge badge-success" id="prev_status" v-if="prev.state == 'out'">Success</span>
                        </template>
                        <span class="badge badge-info" id="prev_status" v-if="prev == null">NONE</span>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="text-muted text-truncate mb-2">Full name</p>
                            <h5 class="mb-0" id="prev_fulltime" v-if="prev != null">@{{ prev.account.first_name + " " + prev.account.middle_name + " " + prev.account.last_name }}</h5>
                            <h5 class="mb-0" id="prev_fulltime" v-else>NONE</h5>
                        </div>
                        <div class="col-6">
                            <p class="text-muted text-truncate mb-2">Token</p>
                            <h5 class="mb-0" id="prev_token" v-if="prev != null">@{{ prev.token }}</h5>
                            <h5 class="mb-0" id="prev_token" v-else>NONE</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-2">
                <button type="button" class="btn btn-primary btn-lg h-100 w-100" id="nextCustomer" v-on:click="nextq">
                    <span class="btn-label" style="background:transparent;"><i class="las la-chevron-circle-right"></i></span>Next
                </button>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-danger btn-lg h-100 w-100" id="dropCustomer" v-on:click="dropq">
                    <span class="btn-label"  style="background:transparent;"><i class="las la-trash"></i></span>Drop
                </button>
            </div>
            <div class="col-2 ">
                <button type="button" class="btn btn-warning btn-lg h-100 w-100 " id="ring" v-on:click="ringq">
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
                <button type="button" class="btn btn-success btn-lg h-100 w-100" id="startQueue" v-on:click="startq">
                    <span class="btn-label"  style="background:transparent;"><i v-bind:class="{ 'las la-play-circle': !is_ongoing, 'las la-spinner': is_ongoing }"  class=""></i></span>
                    <template v-if="!is_ongoing">
                        Start
                    </template>
                    <template v-if="is_ongoing">
                        Ongoing
                    </template>
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
                                    <!-- <th>Customer Type</th> -->
                                    <th class="no-content"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="transaction in transactions">
                                    <td>@{{ transaction.id }}</td>
                                    <td>@{{ transaction.token }}</td>
                                    <td>
                                        <span class="badge badge-success" v-if="transaction.state == 'out'">Success</span>
                                        <span class="badge badge-danger" v-if="transaction.state == 'drop'">Drop</span>
                                        <span class="badge badge-warning" v-if="transaction.state == 'serving'">Serving</span>
                                        <span class="badge badge-secondary" v-if="transaction.state == 'waiting'">Waiting</span>
                                    </td>
                                    <td>@{{ transaction.account.account_number }}</td>
                                    <td>
                                        @{{ transaction.account.first_name + " " + transaction.account.middle_name + " " + transaction.account.last_name }}
                                    </td>
                                    <td>
                                        <a href='#' title='Notify' v-on:click="notify(transaction.id)" class='font-20 text-primary'>
                                            <i class='las la-envelope'></i>
                                        </a>
                                    </td>
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
                    <h5 class="modal-title">Switching Customers</h5>
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
                    <div class="form-group">
                        <label>Customers
                        <span class="text-danger">*</span></label>
                        <select class="form-control multiple" multiple="multiple" name="transactions[]" id="transactions" required>
                            <option v-for="option in switch_options" v-bind:value="option.id">
                                @{{ option.token }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i>Close</button>
                    <button class="btn btn-primary" id="switch" v-on:click="switchq">Switch</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("custom-scripts")
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="/admin/plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="/admin/assets/js/basicui/sweet_alerts.js"></script>
<script src="/admin/plugins/notification/snackbar/snackbar.min.js"></script>
<script src="/admin/assets/js/basicui/notifications.js"></script>
<script src="/admin/assets/js/control/websocket.js"></script>
<script src="/admin/assets/js/control/control_v4.js"></script>
@endpush