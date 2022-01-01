@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">BILLS PAYMENT REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif

@if(isset($data["biller"]))
<h4 style="margin-top:-1rem; ">BILLER : <span style="font-weight:normal;">{{  $data["biller"]->name  }}</span></h4>
@endif


<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        <th>Account</th>
        @if(!isset($data["biller"]))
        <th>Biller</th>
        @endif
    </tr>
    @foreach($data["data"] as $bill)
    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($bill->in), "F d, Y")}}</td>
        @endif
        <td>{{ $bill->token }}</td>
        <td>{{ $bill->account->account_number }}</td>
        @if(!isset($data["biller"]))
        <td>{{ $bill->bill->name }}</td>
        @endif
    </tr>
    @endforeach
</table>
<h2>SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
@if(isset($data["biller"]))
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@else
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper("Philippine Airlines") }}: <span style="font-weight:normal;">{{ $data["billers_data"][1] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("SMART") }}: <span style="font-weight:normal;">{{ $data["billers_data"][2]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("Converge") }}: <span style="font-weight:normal;">{{ $data["billers_data"][3]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("Globe") }}: <span style="font-weight:normal;">{{ $data["billers_data"][4]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("BPI") }}: <span style="font-weight:normal;">{{ $data["billers_data"][5]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("MetroBank") }}: <span style="font-weight:normal;">{{ $data["billers_data"][6]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("AMA") }}: <span style="font-weight:normal;">{{ $data["billers_data"][7]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("NBI") }}: <span style="font-weight:normal;">{{ $data["billers_data"][8]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF {{ strtoupper("Home Credit") }}: <span style="font-weight:normal;">{{ $data["billers_data"][9]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@endif

@endsection