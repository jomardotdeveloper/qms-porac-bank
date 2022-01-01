@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">CASH DEPOSIT REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif

@php($total_amount = 0)
<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        <th>Account</th>
        <th>Amount</th>
    </tr>
    @foreach($data["data"] as $deposit)
    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($deposit->in), "F d, Y")}}</td>
        @endif
        <td>{{ $deposit->token }}</td>
        <td>{{ $deposit->account->account_number }}</td>
        <td><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>{{ $deposit->amount }}</td>
        @php($total_amount += intval($deposit->amount))
    </tr>
    @endforeach
</table>
<h2>SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
<h4 style="margin-top:-.1rem; ">TOTAL AMOUNT: <span style="font-weight:normal;"><span style="font-family: DejaVu Sans; sans-serif;">&#8369;</span>{{ $total_amount }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@endsection