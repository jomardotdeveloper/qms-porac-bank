<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinkerClod extends Controller
{
    public function sinkTransactions(Request $request){
        return $request->get("transactions");
    }

    public function sinkTransactionsViaJson(){

    }
}
