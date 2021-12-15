<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Account;
use App\Models\Profile;
use App\Models\User;
use App\Models\Transaction;
use GuzzleHttp\Client;

class SinkerLoc extends Controller
{
    public function sinkTransactions(){
        $response = Http::post('https://www.poracbankqms.com/api/sinker_cloud/sink_transactions', [
            'name' => 'Steve',
            'role' => 'Network Administrator',
        ]);
        // dd($res);
    }

    public function getAllTransactions($branch_id){
        return Transaction::whereRaw("branch_id = ? ", [$branch_id])->get()->all();
        // return Transaction::with([ "account", "profile"])->whereRaw("DATE(transactions.in) = CURDATE() AND state IN ('waiting', 'serving')  AND branch_id = ?", [$branch_id])->get()->all();
    }

    public function sinkTransactionsViaJson(){

    }


    public function sinkUser(){

    }

    public function sinkAccount(){
        
    }

    public function transactionHasUnsink(){
        return count(Transaction::where("is_sync", "=", false)->get()->all()) > 0;
    }

    public function accountHasUnsink(){
        return count(Account::where("is_sync", "=", false)->get()->all()) > 0;
    }

    public function userHasUnsink(){
        return count(Profile::where("is_sync", "=", false)->get()->all()) > 0;
    }

    // IT CAN ONLY UPDATE THE TRANSACTION IN CURRENT DAY
    public function updateTransactionStatus($token){

    }

    public function getValidated(){
        $flag = false;
        $data = [
            "sinkUser" => 0,
            "sinkAccount" => 0,
            "viaJson" => 0
        ];

        if($this->userHasUnsink()){
            $data["sinkUser"] = 1;
            $flag = true;
        }

        if($this->accountHasUnsink()){
            $data["sinkAccount"] = 1;
            $flag = true;
        }

        if($flag)
            $data["viaJson"] = 1;

        return $data;
    }
}
