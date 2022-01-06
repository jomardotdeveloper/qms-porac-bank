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
    var emailHasSent = false;

    function main() {
        var status = window.navigator.onLine;
        if (status) {
            setChartOnline();
        } else {
            setChartOffline();
        }

        // var interval = setInterval(function() {
        //     resetEmail();
        //     if (!emailHasSent) {
        //         sendEmail();
        //     }
        // }, 3000);
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

    function websocketRecon() {
        socket = new WebSocket('ws://74.63.204.84:8090');
        var myInterval = setInterval(function() {
            if (socket.readyState == WebSocket.OPEN) {
                var datas = {
                    "message": "iambranch",
                    "branch_id": branch
                };
                socket.send(JSON.stringify(datas));
                clearInterval(myInterval);
            }
        }, 1000);
    }

    function websocketDiscon() {
        socket.close();
        socket = null;
    }




    window.addEventListener('offline', function(e) {
        setChartOffline();
        websocketDiscon();
    });
    window.addEventListener('online', function(e) {
        setChartOnline();
        websocketRecon();
        // fetchCloud();
        sink();

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
        console.log(e.data);
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

        if (jsonObject["message"] == "sinkNotLog" && jsonObject["branch_id"] == branch) {

        }

        if (jsonObject["message"] == "newCustomer" && jsonObject["branch_id"] == branch) {
            fetchCloud(function() {
                sinkNotif();
            });
        }

        if (jsonObject["message"] == "sink" && jsonObject["branch_id"] == branch) {
            sinkNotifier();
        }



    }




    localSocket.onmessage = function(e) {
        var jsonObject = jQuery.parseJSON(e.data);
        jsonObject = jQuery.parseJSON(jsonObject["message"]);
        console.log(jsonObject);
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

        if (jsonObject["message"] == "newUser") {

            if (window.navigator.onLine) {
                // sinkUser();
                Snackbar.show({
                    text: 'User sync',
                    pos: 'bottom-right'
                });
            } else {
                Snackbar.show({
                    text: 'Failed to sync. Internet connection failure.',
                    pos: 'bottom-right'
                });
            }
        }

        if (jsonObject["message"] == "newAccount" && jsonObject["branch_id"] == branch) {
            if (window.navigator.onLine) {
                sinkAccount();
            } else {
                Snackbar.show({
                    text: 'Failed to sync. Internet connection failure.',
                    pos: 'bottom-right'
                });
            }
        }

        if (jsonObject["message"] == "nextCustomer" && jsonObject["branch_id"] == branch) {
            console.log("PUMASOK NAMAN DITO");
            if (window.navigator.onLine) {
                sink(function() {
                    socket.send(JSON.stringify({
                        message: "nextCustomer",
                        branch_id: branch
                    }));
                });

            } else {
                Snackbar.show({
                    text: 'Failed to sync. Internet connection failure.',
                    pos: 'bottom-right'
                });
            }
        }

        if (jsonObject["message"] == "sinkNotif" && jsonObject["branch_id"] == branch) {
            fetchNotif();
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

    async function sink(callbackmethod = false) {
        var res = (await axios.post("http://poracbankqms.com/api/sinker_cloud/sink_transactions", {
            transactions: await getTransactionsUnsink()
        })).data;

        if (callbackmethod != false) {
            callbackmethod();
        }
    }

    async function fetchCloud(callback = false) {
        var res = (await axios.post("/api/sinker_local/sink_transactions", {
            transactions: await getTransactionsCloud()
        })).data;
        console.log(res);
        localSocket.send(JSON.stringify({
            message: "newCustomer",
            branch_id: branch
        }));

        if (callback != false) {
            callback();
        }
    }

    async function getTransactionsCloud() {
        var res = (await axios.get("http://poracbankqms.com/api/sinker_cloud/get_all/" + branch)).data;
        return res;
    }

    async function getAllUsers() {
        var res = (await axios.get("/api/sinker_local/get_all_users/" + branch)).data;
        return res;
    }

    async function sinkUser() {
        var res = (await axios.post("http://poracbankqms.com/api/sinker_cloud/sink_users", {
            users: await getAllUsers()
        })).data;
    }

    async function getAllAccounts() {
        var res = (await axios.get("/api/sinker_local/get_all_accounts/" + branch)).data;
        return res;
    }

    async function sinkAccount() {
        var res = (await axios.post("http://poracbankqms.com/api/sinker_cloud/sink_accounts", {
            accounts: await getAllAccounts()
        })).data;
    }


    async function getCloudNotifs() {
        var res = (await axios.get("http://poracbankqms.com/api/sinker_local/get_all_notifs/" + branch)).data;
        console.log(res);
        return res;
    }

    async function getLocalNotifs() {
        var res = (await axios.get("/api/sinker_local/get_all_notifs/" + branch)).data;
        return res;
    }


    // SYNC CLOUD WITH LOCAL
    async function sinkNotif() {
        var res = (await axios.post("/api/sinker_cloud/sink_notifs", {
            notifications: await getCloudNotifs()
        })).data;
    }


    // SYNC LOCAL WITH CLOUD
    async function fetchNotif() {
        var res = (await axios.post("http://poracbankqms.com/api/sinker_cloud/sink_notifs", {
            notifications: await getLocalNotifs()
        })).data;
    }

    async function getCutoff() {
        var res = (await axios.get("/api/cutoffs/get_cutoff_data/" + branch)).data;
        return res;
    }

    async function emailer() {
        var res = (await axios.get("/api/mailer/send/" + branch)).data;
        return res;
    }

    // console.log();
    // getCloudNotifs()
    async function sendEmail() {
        var dateTimeNow = new Date();
        var now = dateTimeNow.getDay();
        var cutoffNow = await getCutoffOnDay(now);

        if (dateTimeNow.getHours() == 0 && dateTimeNow.getMinutes() == 0) {
            emailHasSent = false;
        }


        if (cutoffNow == null) {
            var hour = dateTimeNow.getHours();
            var minute = dateTimeNow.getMinutes();

            if (hour >= 23) {
                if (minute >= 0) {
                    emailer();
                    Snackbar.show({
                        text: 'Sending emails',
                        pos: 'bottom-right'
                    });
                    emailHasSent = true;
                }
            }
        } else {
            var splittedCutoff = cutoffNow.toString().split(":");
            var hourCutoff = parseInt(splittedCutoff[0]);
            var minuteCutoff = parseInt(splittedCutoff[1]);
            var hour = dateTimeNow.getHours();
            var minute = dateTimeNow.getMinutes();

            if (hour >= hourCutoff) {
                if (minute >= minuteCutoff) {
                    emailer();
                    Snackbar.show({
                        text: 'Sending emails',
                        pos: 'bottom-right'
                    });
                    emailHasSent = true;
                }
            }
        }
    }

    async function getCutoffOnDay(idx) {
        var cutOffs = (await getCutoff()).data;

        if (idx == 0) {
            return cutOffs.sd;
        } else if (idx == 1) {
            return cutOffs.m;
        } else if (idx == 2) {
            return cutOffs.t;
        } else if (idx == 3) {
            return cutOffs.w;
        } else if (idx == 4) {
            return cutOffs.th;
        } else if (idx == 5) {
            return cutOffs.f;
        } else if (idx == 6) {
            return cutOffs.s;
        }

        return null;

    }

    async function resetEmail() {
        var dateTimeNow = new Date();
        if (dateTimeNow.getHours() == 0 && dateTimeNow.getMinutes() == 0) {
            emailHasSent = false;
        }
    }
</script>
@endpush