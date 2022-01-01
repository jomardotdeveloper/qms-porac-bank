@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">LOAN TRANSACTION REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif

@if(isset($data["loan_type"]))
<h4 style="margin-top:-1rem; ">LOAN : <span style="font-weight:normal;">{{  $data["loan_type"]->name  }}</span></h4>
@endif


<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        <th>Account</th>
        @if(!isset($data["loan_type"]))
        <th>Loan</th>
        @endif
    </tr>
    @foreach($data["data"] as $loan)
    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($loan->in), "F d, Y")}}</td>
        @endif
        <td>{{ $loan->token }}</td>
        <td>{{ $loan->account->account_number }}</td>
        @if(!isset($data["loan_type"]))
        <td>{{ $loan->loan->name }}</td>
        @endif
    </tr>
    @endforeach
</table>
<h2>SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
@if(isset($data["loan_type"]))
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@else
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper("Agricultural Loan") }}: <span style="font-weight:normal;">{{ $data["loans_data"][1] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("Commercial Loan") }}: <span style="font-weight:normal;">{{ $data["loans_data"][2]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("Housing Loan") }}: <span style="font-weight:normal;">{{ $data["loans_data"][3]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("Easy Cash") }}: <span style="font-weight:normal;">{{ $data["loans_data"][4]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("SSS Pensioners") }}: <span style="font-weight:normal;">{{ $data["loans_data"][5]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@endif

@endsection