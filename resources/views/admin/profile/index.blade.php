@extends("layouts.admin-master")
@section("title", "Users")
@section("custom-styles")
<link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/datatables.css">
<link rel="stylesheet" type="text/css" href="/admin/plugins/table/datatable/dt-global_style.css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        <!-- <li class="breadcrumb-item"><a href="javascript:void(0);">Branches</a></li> -->
        <li class="breadcrumb-item" aria-current="page"><span>Users</span></li>
    </ol>
</nav>
@endsection
@section("content")
<div class="layout-top-spacing mb-2">
    <div class="col-md-12">
        <div class="row">
            <div class="container p-0">
            <a href="{{route('profiles.create')}}" class="btn btn-primary">Create User</a>
                <div class="row layout-top-spacing date-table-container">
                    <!-- BASIC -->
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                            <h4 class="table-header">All Users</h4>
                            <div class="table-responsive mb-4">
                                <table id="basic-dt" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Username</th>
                                            <th>Full Name</th>
                                            <th>Branch</th>
                                            <th>Role</th>
                                            <th class="no-content"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($profiles as $profile)
                                            @if(!$profile->user->is_admin)
                                            <tr>
                                                <td><a href="{{route('profiles.show', ['profile' => $profile])}}" class="text-primary">{{ $profile->user->username }} </a></td>
                                                <td>{{ $profile->full_name }}</td>
                                                <td>{{ $profile->branch->name }}</td>
                                                <td>{{ $profile->role->name }}</td>
                                                <td><a href="{{route('profiles.edit', ['profile' => $profile])}}" title="Edit" class="font-20 text-primary"><i class="las la-edit"></i></a></td>
                                            </tr>
                                            @endif
                                       @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Username</th>
                                            <th>Full Name</th>
                                            <th>Branch</th>
                                            <th>Role</th>
                                            <th></th>
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