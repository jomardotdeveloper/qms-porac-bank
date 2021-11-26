@extends("layouts.admin-master")
@section("title", "Transactions")
@section("custom-styles")
<link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/dt-global_style.css">
<link href="/admin/plugins/animate/animate.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <!-- <li class="breadcrumb-item"><a href="javascript:void(0);">Branches</a></li> -->
        <li class="breadcrumb-item" aria-current="page"><span>Transactions</span></li>
    </ol>
</nav>
@endsection
@section("content")
<div class="layout-top-spacing mb-2">
    <div class="col-md-12">
        <div class="row">
            <div class="container p-0">
                <button type="button" class="btn btn-primary mb-2 mr-2" data-toggle="modal" data-target="#fadeinModal">Export</button>
                @if(auth()->user()->is_admin)
                <button type="button" class="btn btn-primary mb-2 mr-2" data-toggle="modal" data-target="#demoData">Generate Demo Data</button>
                @endif
                <div class="row layout-top-spacing date-table-container">
                    <!-- BASIC -->
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
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
                            <h4 class="table-header">All Transaction</h4>
                            <div class="table-responsive mb-4">
                                <table id="basic-dt" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Token</th>
                                            <th>Account</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Mobile Number</th>
                                            <th>Window</th>
                                            <th>Service</th>
                                            <th>Branch</th>
                                            <th>Service Provider</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                        <tr>
                                            <td>{{ $transaction->token }}</td>
                                            <td>{{ $transaction->account->account_number }}</td>
                                            <td>
                                                @if(in_array($transaction->state, ['waiting', 'serving']))
                                                <span class="badge badge-secondary">Unsettled</span>
                                                @elseif($transaction->state == 'out')
                                                <span class="badge badge-success">Success</span>
                                                @elseif($transaction->state == 'drop')
                                                <span class="badge badge-danger">Drop</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{ $transaction->amount }}
                                            </td>
                                            <td>
                                                {{ $transaction->mobile_number }}
                                            </td>
                                            <td>
                                                {{ $transaction->window->name }}
                                            </td>
                                            <td>
                                                {{ $transaction->service->name }}
                                            </td>
                                            <td>
                                                {{ $transaction->branch->name }}
                                            </td>
                                            <td>
                                                {{ $transaction->profile->full_name }}
                                            </td>
                                            <td>
                                                {{ $transaction->date }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Token</th>
                                            <th>Account</th>
                                            <th>Status</th>
                                            <th>Amount</th>
                                            <th>Mobile Number</th>
                                            <th>Window</th>
                                            <th>Service</th>
                                            <th>Branch</th>
                                            <th>Service Provider</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="fadeinModal" class="modal animated fadeInDown" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Export Reports</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{route('transaction.export')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Branch 
                                <span class="text-danger">*</span></label>
                                <select class="form-control basic" name="branch_id" required>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>From 
                                <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="from" id="from" required/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>To 
                                <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="to" id="to" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <input type="submit" value="DOWNLOAD PDF" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
</div>
@if(auth()->user()->is_admin)
<!-- CREATE DEMO DATA -->
<div id="demoData" class="modal animated fadeInDown" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Generate Demo Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="las la-times"></i>
                </button>
            </div>
            <form action="{{route('transaction.generate_demo_data')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label>Branch 
                                <span class="text-danger">*</span></label>
                                <select class="form-control basic" name="branch_id" required>
                                    @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Number of transactions per day 
                                <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" name="number_day" id="number_day" required/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>From 
                                <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="from" id="from" required/>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>To 
                                <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" name="to" id="to" required/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Discard</button>
                    <input type="submit" value="Generate" class="btn btn-primary"/>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@push("custom-scripts")
<script src="/admin/plugins/table/datatable/datatables.js"></script>
<script>
    $(document).ready(function() {
        $('#basic-dt').DataTable({
            "language": {
                "paginate": {
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>"
                }
            },
            "lengthMenu": [5,10,15,20],
            "pageLength": 5 
        });
        $('#dropdown-dt').DataTable({
            "language": {
                "paginate": {
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>"
                }
            },
            "lengthMenu": [5,10,15,20],
            "pageLength": 5 
        });
        $('#last-page-dt').DataTable({
            "pagingType": "full_numbers",
            "language": {
                "paginate": {
                    "first": "<i class='las la-angle-double-left'></i>",
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>",
                    "last": "<i class='las la-angle-double-right'></i>"
                }
            },
            "lengthMenu": [3,6,9,12],
            "pageLength": 3 
        });
        $.fn.dataTable.ext.search.push(
            function( settings, data, dataIndex ) {
                var min = parseInt( $('#min').val(), 10 );
                var max = parseInt( $('#max').val(), 10 );
                var age = parseFloat( data[3] ) || 0; // use data for the age column
                if ( ( isNaN( min ) && isNaN( max ) ) ||
                    ( isNaN( min ) && age <= max ) ||
                    ( min <= age   && isNaN( max ) ) ||
                    ( min <= age   && age <= max ) )
                {
                    return true;
                }
                return false;
            }
        ); 
        var table = $('#range-dt').DataTable({
            "language": {
                "paginate": {
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>"
                }
            },
            "lengthMenu": [5,10,15,20],
            "pageLength": 5 
        });
        $('#min, #max').keyup( function() { table.draw(); } );
        $('#export-dt').DataTable( {
            dom: '<"row"<"col-md-6"B><"col-md-6"f> ><""rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>>',
            buttons: {
                buttons: [
                    { extend: 'copy', className: 'btn btn-primary' },
                    { extend: 'csv', className: 'btn btn-primary' },
                    { extend: 'excel', className: 'btn btn-primary' },
                    { extend: 'pdf', className: 'btn btn-primary' },
                    { extend: 'print', className: 'btn btn-primary' }
                ]
            },
            "language": {
                "paginate": {
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>"
                }
            },
            "lengthMenu": [7, 10, 20, 50],
            "pageLength": 7 
        } );
        // Add text input to the footer
        $('#single-column-search tfoot th').each( function () {
            var title = $(this).text();
            $(this).html( '<input type="text" class="form-control" placeholder="Search '+title+'" />' );
        } );
        // Generate Datatable
        var table = $('#single-column-search').DataTable({
            "language": {
                "paginate": {
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>"
                }
            },
            "lengthMenu": [5,10,15,20],
            "pageLength": 5
        });
        // Search
        table.columns().every( function () {
            var that = this;
            $( 'input', this.footer() ).on( 'keyup change', function () {
                if ( that.search() !== this.value ) {
                    that
                        .search( this.value )
                        .draw();
                }
            } );
        } );
        var table = $('#toggle-column').DataTable( {
            "language": {
                "paginate": {
                    "previous": "<i class='las la-angle-left'></i>",
                    "next": "<i class='las la-angle-right'></i>"
                }
            },
            "lengthMenu": [5,10,15,20],
            "pageLength": 5
        } );
        $('a.toggle-btn').on( 'click', function (e) {
            e.preventDefault();
            // Get the column API object
            var column = table.column( $(this).attr('data-column') );
            // Toggle the visibility
            column.visible( ! column.visible() );
            $(this).toggleClass("toggle-clicked");
        } );
    } );
</script>
@endpush