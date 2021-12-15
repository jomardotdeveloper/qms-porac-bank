<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SinkerClod extends Controller
{
    public function sinkTransactions(Request $request){
        return count($request->get("transactions")) > 0;
    }

    public function sinkTransactionsViaJson(){

    }
}
