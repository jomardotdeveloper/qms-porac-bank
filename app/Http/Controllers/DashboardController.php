<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $data = ["quotes" => Inspiring::quote()];
        
        if($user->is_admin){
            $data["transactions"] = Transaction::all();
            $data["branches"] = Branch::all();
            $data["profiles"] = Profile::all();
            $data["accounts"] = Account::all();
        }else{
            $data["period"] = $this->getNumberOfTransactionPerPeriod();
            $data["transactions"] = Transaction::all()->where("branch_id", "=", $user->profile->branch->id)->all();
            $data["profiles"] = Profile::all()->where("branch_id", "=", $user->profile->branch->id)->where("id", "!=", $user->profile->id)->all();
            $data["accounts"] = Account::all()->where("branch_id", "=", $user->profile->branch->id)->all();
        }


        return view("admin.dashboard.index", $data);
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

    // 2021-10-26
    public function getDayNow($is_admin = false){
        $transactions = null;
        
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE()")->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ?", [
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        
        return $transactions;
    }

    public function getDayPrev($is_admin = false){
        $transactions = null;

        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = ?", [
                date("Y-m-d", strtotime("-1 days"))
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = ? AND branch_id = ?", [
                date("Y-m-d", strtotime("-1 days")),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        
        return $transactions;
    }

    public function getMonthNow($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ?", [
                date("m"),
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ? AND branch_id = ?", [
                date("m"),
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getMonthPrev($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ?", [
                date("m" ,strtotime("-1 months")),
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ? AND branch_id = ?", [
                date("m", strtotime("-1 months")),
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getYearNow($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ?", [
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND branch_id = ?", [
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getYearPrev($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ?", [
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND branch_id = ?", [
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getNumberOfTransactionPerPeriod($is_admin = false){
        $data = [];

        $data["day"] = [
            "now" => count($this->getDayNow($is_admin)),
            "prev" => count($this->getDayPrev($is_admin)),
            "percent" => ""
        ];

        $data["month"] = [
            "now" => count($this->getMonthNow($is_admin)),
            "prev" => count($this->getMonthPrev($is_admin)),
            "percent" => ""
        ];

        $data["year"] = [
            "now" => count($this->getYearNow($is_admin)),
            "prev" => count($this->getYearPrev($is_admin)),
            "percent" => ""
        ];

        return $data;
    }

}
