@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">TRANSACTION REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif

@if(isset($data["service"]))
<h4 style="margin-top:-1rem; ">SERVICE : <span style="font-weight:normal;">{{  $data["service"]->name  }}</span></h4>
@endif

@if(isset($data["status"]))
<h4 style="margin-top:-1rem; ">STATUS : <span style="font-weight:normal;">{{  $data["status"]  }}</span></h4>
@endif

@if(isset($data["platform"]))
<h4 style="margin-top:-1rem; ">PLATFORM : <span style="font-weight:normal;">{{  $data["platform"]  }}</span></h4>
@endif

@php($counts = [
    "platform" => [0,0],
    "status" => [0,0,0,0],
    "service" => [0,0,0,0,0,0]
])

<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        @if(!isset($data["service"]))
        <th>Service</th>
        @endif
        @if(!isset($data["status"]))
        <th>Status</th>
        @endif
        @if(!isset($data["platform"]))
        <th>Platform</th>
        @endif
    </tr>
    @foreach($data["data"] as $transaction)
    @if($transaction->is_mobile)
    @php($counts["platform"][0]++)
    @else
    @php($counts["platform"][1]++)
    @endif

    @if($transaction->state == 'waiting')
    @php($counts["status"][0]++)
    @elseif($transaction->state == 'serving')
    @php($counts["status"][1]++)
    @elseif($transaction->state == 'out')
    @php($counts["status"][2]++)
    @elseif($transaction->state == 'drop')
    @php($counts["status"][3]++)
    @endif

    @if($transaction->service->id == 1)
    @php($counts["service"][0]++)
    @elseif($transaction->service->id == 2)
    @php($counts["service"][1]++)
    @elseif($transaction->service->id == 3)
    @php($counts["service"][2]++)
    @elseif($transaction->service->id == 4)
    @php($counts["service"][3]++)
    @elseif($transaction->service->id == 5)
    @php($counts["service"][4]++)
    @elseif($transaction->service->id == 6)
    @php($counts["service"][5]++)
    @endif

    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($transaction->in), "F d, Y")}}</td>
        @endif
        <td>{{$transaction->token}}</td>
        @if(!isset($data["service"]))
        <td>{{ $transaction->service->name }}</td>
        @endif
        @if(!isset($data["status"]))
        @if($transaction->state == 'waiting')
        <td>Waiting</td>
        @elseif($transaction->state == 'out')
        <td>Served</td>
        @elseif($transaction->state == 'drop')
        <td>Drop</td>
        @else
        <td>Serving</td>
        @endif
        @endif
        @if(!isset($data["platform"]))
        @if($transaction->is_mobile)
        <td>Mobile Application</td>
        @else
        <td>Kiosk Machine</td>
        @endif
        @endif
    </tr>
    @endforeach
</table>
<h2>PLATFORM SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
@if(isset($data["platform"]))
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper($data["platform"]) }} USER: <span style="font-weight:normal;">{{ $data["platform"] == "Mobile Application" ? $counts["platform"][0] :   $counts["platform"][1]}}</span></h4>
@else
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF MOBILE APPLICATION USER: <span style="font-weight:normal;">{{  $counts["platform"][0] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF KIOSK MACHINE USER: <span style="font-weight:normal;">{{  $counts["platform"][1] }}</span></h4>
@endif

<h2>SERVICE SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
@if(isset($data["service"]))
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper($data["service"]->name) }} TRANSACTIONS: <span style="font-weight:normal;">{{  $counts["service"][ intval($data["service"]->id) - 1 ] }}</span></h4>
@else
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF CASH DEPOSIT TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["service"][ 0 ]  }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF CASH WITHDRAWAL TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["service"][ 1 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF CASH ENCASHMENT TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["service"][ 2 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF BILLS PAYMENT TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["service"][ 3 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF LOAN TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["service"][4 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF NEW ACCOUNT TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["service"][ 5 ] }}</span></h4>
@endif


<h2>STATUS SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
@if(isset($data["status"]))
@if($data["status"] == "Waiting")
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper($data["status"]) }} TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][ 0 ] }}</span></h4>
@elseif($data["status"] == "Served")
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper($data["status"]) }} TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][ 1 ] }}</span></h4>
@elseif($data["status"] == "Dropped")
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper($data["status"]) }} TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][2 ] }}</span></h4>
@elseif($data["status"] == "Serving")
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF {{ strtoupper($data["status"]) }} TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][ 3] }}</span></h4>
@endif

@else
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF WAITING TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][ 0 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF SERVING TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][ 1 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF SERVED TRANSACTIONS: <span style="font-weight:normal;">{{$counts["status"][ 2 ] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF DROPPED TRANSACTIONS: <span style="font-weight:normal;">{{ $counts["status"][ 3 ] }}</span></h4>
@endif

<h2>TOTAL</h2>
<hr style="margin-top: -1rem;"/>
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF TRANSACTIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>

@endsection