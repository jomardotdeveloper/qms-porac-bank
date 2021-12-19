<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Window;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
class MessagingController extends Controller
{
    public function getOrder($id){
        $transaction = Transaction::find($id);
        $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND state = 'waiting'", [$transaction->branch->id])->get()->all();
        $count = 0;
        

        foreach($transactions as $t){
            if($transaction->order > $t->order){
                $count++;
            }else if($transaction->order < $t->order){
                if($t->account_id != null){
                    $account = Account::find($t->account_id);
                    if($account->customer_type == "priority"){
                        $count++;
                    }
                }
            }
        }


        if($this->hasServing($transaction->branch->id)){
            return $count + 2;
        }

        return $count + 1;
    }

    public function hasServing($branch_id){
        $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND state = 'serving'", [$branch_id])->get()->all();
        return count($transactions) > 0;
    }

    public function sendMessage($id, $isPrioMessage){
        $transaction = Transaction::find($id);
        $windows = $this->get_active_current($transaction->branch->id);
        $window_1 = $windows[1] == null ? "NONE" : $windows[1];
        $window_2 = $windows[2] == null ? "NONE" : $windows[2];
        $window_3 = $windows[3] == null ? "NONE" : $windows[3];

        $token = $transaction->token;
        $waitingTime = $this->getEstimateWaitingTime($id);
        $order = $this->ordinal($this->getOrder($id));

        $data = [];

        if($transaction->is_notifiable && $transaction->mobile_number != null){
            if(intval($isPrioMessage) == 1){
                $message = $this->getMessagePrio($waitingTime, $window_1, $window_2, $window_3);
                $sent = $this->itextMoSend($transaction->mobile_number, $message);
                if(intval($sent) == 0){
                    $data["status"] = 1;
                    $data["log"] = $message;
                }else{
                    $data["status"] = 0;
                }
            }else{
                if($transaction->state == "serving"){
                    $my_window =  $transaction->window->order;
                    $message = $this->getMessageFirst($my_window, $token);
                    $sent = $this->itextMoSend($transaction->mobile_number, $message);
                    if(intval($sent) == 0){
                        $data["status"] = 1;
                        $data["log"] = $message;
                    }else{
                        $data["status"] = 0;
                    }
                }else if($transaction->state == "waiting"){
                    $message = $this->getMessageQueue($token, $order, $waitingTime, $window_1, $window_2, $window_3);
                    $sent = $this->itextMoSend($transaction->mobile_number, $message);
                    if(intval($sent) == 0){
                        $data["status"] = 1;
                        $data["log"] = $message;
                    }else{
                        $data["status"] = 0;
                    }
                }
            }

            
        }else{
            $data["status"] = 0;
        }

        
        return $data;
    }

    public function getNotification($id, $isPrioMessage){
        $transaction = Transaction::find($id);
        $windows = $this->get_active_current($transaction->branch->id);
        $window_1 = $windows[1] == null ? "NONE" : $windows[1];
        $window_2 = $windows[2] == null ? "NONE" : $windows[2];
        $window_3 = $windows[3] == null ? "NONE" : $windows[3];

        $token = $transaction->token;
        $waitingTime = $this->getEstimateWaitingTime($id);
        $order = $this->ordinal($this->getOrder($id));

        $data = [];

        if(true){
            if(intval($isPrioMessage) == 1){
                $message = $this->getMessagePrio($waitingTime, $window_1, $window_2, $window_3);
                // $sent = $this->itextMoSend($transaction->mobile_number, $message);
                $data["status"] = 1;
                $data["log"] = $message;
                $data["token"] = $token;
                $data["datetime"] = $transaction->in;
                $data["branch_id"] = $transaction->branch->id;
                $data["service"] = $transaction->service->name;
            }else{
                if($transaction->state == "serving"){
                    $my_window =  $transaction->window->order;
                    $message = $this->getMessageFirst($my_window, $token);
                    $data["status"] = 1;
                    $data["log"] = $message;
                    $data["token"] = $token;
                    $data["datetime"] = $transaction->in;
                    $data["service"] = $transaction->service->name;
                    $data["branch_id"] = $transaction->branch->id;
                }else if($transaction->state == "waiting"){
                    $message = $this->getMessageQueue($token, $order, $waitingTime, $window_1, $window_2, $window_3);
                    $sent = $this->itextMoSend($transaction->mobile_number, $message);
                    $data["status"] = 1;
                    $data["log"] = $message;
                    $data["token"] = $token;
                    $data["datetime"] = $transaction->in;
                    $data["service"] = $transaction->service->name;
                    $data["branch_id"] = $transaction->branch->id;
                }
            }

            
        }else{
            $data["status"] = 0;
        }

        
        return $data;
    }

    public function getMessageFirst($window , $token){
        return "Hello, dear customer! You have reached the front of the Queue. Window $window is waiting for your queue number $token. Kindly proceed to Window $window to receive the service. Thank you.";
    }

    public function getMessagePrio($waitingTime, $window_1, $window_2, $window_3){
        return "Hello, customer! We'd like to inform you that a priority customer has been added to the line. Your wait time is estimated to be $waitingTime minutes. This is the queue status: \nWindow 1 : $window_1 \nWindow 2 : $window_2 \nWindow 3 : $window_3\nThank you.";
    }

    public function getMessageQueue($token, $order, $waitingTime, $window_1, $window_2, $window_3){
        return "Hello customer! Thank you for your patience. Your queue number $token is $order in the queue and will be called in approximately $waitingTime minutes. This is the queue status: \nWindow 1 : $window_1 \nWindow 2 : $window_2 \nWindow 3 : $window_3\nThank you. ";
    }




    public function get_active_current($branch_id){
        $all_windows = Window::with(["profile"])->where("branch_id", "=", $branch_id)->get()->all();
        $data = [];

        foreach($all_windows as $window){
            $data[$window["order"]] =DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND state = 'serving' AND window_id = ?", [$branch_id, $window->id,])->get()->first();
        }

        return $data;
    }


    function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public function getEstimateWaitingTime($id){
        $burst_time = 5;
        // $transactions

        // $transaction = Transaction::find($id);
        return $burst_time;
    }

    public function itextMoSend($to, $message){
        if(strlen($to) == 10){
            $to = "0" . $to;
        }

        $client = new Client;
        $endpoint = 'https://www.itexmo.com/php_api/';

        $res = $client->post($endpoint . 'api.php',["form_params" => [
            '1' => $to,
            '2' => $message,
            '3' => "ST-SAIRA416151_ILJ5C",
            "passwd" => "%p5d)1]pq9"
        ]]);
        
        return $res->getBody()->getContents();
    }   
}
