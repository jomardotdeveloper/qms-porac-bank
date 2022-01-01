@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">CASH ENCASHMENT REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif

<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        <th>Account</th>
    </tr>
    @foreach($data["data"] as $encashment)
    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($encashment->in), "F d, Y")}}</td>
        @endif
        <td>{{ $encashment->token }}</td>
        <td>{{ $encashment->account->account_number }}</td>
    </tr>
    @endforeach
</table>
<h2>SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@endsection