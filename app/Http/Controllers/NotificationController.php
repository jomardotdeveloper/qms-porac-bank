<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Branch;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use DateTime;
use DatePeriod;
use DateInterval;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $notifications = null;

        if ($user->is_admin) {
            $notifications = Notification::all();
        } else {
            $notifications = Notification::all()->where("branch_id", "=", $user->profile->branch->id)->all();
        }

        return view("admin.notification.index", [
            "notifications" => $notifications,
            "branches" => Branch::all()
        ]);
    }





    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $transaction = Transaction::find($request->get("id"));
        $notification = Notification::create([
            "account_id" => $transaction->account_id != null ? $transaction->account_id : null,
            "message" => $request->get("message"),
            "transaction_id" => $transaction->id,
            "branch_id" => $transaction->branch->id,
            "is_push" => isset($request->all()["is_push"]) ? true : false
        ]);


        return $notification;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function export(Request $request)
    {
        $branch_id = 0;
        $notif_filter = "";
        $data = [
            "from" => date_format(date_create($request->get("from")), "F d, Y"),
            "to" => date_format(date_create($request->get("to")), "F d, Y")
        ];
        $pdf_obj = App::make('dompdf.wrapper');
        $date_from = $request->get("from");
        $date_to =  $request->get("to");


        if ($request->get("from") > $request->get("to")) {
            return back()->withErrors([
                "date-error" => "Date from must not be greater than date to."
            ]);
        }

        if (intval($request->get("notification_type")) == 1) {
            $data["type"] = "Sms Notification";
            $notif_filter = "AND is_push = 0";
        } else if (intval($request->get("notification_type")) == 2) {
            $notif_filter = "AND is_push = 1";
            $data["type"] = "Push Notification";
        }

        if (auth()->user()->is_admin) {
            $branch_id = $request->get("branch_id");
        } else {
            $branch_id = auth()->user()->profile->branch->id;
        }

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);

        if ($request->get("pdf") != null) {
            $data["data"] = Notification::with(["account", "transaction.service"])
                ->whereRaw("DATE(notifications.datetime) >= ? AND DATE(notifications.datetime) <= ? AND branch_id = ? $notif_filter", [$request->get("from"), $request->get("to"), $branch_id])
                ->orderBy("datetime")
                ->get()
                ->all();


            if (intval($request->get("notification_type")) == 0) {
                $data["sms"] = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) >= ? AND DATE(notifications.datetime) <= ? AND branch_id = ? AND is_push = 0", [$request->get("from"), $request->get("to"), $branch_id])->get()->all());
                $data["push"] = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) >= ? AND DATE(notifications.datetime) <= ? AND branch_id = ? AND is_push = 1", [$request->get("from"), $request->get("to"), $branch_id])->get()->all());
            }
            $pdf = $pdf_obj->loadView('admin.reports.notification', ["data" => $data]);
            return $pdf->download("Notification Reports($date_from - $date_to).pdf");
        }
    }



    public function export_pdf_daily($date)
    {
        $user = auth()->user();
        $pdf_obj = App::make('dompdf.wrapper');
        $data = null;
        $sms = 0;
        $push = 0;

        if ($user->is_admin) {
            $data = Notification::with(["account", "transaction.service"])->whereRaw("DATE(notifications.datetime) = ?", [$date])->get()->all();
            $sms = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 0", [$date])->get()->all());
            $push = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 1", [$date])->get()->all());
        } else {
            $data = Notification::with(["account",  "transaction.service"])->whereRaw("DATE(notifications.datetime) = ? AND branch_id = ?", [$date, $user->profile->branch->id])->get()->all();
            $sms = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 0 AND branch_id=?", [$date, $user->profile->branch->id])->get()->all());
            $push = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 1 AND branch_id=?", [$date, $user->profile->branch->id])->get()->all());
        }

        $pdf = $pdf_obj->loadView('admin.reports.notifications.daily', ["data" => $data, "sms" => $sms, "push" => $push]);
        return $pdf->download("Daily Notification Reports($date).pdf");

        // return $pdf->stream();
    }

    public function export_pdf_monthly($month, $year)
    {

        // return $pdf->stream();
    }

    public function export_excel()
    {
    }




    public function daily()
    {
        $user = auth()->user();
        $coverage = DB::table('notifications')->select(DB::raw('MIN(DATE(notifications.datetime)) AS MinDate,MAX(DATE(notifications.datetime)) AS MaxDate'))->first();
        $min_date = $coverage->MinDate;

        $data = [];

        if (date("Y-m-d") == $min_date) {
            if ($user->is_admin) {
                $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 0")->get()->all();
                $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 1")->get()->all();

                array_push($data, [
                    "date" => $min_date,
                    "sms" => count($smsNotifications),
                    "push" => count($pushNotifications),
                    "datename" => date('F d, Y')
                ]);
            } else {
                $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 0 AND branch_id=?", [$user->profile->branch->id])->get()->all();
                $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 1 AND branch_id=?", [$user->profile->branch->id])->get()->all();

                array_push($data, [
                    "date" => $min_date,
                    "sms" => count($smsNotifications),
                    "push" => count($pushNotifications),
                    "datename" => date('F d, Y')
                ]);
            }
        } else {
            $period = new DatePeriod(
                new DateTime($min_date),
                new DateInterval('P1D'),
                new DateTime(date("Y-m-d") . " + 1 day")
            );


            if ($user->is_admin) {
                foreach ($period as $key => $value) {
                    $curr_date = $value->format('Y-m-d');
                    $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 0", [$curr_date])->get()->all();
                    $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 1", [$curr_date])->get()->all();

                    array_push($data, [
                        "date" => $curr_date,
                        "sms" => count($smsNotifications),
                        "push" => count($pushNotifications),
                        "datename" =>  $value->format('F d, Y')
                    ]);
                }
            } else {
                foreach ($period as $key => $value) {
                    $curr_date = $value->format('Y-m-d');
                    $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 0 AND branch_id=?", [$curr_date, $user->profile->branch->id])->get()->all();
                    $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 1 AND branch_id=?", [$curr_date, $user->profile->branch->id])->get()->all();

                    array_push($data, [
                        "date" => $curr_date,
                        "sms" => count($smsNotifications),
                        "push" => count($pushNotifications),
                        "datename" => $value->format('F d, Y')
                    ]);
                }
            }
        }

        return view("admin.notification.daily", [
            "data" => $data
        ]);
    }

    public function monthly()
    {
        // $user = auth()->user();
        // $coverage = DB::table('notifications')->select(DB::raw('MIN(MONTH(notifications.datetime)) AS MinMonth, MIN(YEAR(notifications.datetime)) AS MinYear,MAX(DATE(notifications.datetime)) AS MaxDate'))->first();
        // $min_date = $coverage->MinDate;

        // $data = [];

        // if(date("Y-m-d") == $min_date){

        //     if($user->is_admin){    
        //         $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 0")->get()->all();
        //         $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 1")->get()->all();

        //         array_push($data, [
        //             "date" => $min_date,
        //             "sms" => count($smsNotifications),
        //             "push" => count($pushNotifications),
        //             "datename" => date('F d, Y')
        //         ]);

        //     }else{
        //         $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 0 AND branch_id=?", [ $user->profile->branch->id])->get()->all();
        //         $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = CURDATE() AND is_push = 1 AND branch_id=?", [ $user->profile->branch->id])->get()->all();

        //         array_push($data, [
        //             "date" => $min_date,
        //             "sms" => count($smsNotifications),
        //             "push" => count($pushNotifications),
        //             "datename" => date('F d, Y')
        //         ]);
        //     }
        // }else{
        //     $period = new DatePeriod(
        //         new DateTime($min_date),
        //         new DateInterval('P1D'),
        //         new DateTime(date("Y-m-d") . " + 1 day")
        //     );


        //     if($user->is_admin){
        //         foreach ($period as $key => $value) {   
        //             $curr_date = $value->format('Y-m-d');
        //             $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 0", [$curr_date])->get()->all();
        //             $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 1", [$curr_date])->get()->all();

        //             array_push($data, [
        //                 "date" => $curr_date,
        //                 "sms" => count($smsNotifications),
        //                 "push" => count($pushNotifications),
        //                 "datename" =>  $value->format('F d, Y')
        //             ]);
        //         }
        //     }else{
        //         foreach ($period as $key => $value) {   
        //             $curr_date = $value->format('Y-m-d');
        //             $smsNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 0 AND branch_id=?", [$curr_date, $user->profile->branch->id])->get()->all();
        //             $pushNotifications = DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND is_push = 1 AND branch_id=?", [$curr_date, $user->profile->branch->id])->get()->all();

        //             array_push($data, [
        //                 "date" => $curr_date,
        //                 "sms" => count($smsNotifications),
        //                 "push" => count($pushNotifications),
        //                 "datename" => $value->format('F d, Y')
        //             ]);
        //         }
        //     }


        // }

        // return view("admin.notification.daily", [
        //     "data" => $data
        // ]);
    }
    public function yearly()
    {
        $user = auth()->user();
        $coverage = DB::table('notifications')->select(DB::raw('MIN(YEAR(notifications.datetime)) AS MinYear'))->first();
        $min_year = $coverage->MinYear;
        $max_year = date("Y");

        $data = [];

        if ($min_year == $max_year) {
            if ($user->is_admin) {
                $smsNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = YEAR(CURDATE()) AND is_push = 0")->get()->all();
                $pushNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = YEAR(CURDATE()) AND is_push = 1")->get()->all();

                array_push($data, [
                    "date" => $min_year,
                    "sms" => count($smsNotifications),
                    "push" => count($pushNotifications),
                    "datename" => $min_year
                ]);
            } else {
                $smsNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = YEAR(CURDATE()) AND is_push = 0 AND branch_id=?", [$user->profile->branch->id])->get()->all();
                $pushNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = YEAR(CURDATE()) AND is_push = 1 AND branch_id=?", [$user->profile->branch->id])->get()->all();

                array_push($data, [
                    "date" => $min_year,
                    "sms" => count($smsNotifications),
                    "push" => count($pushNotifications),
                    "datename" => $min_year
                ]);
            }
        } else {

            if ($user->is_admin) {
                for ($i = $min_year; $i <= intval($max_year); $i++) {
                    $smsNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = ? AND is_push = 0", [$i])->get()->all();
                    $pushNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = ? AND is_push = 1", [$i])->get()->all();
                    array_push($data, [
                        "date" => $i,
                        "sms" => count($smsNotifications),
                        "push" => count($pushNotifications),
                        "datename" =>  $i
                    ]);
                }
            } else {
                for ($i = $min_year; $i <= intval($max_year); $i++) {
                    $smsNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = ? AND is_push = 0 AND branch_id=?", [$i, $user->profile->branch->id])->get()->all();
                    $pushNotifications = DB::table('notifications')->whereRaw("YEAR(notifications.datetime) = ? AND is_push = 1 AND branch_id=?", [$i, $user->profile->branch->id])->get()->all();
                    array_push($data, [
                        "date" => $i,
                        "sms" => count($smsNotifications),
                        "push" => count($pushNotifications),
                        "datename" =>  $i
                    ]);
                }
            }
        }

        return view("admin.notification.daily", [
            "data" => $data
        ]);
    }


    public function publicDaily($branch_id, $date)
    {
        $notif_filter = "";
        $pdf_obj = App::make('dompdf.wrapper');
        $data = [
            "from" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y"),
            "to" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y")
        ];
        $data["branch"] = strtoupper(Branch::find($branch_id)->name);
        $data["data"] = Notification::with(["account", "transaction.service"])
            ->whereRaw("DATE(notifications.datetime) = ? AND branch_id = ? ", [$date, $branch_id])
            ->orderBy("datetime")
            ->get()
            ->all();

        $data["sms"] = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime) = ? AND branch_id = ? AND is_push = 0", [$date, $branch_id])->get()->all());
        $data["push"] = count(DB::table('notifications')->whereRaw("DATE(notifications.datetime)= ? AND branch_id = ? AND is_push = 1", [$date, $branch_id])->get()->all());
        $pdf = $pdf_obj->loadView('admin.reports.notification', ["data" => $data]);
        return $pdf->download("Notification Daily Reports($date).pdf");
    }
}
