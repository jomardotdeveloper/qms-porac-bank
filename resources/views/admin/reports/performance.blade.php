@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
<style>
.page-break {
    page-break-after: always;
}
</style>
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">PERFORMANCE REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif
@php($is_first = true)
@foreach($data["tellers"] as $teller_data)
@if(!$is_first)
<div class="page-break"></div>
@else
@php($is_first = false)
@endif


<h4 >Teller: <span style="font-weight:normal;">{{ $teller_data["profile"]->full_name }}</span></h4>
<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        <th>Service</th>
        <th>Status</th>
        <th>Serving Time</th>
    </tr>

    @foreach($teller_data["transactions"] as $tr)
    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($tr->in), "F d, Y")}}</td>
        @endif
        <td>{{ $tr->token }}</td>
        <td>{{ $tr->service->name }}</td>
        @if($tr->state == "out")
        <td>Served</td>
        @else
        <td>Drop</td>
        @endif
        <td>{{ $tr->formattedServingTime() }}</td>
    </tr>
    @endforeach
</table>
<h2>SUMMARY</h2>
<hr style="margin-top: -1rem;"/>
<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        <th>Service</th>
        <th>Served</th>
        <th>Drop</th>
        <th>Average Serving Time</th>
    </tr>
    @foreach($teller_data["profile"]->services as $service)
        <tr>
            <td>{{ $service->name }}</td>
            <td>{{ $teller_data["service_data"][strval($service->id)]["success"] }}</td>
            <td>{{ $teller_data["service_data"][strval($service->id)]["drop"] }}</td>
            <td>{{ $teller_data["service_data"][strval($service->id)]["ave"] }}</td>
        </tr>
    @endforeach
    <tr>
        <td style="font-weight:bold; color:#0767A3;">Total & Average</td>
        <td style=" color:#0767A3;">{{ $teller_data["total_data"]["success"] }}</td>
        <td style=" color:#0767A3;">{{ $teller_data["total_data"]["drop"] }}</td>
        <td style=" color:#0767A3;">{{ $teller_data["total_data"]["ave"] }}</td>
    </tr>
</table>

@endforeach
@endsection