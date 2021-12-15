<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Profile;
use App\Models\User;
use App\Models\Transaction;
use GuzzleHttp\Client;

class SinkerLoc extends Controller
{
    public function sinkTransactions(){
        $client = new Client;
        $endpoint = 'https://www.poracbankqms.com/api/';
        $res = $client->post($endpoint . 'sinker_clod_transaction',["form_params" => [
            "transactions" => Transaction::all()
        ]]);
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

    public function sinkAll(){
        $flag = false;

        if($this->userHasUnsink()){
            $this->sinkUser();
            $flag = true;
        }

        if($this->accountHasUnsink()){
            $this->sinkAccount();
            $flag = true;
        }

        if($flag)
            $this->sinkTransactionsViaJson();
        else
            $this->sinkTransactions();
    }
}
