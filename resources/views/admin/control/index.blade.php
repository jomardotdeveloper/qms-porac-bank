@extends("layouts.admin-master")
@section("title", "Branches")
@section("custom-styles")
<link href="/admin/assets/css/dashboard/dashboard_2.css" rel="stylesheet" type="text/css">
<script src="/admin/plugins/sweetalerts/promise-polyfill.js"></script>
<link href="/admin/plugins/sweetalerts/sweetalert2.min.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/sweetalerts/sweetalert.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/basic-ui/custom_sweetalert.css" rel="stylesheet" type="text/css">
@endsection

@section("content")
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
                        <h5 class="mb-0">Jomar Ramos</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Token</p>
                        <h5 class="mb-0">P001 </h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Account Number</p>
                        <h5 class="mb-0">0000-0000-000-00-0</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Customer Type</p>
                        <h5 class="mb-0">Priority</h5>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Reference No.</p>
                        <h5 class="mb-0">001</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Service</p>
                        <h5 class="mb-0">Deposit</h5>
                    </div>
                    <div class="col-3">
                        <p class="text-muted text-truncate mb-2">Amount</p>
                        <h5 class="mb-0">1,000,000</h5>
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
                        <h5 class="mb-0">Jomar Ramos</h5>
                    </div>
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Token</p>
                        <h5 class="mb-0">P002</h5>
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
                    <span class="badge badge-danger">Drop</span>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Full name</p>
                        <h5 class="mb-0">Jomar Ramos</h5>
                    </div>
                    <div class="col-6">
                        <p class="text-muted text-truncate mb-2">Token</p>
                        <h5 class="mb-0">P003</h5>
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
            <button type="button" class="btn btn-warning btn-lg h-100 w-100 ">
                <span class="btn-label "  style="background:transparent;"><i class="las la-sms"></i></span>Notify
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-secondary btn-lg h-100 w-100">
                <span class="btn-label"  style="background:transparent;"><i class="las la-exchange-alt"></i></span>Transfer
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-info btn-lg h-100 w-100">
                <span class="btn-label"  style="background:transparent;"><i class="las la-clipboard-list"></i></span>List
            </button>
        </div>
        <div class="col-2">
            <button type="button" class="btn btn-success btn-lg h-100 w-100">
                <span class="btn-label"  style="background:transparent;"><i class="las la-play-circle"></i></span>Start
            </button>
        </div>
    </div>
</div>
@endsection

@push("custom-scripts")
<script src="/admin/plugins/sweetalerts/sweetalert2.min.js"></script>
<script src="/admin/assets/js/basicui/sweet_alerts.js"></script>
<script>
    $('#nextCustomer').on('click', function () {
        socket.send("JOMAR");
        swal({
            title: 'Next Customer!',
            text: "P001",
            type: 'success',
            padding: '2em'
        }).then(function(){
            // alert("JOMAR");
        });
    });
    $('#dropCustomer').on('click', function () {
        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            padding: '2em'
            }).then(function(result) {
            if (result.value) {
                swal(
                    'Drop!',
                    'Customer P001 has been dropped.',
                    'success'
                )
            }
        })
    });
    var socket  = new WebSocket('ws://localhost:8090');
    
    // socket.onmessage = function(e){
    //     alert(e.data);
    // }


    
</script>
@endpush