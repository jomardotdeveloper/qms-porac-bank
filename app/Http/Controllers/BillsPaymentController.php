<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Bill;
use DateTime;

class BillsPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $bills = [];


        if ($user->is_admin) {
            $bills = Transaction::where("service_id", "=", 4)->where("state", "!=", "waiting")->where("state", "!=", "serving")->where("state", "!=", "drop")->get()->all();
        } else {
            $bills = Transaction::where("branch_id", "=", $user->profile->branch->id)->where("service_id", "=", 4)->where("state", "!=", "waiting")->where("state", "!=", "serving")->where("state", "!=", "drop")->get()->all();
        }


        return view("admin.bills-payment.index", [
            "bills" => $bills,
            "branches" => Branch::all(),
            "billers" => Bill::all()
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
        $filter = "";
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

        if (intval($request->get("biller_id")) != 0) {
            $data["biller"] = Bill::find($request->get("biller_id"));
            $filter = " AND bill_id=" . $request->get("biller_id");
        } else {
            $data["billers_data"] = [
                "1" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=1 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "2" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=2 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "3" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=3 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "4" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=4 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "5" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=5 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "6" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=6 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "7" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=7 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "8" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=8 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all()),
                "9" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND bill_id=9 AND state = 'out'", [$request->get("from"), $request->get("to"), $branch_id])->get()->all())
            ];
        }

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);

        if ($request->get("pdf") != null) {
            $data["data"] = Transaction::with(["account",  "bill"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND service_id=4 AND branch_id=? AND state = 'out' $filter", [$request->get("from"), $request->get("to"), $branch_id])->get()->all();
            $pdf = $pdf_obj->loadView('admin.reports.bill', ["data" => $data]);
            return $pdf->download("Bills Payment Reports($date_from - $date_to).pdf");
        }
    }

    public function publicDaily($branch_id, $date)
    {
        $data = [
            "from" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y"),
            "to" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y")
        ];
        $pdf_obj = App::make('dompdf.wrapper');

        $data["billers_data"] = [
            "1" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=1 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "2" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=2 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "3" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=3 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "4" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=4 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "5" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=5 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "6" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=6 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "7" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=7 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "8" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=8 AND state = 'out'", [$date, $branch_id])->get()->all()),
            "9" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND bill_id=9 AND state = 'out'", [$date, $branch_id])->get()->all())
        ];

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);
        $data["data"] = Transaction::with(["account",  "bill"])->whereRaw("DATE(transactions.in) = ? AND service_id=4 AND branch_id=? AND state = 'out' ", [$date, $branch_id])->get()->all();
        $pdf = $pdf_obj->loadView('admin.reports.bill', ["data" => $data]);
        return $pdf->download("Bills Payment Daily Reports($date).pdf");
    }
}
