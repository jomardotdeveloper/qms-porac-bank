<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Account;
use App\Models\Window;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Exception;

class MessagingController extends Controller
{
    public function messageDrop($id)
    {
        $transaction = Transaction::find($id);
        $token = $transaction->token;
        $data = [];
        $message = "Greetings! Due to your failure to appear inside the bank, your ticket number $token was discarded.";

        if ($transaction->is_notifiable && $transaction->mobile_number != null) {
            $sent = $this->itextMoSend($transaction->mobile_number, $message);
            if (intval($sent) == 0) {
                $data["status"] = 1;
                $data["log"] = $message;
            } else {
                $data["status"] = 0;
            }
        } else {
            $data["message"] = $transaction->token;
            $data["status"] = 0;
        }

        return $data;
    }

    public function pushNotifDrop($id)
    {
        $transaction = Transaction::find($id);
        $token = $transaction->token;
        $data = [];
        $message = "Greetings! Due to your failure to appear inside the bank, your ticket number $token was discarded.";
        $data["status"] = 1;
        $data["log"] = $message;
        $data["token"] = $token;
        $data["datetime"] = $transaction->in;
        $data["service"] = $transaction->service->name;
        $data["branch_id"] = $transaction->branch->id;

        return $data;
    }

    public function getOrder($id)
    {
        $transaction = Transaction::find($id);
        $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND state = 'waiting'", [$transaction->branch->id])->get()->all();
        $count = 0;


        foreach ($transactions as $t) {
            if ($transaction->order > $t->order) {
                $count++;
            } else if ($transaction->order < $t->order) {
                if ($t->account_id != null) {
                    $account = Account::find($t->account_id);
                    if ($account->customer_type == "priority") {
                        $count++;
                    }
                }
            }
        }

        if ($this->hasServing($transaction->branch->id)) {
            return $count + 2;
        }

        return $count + 1;
    }

    public function getAheadCustomer($id)
    {
        $transaction = Transaction::find($id);
        $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND state = 'waiting'", [$transaction->branch->id])->get()->all();
        $count = 0;


        foreach ($transactions as $t) {
            if ($transaction->order > $t->order) {
                $count++;
            } else if ($transaction->order < $t->order) {
                if ($t->account_id != null) {
                    $account = Account::find($t->account_id);
                    if ($account->customer_type == "priority") {
                        $count++;
                    }
                }
            }
        }

        return $count;
    }

    public function hasServing($branch_id)
    {
        $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND state = 'serving'", [$branch_id])->get()->all();
        return count($transactions) > 0;
    }

    public function sendMessage($id, $isPrioMessage)
    {
        $transaction = Transaction::find($id);
        $windows = $this->get_active_current($transaction->branch->id);
        $window_1 = $windows[1] == null ? "NONE" : $windows[1]->token;
        $window_2 = $windows[2] == null ? "NONE" : $windows[2]->token;
        $window_3 = $windows[3] == null ? "NONE" : $windows[3]->token;

        $token = $transaction->token;
        $waitingTime = $this->getEstimateWaitingTime($id);
        $order = $this->ordinal($this->getOrder($id));

        $data = [];

        if ($transaction->is_notifiable && $transaction->mobile_number != null) {
            if (intval($isPrioMessage) == 1) {
                $message = $this->getMessagePrio($waitingTime, $window_1, $window_2, $window_3);
                $sent = $this->itextMoSend($transaction->mobile_number, $message);
                if (intval($sent) == 0) {
                    $data["status"] = 1;
                    $data["log"] = $message;
                } else {
                    $data["status"] = 0;
                }
            } else {
                if ($transaction->state == "serving") {
                    $my_window =  $transaction->window->order;
                    $message = $this->getMessageFirst($my_window, $token);
                    $sent = $this->itextMoSend($transaction->mobile_number, $message);
                    if (intval($sent) == 0) {
                        $data["status"] = 1;
                        $data["log"] = $message;
                    } else {
                        $data["status"] = 0;
                    }
                } else if ($transaction->state == "waiting") {
                    $message = $this->getMessageQueue($token, $order, $waitingTime, $window_1, $window_2, $window_3);
                    $sent = $this->itextMoSend($transaction->mobile_number, $message);
                    if (intval($sent) == 0) {
                        $data["status"] = 1;
                        $data["log"] = $message;
                    } else {
                        $data["status"] = 0;
                    }
                }
            }
        } else {
            $data["status"] = 0;
        }


        return $data;
    }

    public function getNotification($id, $isPrioMessage)
    {
        $transaction = Transaction::find($id);
        $windows = $this->get_active_current($transaction->branch->id);
        $window_1 = $windows[1] == null ? "NONE" : $windows[1]->token;
        $window_2 = $windows[2] == null ? "NONE" : $windows[2]->token;
        $window_3 = $windows[3] == null ? "NONE" : $windows[3]->token;

        $token = $transaction->token;
        $waitingTime = $this->getEstimateWaitingTime($id);
        $order = $this->ordinal($this->getOrder($id));

        $data = [];

        if (true) {
            if (intval($isPrioMessage) == 1) {
                $message = $this->getMessagePrio($waitingTime, $window_1, $window_2, $window_3);
                // $sent = $this->itextMoSend($transaction->mobile_number, $message);
                $data["status"] = 1;
                $data["log"] = $message;
                $data["token"] = $token;
                $data["datetime"] = $transaction->in;
                $data["branch_id"] = $transaction->branch->id;
                $data["service"] = $transaction->service->name;
            } else {
                if ($transaction->state == "serving") {
                    $my_window =  $transaction->window->order;
                    $message = $this->getMessageFirst($my_window, $token);
                    $data["status"] = 1;
                    $data["log"] = $message;
                    $data["token"] = $token;
                    $data["datetime"] = $transaction->in;
                    $data["service"] = $transaction->service->name;
                    $data["branch_id"] = $transaction->branch->id;
                } else if ($transaction->state == "waiting") {
                    $message = $this->getMessageQueue($token, $order, $waitingTime, $window_1, $window_2, $window_3);
                    $data["status"] = 1;
                    $data["log"] = $message;
                    $data["token"] = $token;
                    $data["datetime"] = $transaction->in;
                    $data["service"] = $transaction->service->name;
                    $data["branch_id"] = $transaction->branch->id;
                }
            }
        } else {
            $data["status"] = 0;
        }


        return $data;
    }

    public function getMessageFirst($window, $token)
    {
        return "Hello, dear customer! Your queue number $token have reached the front of the Queue. Kindly proceed to Window $window to receive the service. Thank you.";
    }

    public function getMessagePrio($waitingTime, $window_1, $window_2, $window_3)
    {
        return "Hello, customer! We'd like to inform you that a priority customer has been added to the line. Your wait time is estimated to be $waitingTime minutes. This is the queue status: \nWindow 1 : $window_1 \nWindow 2 : $window_2 \nWindow 3 : $window_3\nThank you.";
    }

    public function getMessageQueue($token, $order, $waitingTime, $window_1, $window_2, $window_3)
    {
        if ($waitingTime == 0) {
            return "Hello customer! Thank you for your patience. Your queue number $token is $order in the queue. This is the queue status: \nWindow 1 : $window_1 \nWindow 2 : $window_2 \nWindow 3 : $window_3\nThank you. ";
        } else {
            return "Hello customer! Thank you for your patience. Your queue number $token is $order in the queue and will be called in approximately $waitingTime minutes. This is the queue status: \nWindow 1 : $window_1 \nWindow 2 : $window_2 \nWindow 3 : $window_3\nThank you. ";
        }
    }

    public function get_active_current($branch_id)
    {
        $all_windows = Window::with(["profile"])->where("branch_id", "=", $branch_id)->get()->all();
        $data = [];

        foreach ($all_windows as $window) {
            $data[$window["order"]] = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND state = 'serving' AND window_id = ?", [$branch_id, $window->id,])->get()->first();
        }

        return $data;
    }


    function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }

    public function getEstimateWaitingTime($id)
    {
        $transaction = Transaction::find($id);
        $burst_time = $this->getBurstTime($transaction->service->id, $transaction->branch->id);
        $numberOfAheadCustomer = $this->getAheadCustomer($id);
        $waiting_time = $burst_time * $numberOfAheadCustomer;

        return $waiting_time;
    }

    public function getBurstTime($service_id, $branch_id)
    {
        $transactions = Transaction::where("branch_id", $branch_id)->where("service_id", $service_id)->where("state", "out")->get()->all();

        if (count($transactions) < 1) {
            return 5;
        } else {
            $tr = DB::table("transactions")->select(DB::raw("round(AVG(servedtime),0) as aveg"))->where("branch_id", $branch_id)->where("service_id", $service_id)->where("state", "out")->get();
            if ($tr[0]->aveg) {
                return (intval($tr[0]->aveg) +  5) / 2;
            } else {
                return 5;
            }
        }
    }

    public function itextMoSend($to, $message)
    {
        if (strlen($to) == 10) {
            $to = "0" . $to;
        }

        $client = new Client;
        $endpoint = 'https://www.itexmo.com/php_api/';

        try {
            $res = $client->post($endpoint . 'api.php', ["form_params" => [
                '1' => $to,
                '2' => $message,
                '3' => "ST-SAIRA416151_ILJ5C",
                "passwd" => "%p5d)1]pq9"
            ]]);
            return $res->getBody()->getContents();
        } catch (Exception $e) {
            return 1;
        }
    }
}
