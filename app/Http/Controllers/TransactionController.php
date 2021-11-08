<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Service;
use App\Models\Window;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;

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
            return Transaction::create([
                "token" => $this->token_formatter(1, $this->get_customer_type($request->get("account_number"))  == "priority"),
                "account_id" => $this->get_account_id($request->get("account_number")),
                "amount" => isset($request->all()["amount"]) ? $request->get("amount") : null,
                "mobile_number" => isset($request->all()["mobile_number"]) ? $request->get("mobile_number") : null,
                "is_notifiable" => isset($request->all()["mobile_number"]) ? true : false,
                "window_id" => $request->get("window_id"),
                "service_id" => $request->get("service_id"),
                "branch_id" => $request->get("branch_id"),
                "profile_id" => $request->get("profile_id")
            ]);
        }else{
            $last_token = $transactions_for_the_day[count($transactions_for_the_day) - 1];
            $tokens = Transaction::all()->where("branch_id", "=", $request->get("branch_id"))->where("state", "=", "waiting")->where("window_id", "=", $request->get("window_id"))->all();
            $token_format = $this->token_formatter($last_token->order + 1, $this->get_customer_type($request->get("account_number")) == "priority" );
            if(isset($request->all()["mobile_number"])){
                $estimated_waiting = (count($tokens) * 5);
                $waiting_time = $estimated_waiting == 0 ? "5" : strval($estimated_waiting);
                $message = "Dear customer, thank you for waiting. Your queue number " .  $token_format .  " will be called  in an estimated time of " .  $waiting_time. " minutes. Thank you.";
                $this->send_message_time($request->all()["mobile_number"], $message);
            }

            return Transaction::create([
                "token" =>$token_format,
                "order" => $last_token->order + 1,
                "account_id" => $this->get_account_id($request->get("account_number")),
                "amount" => isset($request->all()["amount"]) ? $request->get("amount") : null,
                "mobile_number" => isset($request->all()["mobile_number"]) ? $request->get("mobile_number") : null,
                "is_notifiable" => isset($request->all()["mobile_number"]) ? true : false,
                "window_id" => $request->get("window_id"),
                "service_id" => $request->get("service_id"),
                "branch_id" => $request->get("branch_id"),
                "profile_id" => $request->get("profile_id")
            ]);
            
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
        $state = $request->get("to_state");
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
        $transaction = Transaction::find($request->get("id"));
        $transaction->state = "waiting";
        $transaction->window_id = $request->get("window_id");
        $transaction->save();
        $transaction->profile_id = $transaction->window->profile->id;
        $transaction->save();

    }


    public function get_active_current($branch_id){
        $all_windows = Window::with(["profile"])->where("branch_id", "=", $branch_id)->get()->all();
        $data = [];

        foreach($all_windows as $window){
            $data[$window["order"]] = Transaction::all()->where("branch_id", "=", $branch_id)->where("state", "=", "serving")->where("window_id", "=", $window->id)->first();
        }

        echo json_encode($data);
    }
}
