<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;

class MailManController extends Controller
{
    //

    function sendDailyReports($branch_id)
    {
        $branch = Branch::find($branch_id);
        $data = [
            "reports" => [
                "notification" => "notifications/daily/$branch_id/" . date("Y-m-d"),
                "performance" =>  "performances/daily/$branch_id/" . date("Y-m-d"),
                "all" =>  "alls/daily/$branch_id/" . date("Y-m-d"),
                "deposit" =>  "deposits/daily/$branch_id/" . date("Y-m-d"),
                "withdrawal" =>  "withs/daily/$branch_id/" . date("Y-m-d"),
                "encashment" =>  "encashments/daily/$branch_id/" . date("Y-m-d"),
                "bills" =>  "bills/daily/$branch_id/" . date("Y-m-d"),
                "loan" =>  "loans/daily/$branch_id/" . date("Y-m-d"),
                "new" =>  "news/daily/$branch_id/" . date("Y-m-d")
            ],
            "branch_name" => strtoupper($branch->name),
            "date" => date_format(date_create(date("Y-m-d")), "F d, Y"),
            "base_url" => "http://127.0.0.1:8000/api/"
        ];

        $strData = json_encode($data);
        \Mail::to('remdoro.28@gmail.com')->send(new \App\Mail\MyTestMail($strData));
    }
}
