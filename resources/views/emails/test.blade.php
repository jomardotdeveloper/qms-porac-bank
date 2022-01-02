<!DOCTYPE html>
<html>

<head>
    <title>{{ $mailData }}</title>
    <style>
        .btn {
            padding: 0.4375rem 1.25rem;
            text-shadow: none;
            font-size: 14px;
            color: #3b3f5c;
            font-weight: normal;
            white-space: normal;
            word-wrap: break-word;
            transition: .2s ease-out;
            touch-action: manipulation;
            cursor: pointer;
            background-color: #f1f2f3;
        }

        .btn-primary {
            color: #fff !important;
            background-color: #0767A3 !important;
            border-color: #0767A3;
        }

        a {
            color: #515365;
            outline: none;
            text-decoration: none;
        }

        .lead {
            font-size: 1.25rem;
            font-weight: 300
        }
    </style>
</head>

<body>
    <h1 style="color:#0767A3;">{{ $data->branch_name }} BRANCH</h1>
    <h3 style="color: #888ea8;">{{ $data->date }}</h3>
    <h3 class="lead">Here are your daily reports.</h3>
    <h3 style="color: #0767A3;">Notification Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->notification }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Performance Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->performance }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Cash Deposit Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->deposit }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Cash Withdrawal Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->withdrawal }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Cash Encashment Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->encashment }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Cash Loan Transaction Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->loan }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Cash Bills Payment Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->bills }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">New Account Transaction Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->new }}">CLICK TO DOWNLOAD</a>
    <h3 style="color: #0767A3;">Overall Transactions Daily Reports</h3>
    <a class="btn btn-primary" href="{{ $data->base_url . $data->reports->all }}">CLICK TO DOWNLOAD</a>
</body>

</html>