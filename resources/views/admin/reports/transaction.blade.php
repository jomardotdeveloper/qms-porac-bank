@extends("reports.master")
@section("title")
<h1 class="text-center">{{ $branch->name }} BRANCH</h1>
@endsection
@section("custom-styles")
<style>
    .styled-table {
        border-collapse: collapse;
        font-size: 0.9em;
        font-family: sans-serif;
        min-width: 400px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }
    .styled-table thead tr {
        background-color: #0767A3;
        color: #ffffff;
        text-align: left;
    }
    .styled-table th,
    .styled-table td {
        padding: 12px 15px;
    }

    .styled-table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .styled-table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .styled-table tbody tr:last-of-type {
        border-bottom: 2px solid #0767A3;
    }

    .styled-table tbody tr.active-row {
        font-weight: bold;
        color: #0767A3;
    }
</style>
@endsection
@section("content")
<div style=" margin: 25px 0;">
    <span style="font-weight:bold;">Period</span> : {{ $summary["start_date"] }} - {{ $summary["end_date"] }} <br />    
    <span style="font-weight:bold;">Number of Transactions</span> : {{ $summary["total_number"] }} <br />
</div>

<table class="styled-table">
    <thead>
        <tr>
            <th>Token</th>
            <th>Account</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Mobile Number</th>
            <th>Window</th>
            <th>Service</th>
            <th>Service Provider</th>
        </tr>
    </thead>
    <tbody>
        @foreach($transactions as $transaction)
            <tr>
                <td>
                    {{ $transaction->token }}
                </td>
                <td>
                    {{ $transaction->account->account_number }}
                </td>
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
                    {{ $transaction->profile->full_name }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
<h4>Summary</h4>

@foreach($summary["services"] as $service)
<h6>{{ $service["service"] }}  :  {{ $service["count"] }} transactions</h6>
@endforeach

@endsection