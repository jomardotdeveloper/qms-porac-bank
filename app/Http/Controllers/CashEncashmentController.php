<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\Branch;
use App\Models\Transaction;
use DateTime;

class CashEncashmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $encashments = [];


        if ($user->is_admin) {
            $encashments = Transaction::where("service_id", "=", 3)->where("state", "!=", "waiting")->where("state", "!=", "serving")->where("state", "!=", "drop")->get()->all();
        } else {
            $encashments = Transaction::where("branch_id", "=", $user->profile->branch->id)->where("service_id", "=", 3)->where("state", "!=", "waiting")->where("state", "!=", "serving")->where("state", "!=", "drop")->get()->all();
        }


        return view("admin.cash-encashment.index", [
            "encashments" => $encashments,
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
        //
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

        if (auth()->user()->is_admin) {
            $branch_id = $request->get("branch_id");
        } else {
            $branch_id = auth()->user()->profile->branch->id;
        }

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);

        if ($request->get("pdf") != null) {
            $data["data"] = Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=3 AND branch_id=? AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all();
            $pdf = $pdf_obj->loadView('admin.reports.encashment', ["data" => $data]);
            return $pdf->download("Cash Encashment Reports($date_from - $date_to).pdf");
        }
    }

    public function publicDaily($branch_id, $date)
    {
        $data = [
            "from" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y"),
            "to" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y")
        ];
        $pdf_obj = App::make('dompdf.wrapper');
        $data["branch"] = strtoupper(Branch::find($branch_id)->name);
        $data["data"] = Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=3 AND branch_id=? AND state = 'out'", [$date, $branch_id])->get()->all();
        $pdf = $pdf_obj->loadView('admin.reports.encashment', ["data" => $data]);
        return $pdf->download("Cash Encashment Daily Reports($date).pdf");
    }
}
