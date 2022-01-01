@extends("reports.master")
@section("title")
{{ $data["branch"] }} BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">NOTIFICATION REPORTS</h3>

@if($data["from"] == $data["to"])
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">{{ $data["from"] }}</span></h4>
@endif

@if($data["from"] != $data["to"])
<h4 style="margin-top:-1rem; ">DATE RANGE: <span style="font-weight:normal;">{{ $data["from"] }} - {{ $data["to"] }}</span></h4>
@endif

@if(isset($data["type"]))
<h4 style="margin-top:-1rem; ">Notification Type: <span style="font-weight:normal;">{{ $data["type"] }}</span></h4>
@endif

<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        @if($data["from"] != $data["to"])
        <th>Date</th>
        @endif
        <th>Token</th>
        @if(isset($data["sms"]))
        <th>Type</th>
        @endif
        <th>Message</th>
    </tr>
    @foreach($data["data"] as $notification)
    <tr>
        @if($data["from"] != $data["to"])
        <td>{{date_format(date_create($notification->datetime), "F d, Y")}}</td>
        @endif
        <td>{{ $notification->transaction->token }}</td>
        @if(isset($data["sms"]))
        @if($notification->is_push)
        <td>Push</td>
        @else
        <td>Sms</td>
        @endif
        @endif
        <td>{{ $notification->message }}</td>
    </tr>
    @endforeach
</table>
@if(count($data["data"]) > 0)
<h2>SUMMARY</h2>
<hr style="margin-top: -1rem;"/>

@if(isset($data["sms"]))
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF SMS NOTIFICATIONS: <span style="font-weight:normal;">{{ $data["sms"] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF PUSH NOTIFICATIONS: <span style="font-weight:normal;">{{ $data["push"] }}</span></h4>
<h4 style="margin-top:-1rem; ">TOTAL NUMBER OF NOTIFICATIONS: <span style="font-weight:normal;">{{ $data["sms"] + $data["push"]}}</span></h4>
@else
@if($data["data"][0]->is_push)
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF PUSH NOTIFICATIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@else
<h4 style="margin-top:-.1rem; ">TOTAL NUMBER OF SMS NOTIFICATIONS: <span style="font-weight:normal;">{{ count($data["data"]) }}</span></h4>
@endif
@endif
@endif
@endsection