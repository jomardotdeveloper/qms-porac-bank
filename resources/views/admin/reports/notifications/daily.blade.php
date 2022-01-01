@extends("reports.master")
@section("title")
ARAYAT BRANCH
@endsection
@section("custom-styles")
@endsection
@section("content")
<h3 style="margin-top:-.1rem;">DAILY NOTIFICATION REPORTS</h3>
<h4 style="margin-top:-1rem; ">DATE: <span style="font-weight:normal;">JANUARY 13, 2021</span></h4>
<table class="tb" style="width:100%; margin-left:auto; margin-right:auto;">
    <tr>
        <th>Account</th>
        <th>Message</th>
        <th>Token</th>
        <th>Service</th>
    </tr>
    @foreach($data as $notification)
    <tr>
        @if($notification->account_id != null)
        <td>{{ $notification->account->account_number }}</td>
        @else
        <td>NONE</td>
        @endif
        <td>{{ $notification->message }}</td>
        <td>{{ $notification->transaction->token }}</td>
        <td>{{ $notification->transaction->service->name }}</td>
    </tr>
    @endforeach
</table>

@endsection