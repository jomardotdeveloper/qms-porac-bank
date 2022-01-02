@extends("layouts.admin-master")
@section("title", "Branches")
@section("custom-styles")
<link href="/admin/assets/css/dashboard/dashboard_1.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/flatpickr/custom-flatpickr.css" rel="stylesheet" type="text/css">
<link href="/admin/assets/css/elements/tooltip.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/apex/apexcharts.css" rel="stylesheet" type="text/css">
<link href="/admin/plugins/notification/snackbar/snackbar.min.css" rel="stylesheet" type="text/css">
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
<script src="/admin/plugins/notification/snackbar/snackbar.min.js"></script>
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
                        formatter: function(val) {
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
    var branch = $("#branch_id").val();
    var socket = new WebSocket('ws://74.63.204.84:8090');
    var localSocket = new WebSocket('ws://127.0.0.1:8090');
    var hasNoConnection = false;
    var mtimer = 1;
    // 1,2,3
    var needToSync = 1;
    var networkSpeedCache = 0;
    const maximum = 10;

    function main() {
        var status = window.navigator.onLine;
        if (status) {
            setChartOnline();
        } else {
            setChartOffline();
        }
    }

    function setChartOnline() {
        var speed = navigator.connection.downlink;
        if (speed == 0) {
            computed_speed = 0;
        } else if (speed > maximum) {
            computed_speed = 100;
        } else {
            computed_speed = Math.floor((speed / maximum) * 100);
        }
        chart.updateSeries([computed_speed]);
        chart.updateOptions({
            labels: ["Internet Connected"],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            color: successColor
                        }
                    }
                }
            }
        });
    }

    function setChartOffline() {
        chart.updateSeries([0]);
        chart.updateOptions({
            labels: ["No Internet Connection "],
            plotOptions: {
                radialBar: {
                    dataLabels: {
                        name: {
                            color: dangerColor
                        }
                    }
                }
            }
        });
    }

    window.addEventListener('offline', function(e) {
        setChartOffline();
    });
    window.addEventListener('online', function(e) {
        setChartOnline();
        sink();
        fetchCloud();
        socket = new WebSocket('ws://74.63.204.84:8090');
        Snackbar.show({
            text: 'Syncing data with the cloud server.',
            pos: 'bottom-right'
        });
    });


    main();
</script>
<script>
    socket.onmessage = function(e) {
        var jsonObject = jQuery.parseJSON(e.data);
        jsonObject = jQuery.parseJSON(jsonObject["message"]);
        if (jsonObject["message"] == "serverCheckStatus" && jsonObject["branch_id"] == branch.toString()) {
            socket.send(JSON.stringify({
                message: "serverUpdateStatus",
                branch_id: branch.toString()
            }));
        }

        if (jsonObject["message"] == "isBranch") {
            var datas = {
                "message": "iambranch",
                "branch_id": branch
            };

            socket.send(JSON.stringify(datas));
        }

        if (jsonObject["message"] == "newCustomer" && jsonObject["branch_id"] == branch) {
            fetchCloud();
        }

        if (jsonObject["message"] == "sink" && jsonObject["branch_id"] == branch) {
            sinkNotifier();
        }

        console.log(jsonObject);
    }




    localSocket.onmessage = function(e) {
        var jsonObject = jQuery.parseJSON(e.data);
        jsonObject = jQuery.parseJSON(jsonObject["message"]);

        if (jsonObject["message"] == "newCustomer" && jsonObject["branch_id"] == branch) {
            if (window.navigator.onLine) {
                sink();
            } else {
                Snackbar.show({
                    text: 'Failed to sync. Internet connection failure.',
                    pos: 'bottom-right'
                });
            }
        }

        if (jsonObject["message"] == "nextCustomer" && jsonObject["branch_id"] == branch) {
            if (window.navigator.onLine) {
                sink();
            } else {
                Snackbar.show({
                    text: 'Failed to sync. Internet connection failure.',
                    pos: 'bottom-right'
                });
            }
        }

        if (jsonObject["message"] == "pushNotif" && jsonObject["branch_id"] == branch) {
            var notifData = {
                message: "pushNotif",
                log: jsonObject["log"],
                transaction_id: jsonObject["transaction_id"],
                token: jsonObject["token"],
                datetime: jsonObject["datetime"],
                branch_id: jsonObject["branch_id"],
                service: jsonObject["service"]
            };

            if (window.navigator.onLine) {
                socket.send(JSON.stringify(notifData));
            } else {
                Snackbar.show({
                    text: 'Failed to send push notification. Internet connection failure.',
                    pos: 'bottom-right'
                });
            }

        }
    }

    async function getTransactionsUnsink() {
        var res = (await axios.get("/api/sinker_local/get_all/" + branch)).data;
        return res;
    }

    async function sinkNotifier() {
        var res = (await axios.post("/api/sinker_local/sink_transactions", {
            transactions: await getTransactionsCloud()
        })).data;
    }

    async function sink() {
        var res = (await axios.post("http://poracbankqms.com/api/sinker_cloud/sink_transactions", {
            transactions: await getTransactionsUnsink()
        })).data;

        socket.send(JSON.stringify({
            message: "nextCustomer",
            branch_id: branch
        }));
    }

    async function fetchCloud() {
        var res = (await axios.post("/api/sinker_local/sink_transactions", {
            transactions: await getTransactionsCloud()
        })).data;
        console.log(res);
        localSocket.send(JSON.stringify({
            message: "newCustomer",
            branch_id: branch
        }));
    }

    async function getTransactionsCloud() {
        var res = (await axios.get("http://poracbankqms.com/api/sinker_cloud/get_all/" + branch)).data;
        return res;
    }


    async function emailer() {

    }
</script>
@endpush