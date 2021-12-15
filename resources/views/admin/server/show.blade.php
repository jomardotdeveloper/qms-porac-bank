@extends("layouts.admin-master")
@section("title", "Branches")
@section("custom-styles")
<link href="/admin/assets/css/dashboard/dashboard_1.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/elements/tooltip.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
@endsection
@section("breadcrumbs")
<nav class="breadcrumb-one" aria-label="breadcrumb">
    <ol class="breadcrumb">
        @if(auth()->user()->is_admin)
        <li class="breadcrumb-item active"><a href="{{ route('servers.index') }}" class="text-primary">Servers</a></li>
        @endif
        <li class="breadcrumb-item" aria-current="page"><span>{{ $server->name }}</span></li>
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
                                        <h4>{{ $server->name }}</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <input type="hidden" name="branch_id" id="branch_id" value="{{ $server->branch->id }}" />
                                <div class="widget-content mt-40">
                                    <div id="companyGrowth" class=""></div>
                                    <p class="font-17 text-center mb-0 text-muted">
                                        <a class="text-primary" href="javascript:void(0);">Server Status</a> 
                                    </p>
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
<script src="/admin/plugins/apex/apexcharts.min.js"></script>
<!-- <script src="/admin/assets/js/dashboard/dashboard_1.js"></script> -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
var dangerColor = "#e7515a";
var successColor = "#2262c6";

var options5 = {
    series: [0],
        chart: {
        height: 316,
        type: 'radialBar',
        offsetY: -10,
    },
    plotOptions: {
        radialBar: {
        startAngle: -135,
        endAngle: 135,
        dataLabels: {
            name: {
            fontSize: '15px',
            fontFamily: 'Poppins, sans-serif',
            color: dangerColor,
            fontWeight: 'bold',
            offsetY: 90
            },
            value: {
            offsetY: 50,
            fontSize: '25px',
            fontWeight: 'bold',
            fontFamily: 'Poppins, sans-serif',
            color: "#333333",
            formatter: function (val) {
                return val + "%";
            }
            },
        }
        }
    },
    fill: {
        type: 'gradient',
        colors: "#3bda39",
        gradient: {
            shade: 'dark',
            shadeIntensity: 0.15,
            inverseColors: false,
            opacityFrom: 1,
            opacityTo: 1,
            stops: [0, 50, 65, 91]
        },
    },
    stroke: {
        dashArray: 4
    },
    labels: ['No Internet Connection'],
}
var chart = new ApexCharts(
    document.querySelector("#companyGrowth"),
    options5
);
chart.render();


var isOnline = false;

var networkSpeedCache = 0;
const maximum = 10;
// navigator.connection.downlinkMax
setInterval(
    function () {
        var status = window.navigator.onLine;
        if(isOnline != status){
            isOnline = status; 

            // Update Chart
            if(status){
                var speed = navigator.connection.downlink;

                if(networkSpeedCache != speed){
                    networkSpeedCache = speed;
                    if(speed == 0){
                        computed_speed = 0;
                    }else if(speed > maximum){
                        computed_speed = 100;
                    }else{
                        computed_speed =  Math.floor((speed / maximum) * 100);
                    }

                    // update chart
                    chart.updateSeries([computed_speed]);
                }

                chart.updateOptions({
                    labels : ["Internet Connected"],
                    plotOptions : {
                        radialBar : {
                            dataLabels : {
                                name : {color : successColor}
                            }
                        }
                    }
                });
            }else{
                chart.updateOptions({
                    labels : ["No Internet Connection "],
                    plotOptions : {
                        radialBar : {
                            dataLabels : {
                                name : {color : dangerColor}
                            }
                        }
                    }
                });
            }
            
        }

}, 3000); 
</script>


<script>
    var branch = $("#branch_id").val();
    // var socket  = new WebSocket('ws://74.63.204.84:8090');
    // socket.onmessage = function(e){
    //     var jsonObject = jQuery.parseJSON(e.data);
    //     jsonObject = jQuery.parseJSON(jsonObject["message"]);
    //     if(jsonObject["message"] == "serverCheckStatus" && jsonObject["branch_id"] == branch.toString()){
    //         socket.send(JSON.stringify({message : "serverUpdateStatus", branch_id : branch.toString()}));
    //     }

    //     if(jsonObject["message"] == "isBranch"){
    //         var datas = {
    //             "message" : "iambranch",
    //             "branch_id" : branch
    //         };

    //         socket.send(JSON.stringify(datas));
    //     }

    //     console.log(jsonObject);
    // }


    // var localSocket  = new WebSocket('ws://127.0.0.1:8090');

    // localSocket.onmessage = function(e){
    //     if(jsonObject["message"] == "newCustomer"){

    //     }
    // }

    async function getTransactionsUnsink(){
        var res  = (await axios.get("/api/sinker_local/get_all/" + branch)).data;
        return res;  
    }


    async function sink(){
        var res  = (await axios.post("http://poracbankqms.com/api/sinker_cloud/sink_transactions", {
            transactions : await getTransactionsUnsink()
        } )).data;

        console.log(res);
    }


    sink();


</script>
@endpush


