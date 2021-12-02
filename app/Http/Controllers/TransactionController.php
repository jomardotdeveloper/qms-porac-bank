<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Service;
use App\Models\Window;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use DateTime;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $transactions = null;
        $branches = null;

        if($user->is_admin){
            $transactions = Transaction::all();
            $branches = Branch::all();
        }else{
            $transactions = Transaction::all()->where("branch_id", "=", $user->profile->branch->id)->all();
            $branches = Branch::all()->where("id", "=", $user->profile->branch->id)->all();
        }

        return view("admin.transaction.index", [
            "transactions" => $transactions,
            "branches" => $branches
        ]);
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
    
    public function generate_demo_data(Request $request){
        $date_from = new DateTime($request->get("from"));
        $date_to = new DateTime($request->get("to"));
        $interval = $date_from->diff($date_to);
        $number_of_days = 0;
        
        if($interval->y != 0){
            $number_of_days += ($interval->y * 365);
        }
        $year_from = intval($date_from->format("Y"));
        $year_to = intval($date_to->format("Y"));

        while(true){
            if($year_from > $year_to){
                break;
            }

            for($x = 1; $x <= 12; $x++){
                $number_of_days += cal_days_in_month(CAL_GREGORIAN, $x, $year_from);
            }

            $year_from++;
        }
        $number_of_days += $interval->d;


        $windows = Window::all()->where("branch_id", "=", $request->get("branch_id"))->all();
        $accounts = Account::all()->where("branch_id", "=", $request->get("branch_id"))->all();
        
        // for($day = 0; $day < $number_of_days; $day++){
        //     $your_date = strtotime($day . " day", strtotime($request->get("from")));
        //     $cur = date("Y-m-d", $your_date);
            
        //     for($tr = 0; $tr < intval($request->get("number_day")); $tr++){
        //         $random_window = rand(1,3);
        //         $random_account = rand(1,20);
        //         $random_service = rand(1,7);
        //         $is_drop = rand(0, 1);
        //         $account = Account::find($random_account);
        //         $window = Window::all()->where("order", "=", $random_window)->first();
        //          $transaction = Transaction::create([
        //             "token" => $this->token_formatter($tr + 1, $account->customer_type == "priority"),
        //             "account_id" => $account->id,
        //             "amount" => null,
        //             "mobile_number" => null,
        //             "is_notifiable" =>  "0",
        //             "window_id" => $window->id,
        //             "service_id" => $random_service,
        //             "branch_id" => $request->get("branch_id"),
        //             "profile_id" => $window->profile->id
        //         ]);
        //         $transaction->state = $is_drop =
        //     }
            
            
        //     return Transaction::create([
        //         "token" => $this->token_formatter(1, $this->get_customer_type($request->get("account_number"))  == "priority"),
        //         "account_id" => $this->get_account_id($request->get("account_number")),
        //         "amount" => isset($request->all()["amount"]) ? $request->get("amount") : null,
        //         "mobile_number" => isset($request->all()["mobile_number"]) ? $request->get("mobile_number") : null,
        //         "is_notifiable" =>  isset($request->all()["is_notifiable"]) ? $request->all()["is_notifiable"] : 0,
        //         "window_id" => $request->get("window_id"),
        //         "service_id" => $request->get("service_id"),
        //         "branch_id" => $request->get("branch_id"),
        //         "profile_id" => $request->get("profile_id")
        //     ]);

        // }

        
        // dd($sample_date);
        // dd($number_of_days);
        

        




        
    }


    public function export(Request $request){
        if($request->get("from") > $request->get("to")){
            return back()->withErrors([
                "date-error" => "Invalid date from and to."
            ]);
        }

        

        $services = Service::all();
        $transactions = Transaction::whereBetween("created_at", [$request->get("from"), $request->get("to")])->where("branch_id", "=", $request->get("branch_id"))->get()->all();
        $summary = [
            "start_date" => $request->get("from"),
            "end_date" => $request->get("to"),
            "total_number" => count($transactions),
            "services" => []
        ];

        foreach($services as $service){
            $transactions_in_service = Transaction::whereBetween("created_at", [$request->get("from"), $request->get("to")])->where("branch_id", "=", $request->get("branch_id"))->where("service_id", "=", $service->id)->get()->all();
            array_push($summary["services"], [
                "service" => $service->name,
                "count" => count($transactions_in_service)
            ]);
        }

        $pdf_obj = App::make('dompdf.wrapper');
        $pdf = $pdf_obj->loadView('admin.reports.transaction',[
            "branch" => Branch::find($request->get("branch_id")),
            "transactions" => $transactions,
            "summary" => $summary
        ]);
        return $pdf->download(Branch::find($request->get("branch_id"))->name . '.pdf');;
    }

    public function make(Request $request){
        $transactions_for_the_day = $this->get_prev_token($request->get("branch_id"));
        
        if(count($transactions_for_the_day) < 1){
            $created =  Transaction::create([
                "token" => $this->token_formatter(1, $this->get_customer_type($request->get("account_number"))  == "priority"),
                "account_id" => $this->get_account_id($request->get("account_number")),
                "amount" => isset($request->all()["amount"]) ? $request->get("amount") : null,
                "mobile_number" => isset($request->all()["mobile_number"]) ? $request->get("mobile_number") : null,
                "is_notifiable" =>  isset($request->all()["is_notifiable"]) ? $request->all()["is_notifiable"] : 0,
                "window_id" => $request->get("window_id"),
                "service_id" => $request->get("service_id"),
                "branch_id" => $request->get("branch_id"),
                "profile_id" => $request->get("profile_id")
            ]);

            return Transaction::find($created->id);
        }else{
            $last_token = $transactions_for_the_day[count($transactions_for_the_day) - 1];
            $token_format = $this->token_formatter($last_token->order + 1, $this->get_customer_type($request->get("account_number")) == "priority" );
            
            
            $created = Transaction::create([
                "token" =>$token_format,
                "order" => $last_token->order + 1,
                "account_id" => $this->get_account_id($request->get("account_number")),
                "amount" => isset($request->all()["amount"]) ? $request->get("amount") : null,
                "mobile_number" => isset($request->all()["mobile_number"]) ? $request->get("mobile_number") : null,
                "is_notifiable" => isset($request->all()["is_notifiable"]) ? $request->all()["is_notifiable"] : 0,
                "window_id" => $request->get("window_id"),
                "service_id" => $request->get("service_id"),
                "branch_id" => $request->get("branch_id"),
                "profile_id" => $request->get("profile_id")
            ]);
            
            if(isset($request->all()["mobile_number"])){
                if($created->is_notifiable){
                    $res = null;
                    if($this->isFirst($created)){
                        $res = $this->sendMessageFirst($created);
                    }else{
                        $res = $this->sendMessageInforming($created);
                    }
                    

                    if($res["status"] == 0){
                        Notification::create([
                            "account_id" => $created->account->id,
                            "message" => $res["message"],
                            "transaction_id" => $created->id,
                            "branch_id" => $created->branch->id
                        ]);
                    }
                }
            }

            return Transaction::find($created->id);
            
        }


    }

    public function get_account_id($account_number){
        return Account::all()->where("account_number", "=", $account_number)->first()->id;
    }
    
    public function get_customer_type($account_number){
        return Account::all()->where("account_number", "=", $account_number)->first()->customer_type;
    }
    
    public function token_formatter($order, $is_priority){
        $str = strval($order);

        if(strlen($str) == 1){
            return $is_priority ? "P0" . $str : "R0" . $str; 
        }else{
            return $is_priority ? "P" . $str : "R" . $str;
        }
    }

    public function get_prev_token($branch_id){
        $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ?", [$branch_id])->get()->all();
        return $transactions;
    }

    public function get_transactions_now($branch_id, $window_id){
        $transactions = Transaction::with([ "account", "service" ])->orderBy("order")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND window_id = ?", [$branch_id, $window_id])->get()->all();
        return $transactions;
    }

    public function sendMessageInforming($transaction){
        $tokens = DB::table("transactions")->whereRaw("
            DATE(transactions.in) = CURDATE() 
            AND branch_id = ? 
            AND state = 'waiting'
            AND window_id = ?
            AND id != ?
            AND transactions.order < ?", 
            [$transaction->branch->id, 
             $transaction->window->id,
             $transaction->id,
             $transaction->order])->get()->all();

        
        $current_token = DB::table("transactions")->whereRaw("DATE(transactions.created_at) = CURDATE() AND state = 'serving' AND window_id = ?", [ $transaction->window->id])->get()->first();
        $number_of_minutes = 5;
        $message = "";


        if($current_token != null){
            $message = "Dear customer, thank you for your patience; the current number served on " . $transaction->window->name . " is " .  $current_token->token . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 2) . " in the queue and will be called in approximately " .  (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }else{
            $message = "Dear customer, thank you for your patience; there is currently no customer being served in " . $transaction->window->name . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 1) . " in the queue and will be called in approximately " . (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }
        
        return  [
            "status" => $this->send_message_time($transaction->mobile_number, $message),
            "message" => $message
        ];
    }

    public function getInformingMessage($id){
        $transaction = Transaction::find($id);
        $tokens = DB::table("transactions")->whereRaw("
            DATE(transactions.in) = CURDATE() 
            AND branch_id = ? 
            AND state = 'waiting'
            AND window_id = ?
            AND id != ?
            AND transactions.order < ?", 
            [$transaction->branch->id, 
             $transaction->window->id,
             $transaction->id,
             $transaction->order])->get()->all();

        
        $current_token = DB::table("transactions")->whereRaw("DATE(transactions.created_at) = CURDATE() AND state = 'serving' AND window_id = ?", [ $transaction->window->id])->get()->first();
        $number_of_minutes = 5;
        $message = "";


        if($current_token != null){
            $message = "Dear customer, thank you for your patience; the current number served on " . $transaction->window->name . " is " .  $current_token->token . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 2) . " in the queue and will be called in approximately " .  (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }else{
            $message = "Dear customer, thank you for your patience; there is currently no customer being served in " . $transaction->window->name . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 1) . " in the queue and will be called in approximately " . (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }

        return [
            "message" => $message
        ];
    }

    public function getMessageFirst($id){
        $transaction = Transaction::find($id);
        $message = "Hello customer, your queue number " . $transaction->token . " is still active. Kindly proceed to " . $transaction->window->name . "  to receive the service. Thank you.";
        return [
            "message" => $message
        ];
    }

    public function getMessageTransferFirst($id){
        $transaction = Transaction::find($id);
        $message = "We'd like to inform you that you've been transfer to " .  $transaction->window->name  .  ". Your queue number " . $transaction->token . " is still active. Kindly proceed to " . $transaction->window->name . " to receive the service. Thank you.";
        return [
            "message" => $message
        ];
    }

    public function getMessageTransfer($id){
        $transaction = Transaction::find($id);
        $tokens = DB::table("transactions")->whereRaw("
            DATE(transactions.in) = CURDATE() 
            AND branch_id = ? 
            AND state = 'waiting'
            AND window_id = ?
            AND id != ?
            AND transactions.order < ?", 
            [$transaction->branch->id, 
             $transaction->window->id,
             $transaction->id,
             $transaction->order])->get()->all();
        $current_token = DB::table("transactions")->whereRaw("DATE(transactions.created_at) = CURDATE() AND state = 'serving' AND window_id = ?", [ $transaction->window->id])->get()->first();
        $number_of_minutes = 5;
        $message = "";


        if($current_token != null){
            $message = "We'd like to inform you that you've been transfer to ". $transaction->window->name . ". the current number served on " . $transaction->window->name . " is " .  $current_token->token . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 2) . " in the queue and will be called in approximately " .  (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }else{
            $message = "We'd like to inform you that you've been transfer to ". $transaction->window->name .". there is currently no customer being served in " . $transaction->window->name . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 1) . " in the queue and will be called in approximately " . (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }
        
        return [
            "message" => $message
        ];
    }   

    public function sendMessageFirst($transaction){
        $message = "Hello customer, your queue number " . $transaction->token . " is still active. Kindly proceed to " . $transaction->window->name . "  to receive the service. Thank you.";
        return  [
            "status" => $this->send_message_time($transaction->mobile_number, $message),
            "message" => $message
        ];
    }

    public function sendMessageTransferFirst($transaction){
        $message = "We'd like to inform you that you've been transfer to " .  $transaction->window->name  .  ". Your queue number " . $transaction->token . " is still active. Kindly proceed to " . $transaction->window->name . " to receive the service. Thank you.";
        return  [
            "status" => $this->send_message_time($transaction->mobile_number, $message),
            "message" => $message
        ];
    }

    public function sendMessageTransfer($transaction){
        $tokens = DB::table("transactions")->whereRaw("
            DATE(transactions.in) = CURDATE() 
            AND branch_id = ? 
            AND state = 'waiting'
            AND window_id = ?
            AND id != ?
            AND transactions.order < ?", 
            [$transaction->branch->id, 
             $transaction->window->id,
             $transaction->id,
             $transaction->order])->get()->all();
        $current_token = DB::table("transactions")->whereRaw("DATE(transactions.created_at) = CURDATE() AND state = 'serving' AND window_id = ?", [ $transaction->window->id])->get()->first();
        $number_of_minutes = 5;
        $message = "";


        if($current_token != null){
            $message = "We'd like to inform you that you've been transfer to ". $transaction->window->name . ". the current number served on " . $transaction->window->name . " is " .  $current_token->token . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 2) . " in the queue and will be called in approximately " .  (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }else{
            $message = "We'd like to inform you that you've been transfer to ". $transaction->window->name .". there is currently no customer being served in " . $transaction->window->name . ". Your queue number " . $transaction->token . " is " . $this->ordinal(count($tokens) + 1) . " in the queue and will be called in approximately " . (count($tokens) * $number_of_minutes) . " minutes. Thank you."  ;
        }
        
        return  [
            "status" => $this->send_message_time($transaction->mobile_number, $message),
            "message" => $message
        ];
    }   

    public function send_message_time($to, $message){

        if(strlen($to) == 10){
            $to = "0" . $to;
        }
        
        $client = new Client;
        $endpoint = 'https://www.itexmo.com/php_api/';

        $res = $client->post($endpoint . 'api.php',["form_params" => [
            '1' => $to,
            '2' => $message,
            '3' => "ST-JOMAR002958_J9U4Q",
            "passwd" => "[v%cuk5nsx"
        ]]);
        
        return $res->getBody()->getContents();
    }

    public function update_state(Request $request){
        $transaction = Transaction::find($request->get("id"));
        $state = $request->get("toState");
        if($state == "serving"){
            $transaction->state = "serving";
            $transaction->serve = date('Y-m-d H:i:s');
            $transaction->save();
        }else if($state == "out"){
            $transaction->state = "out";
            $transaction->serve = date('Y-m-d H:i:s');
            $transaction->save();
        }else if($state == "drop"){
            $transaction->state = "drop";
            $transaction->serve = date('Y-m-d H:i:s');
            $transaction->save();
        }

    }

    public function update_holder(Request $request){
        $transaction_ids = $request->get("ids");
        $window = Window::find($request->get("window_id"));
        $window_services=  $window->profile->service_ids;

        $data = [];

        // CHECKER
        foreach($transaction_ids as $id){
            $transaction = Transaction::find($id);

            if(!in_array($transaction->service->id, $window_services)){
                $data = [
                    "success" => 0,
                    "message" =>"Failed to switch transaction " . $transaction->token . ". " . $window->name . " does not have the customer's service."
                ];
                break;
            }
        }

        if(!isset($data["success"]) ){
            foreach($transaction_ids as $id){
                $transaction = Transaction::find($id);
                
                if($transaction->state == "serving"){
                    $transaction->state = "waiting";
                    $data["has_serving"] = 1;
                }
                
                $transaction->window_id = $request->get("window_id");
                $transaction->save();
                $transaction->profile_id = $transaction->window->profile->id;
                $transaction->save();
            }

            $data = [
                "success" => 1,
            ];
        }

        if(isset($data["has_serving"])){
            $data["has_serving"] = 0;
        }
        

        return $data;
    }


    public function get_active_current($branch_id){
        $all_windows = Window::with(["profile"])->where("branch_id", "=", $branch_id)->get()->all();
        $data = [];

        foreach($all_windows as $window){
            $data[$window["order"]] =DB::table("transactions")->whereRaw("DATE(transactions.created_at) = CURDATE() AND branch_id = ? AND state = 'serving' AND window_id = ?", [$branch_id, $window->id,])->get()->first();
        }

        echo json_encode($data);
    }

    public function get_mobile_transactions($delimeter){
        $separator = "YY";
        $data = [
            "data" => []
        ];
        if($delimeter != "AA"){
            if(str_contains($delimeter, $separator)){
                $ids = explode($separator, $delimeter);
                foreach($ids as $id){
                    $transaction = Transaction::find(intval($id));
                    array_unshift($data["data"], $transaction);
                }
            }else{
                $transaction = Transaction::find(intval($delimeter));
                array_unshift($data["data"], $transaction);
            }
        }

        return json_encode($data);
    }

    public function isFirst($transaction){
        $transactions = Transaction::with([ "account", "service" ])->orderBy("order")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ? AND window_id = ?", [$transaction->branch->id, $transaction->window->id])->get()->all();
        return $transaction["id"] == $transactions[0]["id"];
    }

    // API MESSAGING 

    public function sendSms($id, $is_transfer){
        $data = [];
        $transaction = Transaction::find($id);
        $state = ["waiting", "serving"];
        if(in_array($transaction->state, $state)){
            if($transaction->mobile_number != null){
                if($transaction->is_notifiable){
                    if(intval($is_transfer) == 1){
                        if($transaction->state == "serving" || $this->isFirst($transaction)){
                            $status = $this->sendMessageTransferFirst($transaction);
                            if(intval($status["status"]) == 0){
                                $data["status"] = 1;
                                $data["message"] = "Successfuly notified " . $transaction->token;
                                $data["log"] = $status["message"];
                            }else{
                                $data["status"] = 0;
                                $data["message"] = "There is a problem with the SMS server.";
                            }
                        }else{
                            $status = $this->sendMessageTransfer($transaction);
                            if(intval($status["status"]) == 0){
                                $data["status"] = 1;
                                $data["message"] = "Successfuly notified " . $transaction->token;
                                $data["log"] = $status["message"];
                            }else{
                                $data["status"] = 0;
                                $data["message"] = "There is a problem with the SMS server.";
                            }
                        }
                        
            
                    }else if(intval($is_transfer) == 0){
                        if($transaction->state == "serving" || $this->isFirst($transaction)){
                            $status = $this->sendMessageFirst($transaction);
                            if(intval($status["status"]) == 0){
                                $data["status"] = 1;
                                $data["message"] = "Successfuly notified " . $transaction->token;
                                $data["log"] = $status["message"];
                            }else{
                                $data["status"] = 0;
                                $data["message"] = "There is a problem with the SMS server.";
                            }
                        }else{
                            $status = $this->sendMessageInforming($transaction);
                            if(intval($status["status"]) == 0){
                                $data["status"] = 1;
                                $data["message"] = "Successfuly notified " . $transaction->token;
                                $data["log"] = $status["message"];
                            }else{
                                $data["status"] = 0;
                                $data["message"] = "There is a problem with the SMS server.";
                            }
                        }
                        
                    }
                }else{
                    $data["status"] = 0;
                    $data["message"] = "Token " . $transaction->token . " is not notifiable.";
                }
            }else{
                $data["status"] = 0;
                $data["message"] = "Token " . $transaction->token . " has no mobile number.";
            }
        }else{
            $data["status"] = 0;
            $data["message"] = "Token must be in waiting or serving state to send a sms notification.";
        }

        return $data;
    }

    public function getSms($id, $is_transfer){
        $data = [];
        $transaction = Transaction::find($id);
        $state = ["waiting", "serving"];
        if(in_array($transaction->state, $state)){
            if($transaction->mobile_number != null){
                if(true){
                    if(intval($is_transfer) == 1){
                        if($transaction->state == "serving" || $this->isFirst($transaction)){
                            $data["status"] = 1;
                            $data["message"] = "Successfuly notified " . $transaction->token;
                            $data["log"] = $this->getMessageTransfer($id)["message"];
                        }else{
                            $data["status"] = 1;
                            $data["message"] = "Successfuly notified " . $transaction->token;
                            $data["log"] = $this->getMessageTransfer($id)["message"];
                        }
                        
            
                    }else if(intval($is_transfer) == 0){
                        if($transaction->state == "serving" || $this->isFirst($transaction)){
                            $data["status"] = 1;
                            $data["message"] = "Successfuly notified " . $transaction->token;
                            $data["log"] = $this->getMessageFirst($id)["message"];
                        }else{
                            $data["status"] = 1;
                            $data["message"] = "Successfuly notified " . $transaction->token;
                            $data["log"] = $this->getInformingMessage($id)["message"];
                        }
                        
                    }
                }else{
                    $data["status"] = 0;
                    $data["message"] = "Token " . $transaction->token . " is not notifiable.";
                }
            }else{
                $data["status"] = 0;
                $data["message"] = "Token " . $transaction->token . " has no mobile number.";
            }
        }else{
            $data["status"] = 0;
            $data["message"] = "Token must be in waiting or serving state to send a sms notification.";
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

    public function set_notifiable($is_notifiable, $delimeter){
        $separator = "YY";
        $data = [
            "data" => []
        ];
        
        if($delimeter != "AA"){
            if(str_contains($delimeter, $separator)){
                $ids = explode($separator, $delimeter);
                foreach($ids as $id){
                    $transaction = Transaction::find(intval($id));
                    $transaction->is_notifiable = $is_notifiable;
                    $transaction->save();
                }
            }else{
                $transaction = Transaction::find(intval($delimeter));
                $transaction->is_notifiable = $is_notifiable;
                $transaction->save();
            }
        }

        return json_encode($data);
    }
}
