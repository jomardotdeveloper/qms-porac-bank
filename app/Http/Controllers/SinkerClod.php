<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;

class SinkerClod extends Controller
{
    public function sinkTransactions(Request $request){
        // foreach($request->get("transactions") as $transaction){
        //     $finder = Transaction::where("token", "=", $transaction["token"])->where("in", "=", $transaction["in"]);

        //     if($finder){
        //         $cur = 
        //     }
        //     // Transaction::with([ "account", "profile", "bill", "loan" ])->orderBy("order")->whereRaw("DATE(transactions.in) = CURDATE() AND state IN ('waiting', 'serving')  AND branch_id = ?", [$branch_id])->get()->all();
        // }
        return $request->get("transactions");
    }

    public function sinkTransactionsViaJson(){

    }
}
