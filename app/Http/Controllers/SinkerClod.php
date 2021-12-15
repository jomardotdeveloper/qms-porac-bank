<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Profile;
use App\Models\User;

class SinkerClod extends Controller
{
    public function sinkTransactions(Request $request){
        $data = [];
        $transactions = $request->get("transactions");
        foreach($transactions as $transaction){
            
            array_push($data, $transaction["token"]);
            $finder = Transaction::where("token", "=", $transaction["token"])->where("in", "=", $transaction["in"])->first();
            $profile = null;
            if($transaction["profile_id"] != null){
                if($this->userExists($transaction["profile"]["user"]["username"])){
                    $profile = $this->getUser($transaction["profile"]["user"]["username"])->id;
                }
            }

            if($finder){
                
                $finder->fill([
                    "token" => $transaction["token"],
                    "order" => $transaction["order"],
                    "state" => $transaction["state"],
                    "in" => $transaction["in"],
                    "out" => $transaction["out"],
                    "drop" => $transaction["drop"],
                    "serve" => $transaction["serve"],
                    "amount" => $transaction["amount"],
                    "mobile_number" => $transaction["mobile_number"],
                    "is_notifiable" => $transaction["is_notifiable"],
                    "is_mobile" => $transaction["is_mobile"],
                    "is_sync" => true,
                    "servedtime" => $transaction["servedtime"],
                    "bill_id" => $transaction["bill_id"],
                    "loan_id" => $transaction["loan_id"],
                    "window_id" => $transaction["window_id"],
                    "service_id" => $transaction["service_id"],
                    "branch_id" => $transaction["branch_id"],
                    "profile_id" => $profile
                ]);
                $finder->save();
            }else{
                array_push($data, $transaction["token"]);
                $account = null;
              
                if($transaction["account_id"] != null){
                    if($this->accountExists($transaction["account"]["account_number"])){
                        $account = $this->getAccount($transaction["account"]["account_number"])->id;
                    }
                }

                if($transaction["profile_id"] != null){
                    if($this->userExists($transaction["profile"]["user"]["username"])){
                        $profile = $this->getUser($transaction["profile"]["user"]["username"])->id;
                    }
                }

                $cur = Transaction::create([
                    "token" => $transaction["token"],
                    "order" => $transaction["order"],
                    "account_id" => $account,
                    "state" => $transaction["state"],
                    "in" => $transaction["in"],
                    "out" => $transaction["out"],
                    "drop" => $transaction["drop"],
                    "serve" => $transaction["serve"],
                    "amount" => $transaction["amount"],
                    "mobile_number" => $transaction["mobile_number"],
                    "is_notifiable" => $transaction["is_notifiable"],
                    "is_mobile" => $transaction["is_mobile"],
                    "is_sync" => true,
                    "servedtime" => $transaction["servedtime"],
                    "bill_id" => $transaction["bill_id"],
                    "loan_id" => $transaction["loan_id"],
                    "window_id" => $transaction["window_id"],
                    "service_id" => $transaction["service_id"],
                    "branch_id" => $transaction["branch_id"],
                    "profile_id" => $profile
                ]); 

                $cur->save();
            }
            // Transaction::with([ "account", "profile", "bill", "loan" ])->orderBy("order")->whereRaw("DATE(transactions.in) = CURDATE() AND state IN ('waiting', 'serving')  AND branch_id = ?", [$branch_id])->get()->all();
        }
        return $data;
    }

    public function getAllTransactions($branch_id){
        return Transaction::with([ "account", "profile.user"])->whereRaw("branch_id = ? ", [$branch_id])->get()->all();
        // return Transaction::with([ "account", "profile"])->whereRaw("DATE(transactions.in) = CURDATE() AND state IN ('waiting', 'serving')  AND branch_id = ?", [$branch_id])->get()->all();
    }

    public function getAccount($account_number){
        return Transaction::where("account_number", "=", $account_number)->get()->all()[0];
    }

    public function accountExists($account_number){
        return count(Transaction::where("account_number", "=", $account_number)->get()->all()) > 0;
    }

    public function getUser($username){
        return User::where("username", "=", $username)->get()->all()[0]->profile;
    }

    public function userExists($username){
        return count(User::where("username", "=", $username)->get()->all()) > 0;
    }

}
