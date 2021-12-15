<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinkerClod extends Controller
{
    public function sinkTransactions(Request $request){
        return count($request->get("transactions"));
    }

    public function sinkTransactionsViaJson(){

    }
}
