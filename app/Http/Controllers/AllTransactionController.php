<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Bill;
use App\Models\Service;
class AllTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $alls = [];


        if($user->is_admin){
            $alls = Transaction::all();
        }else{
            $alls = Transaction::where("branch_id", "=", $user->profile->branch->id)->get()->all();
        }


        return view("admin.all-transaction.index",[
            "alls" => $alls,
            "branches" => Branch::all(),
            "services" => Service::all()
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

    public function export(Request $request){
       $status_filter = "";
       $platform_filter = " ";
       $service_filter = "";
       $data = [
        "from" => date_format(date_create($request->get("from")), "F d, Y"),
        "to" => date_format(date_create($request->get("to")), "F d, Y")
        ];
       $branch_id = 0;

       $pdf_obj = App::make('dompdf.wrapper');
       $date_from = $request->get("from");
       $date_to =  $request->get("to");


        if($request->get("from") > $request->get("to")){
            return back()->withErrors([
                "date-error" => "Date from must not be greater than date to."
            ]);
        }


        if(auth()->user()->is_admin){
            $branch_id = $request->get("branch_id");
        }else{ 
            $branch_id = auth()->user()->profile->branch->id;
        }

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);

       if(intval($request->get("service_id")) != 0){
            $data["service"] = Service::find($request->get("service_id"));
            $service_filter = " AND service_id= " . $request->get("service_id") . " ";
       }

       if(intval($request->get("status")) != 0){
           if(intval($request->get("status")) == 1){
               $data["status"] = "Waiting";
               $status_filter = " AND state='waiting' ";
           }else if(intval($request->get("status")) == 2){
                $data["status"] = "Served";
                $status_filter = " AND state='out' ";
           }else if(intval($request->get("status")) == 3){
                $data["status"] = "Dropped";
                $status_filter = " AND state='drop' ";
           }else if(intval($request->get("status")) == 4){
                $data["status"] = "Serving";
                $status_filter = " AND state='serving' ";
           }
       }

       if(intval($request->get("platform")) != 0){
            if(intval($request->get("platform")) == 1){
                $data["platform"] = "Mobile Application";
                $platform_filter = " AND is_mobile=1 ";
            }else if(intval($request->get("platform")) == 2){
                $data["platform"] = "Kiosk Machine";
                $platform_filter = " AND is_mobile=0 ";
            }
       }

       if($request->get("pdf") != null){
            $data["data"] = Transaction::with([ "account",  "bill"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND branch_id=? $service_filter $status_filter $platform_filter ", [$request->get("from"), $request->get("to"), $branch_id])->get()->all();
            $pdf = $pdf_obj->loadView('admin.reports.all', ["data" => $data]);
            return $pdf->download("Transaction Reports($date_from - $date_to).pdf");
       }
       
    }
}
