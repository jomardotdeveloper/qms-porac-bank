<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Window;
use App\Models\Transaction;
use App\Models\Service;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Builder;
class WindowController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
    public function update(Request $request, Window $window)
    {
        $window_ids = [$request->get("id_1"), $request->get("id_2"), $request->get("id_3")];

        $window_1 = $request->get("window_1");
        $window_2 = $request->get("window_2");
        $window_3 = $request->get("window_3");
        $window_priority = $request->get("priority_window");
        
        if (($window_1 == $window_2) || ($window_2 == $window_3) || ($window_3 == $window_1)) {
            return back()->withErrors([
                "failed-window" => "Windows cannot have the same user."
            ]);    
        }else{
            $id_1 = Window::find($window_ids[0]);
            $id_1->profile_id = $window_1;
            $id_1->save();

            $id_2 = Window::find($window_ids[1]);
            $id_2->profile_id = $window_2;
            $id_2->save();

            $id_3 = Window::find($window_ids[2]);
            $id_3->profile_id = $window_3;
            $id_3->save();

            $current_active = Window::all()->where("branch_id", "=", 1)->where("id", "!=", $window_priority)->where("is_priority", "=", true)->first();
            if($current_active){
                $current_active->is_priority = false;
                $current_active->save();
                $prio = Window::find($window_priority);
                $prio->is_priority = true;
                $prio->save();
            }else{
                $prio = Window::find($window_priority);
                $prio->is_priority = true;
                $prio->save();
            }
            

            return back()->withErrors([
                "success-window" => "Your changes were successfully applied"
            ]); 
        }
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

    public function get_available_window($branch_id, $service_id, $is_priority){
        $all_windows = Window::all()->where("branch_id", "=", $branch_id)->all();
        $priority_window = Window::all()->where("branch_id", "=", $branch_id)->where("is_priority", "=", true)->first();
        $non_prio_window = Window::all()->where("branch_id", "=", $branch_id)->where("is_priority", "=", false)->all();
        $non_prio_window_cloned = [];

        foreach($non_prio_window as $w){
            array_push($non_prio_window_cloned, $w);
        }
        if($is_priority == 1){
            if($priority_window == null){
                $data = [
                    "status" => "0",
                    "message" => "The branch doesn't have any priority window. Kindly contact the administrator."
                ];

                echo json_encode($data);
                return;
            }

            if($this->has_active_transaction($priority_window->id)){
                if(!$this->has_active_transaction($non_prio_window_cloned[0]->id)){
                    if($this->user_has_this_service($non_prio_window_cloned[0]->profile->id, $service_id)){
                        $data = [
                            "status" => "1",
                            "window_id" => $non_prio_window_cloned[0]->id,
                            "profile_id" => $non_prio_window_cloned[0]->profile->id
                        ];
        
                        echo json_encode($data);
                        return;
                    }
                }

                if(!$this->has_active_transaction($non_prio_window_cloned[1]->id)){
                    if($this->user_has_this_service($non_prio_window_cloned[1]->profile->id, $service_id)){
                        $data = [
                            "status" => "1",
                            "window_id" => $non_prio_window_cloned[1]->id,
                            "profile_id" => $non_prio_window_cloned[1]->profile->id
                        ];
        
                        echo json_encode($data);
                        return;
                    }
                }

                $data = [
                    "status" => "1",
                    "window_id" => $priority_window->id,
                    "profile_id" => $priority_window->profile->id
                ];

                echo json_encode($data);
                return;
            }else{
                $data = [
                    "status" => "1",
                    "window_id" => $priority_window->id,
                    "profile_id" => $priority_window->profile->id
                ];

                echo json_encode($data);
                return;
            }
        }else{
            if($this->has_available_user($branch_id, $service_id)){
                $all_users = $this->get_available_user($branch_id, $service_id);
                $windows_data = [];
                $chosen = null;

                foreach($all_users as $user){
                    if(!$user->window->is_priority){
                        array_push($windows_data, [
                            "count" => $this->get_number_active_transactions($user->window->id),
                            "window_id" => $user->window->id,
                            "profile_id" => $user->id
                        ]);
                    }
                        
                }
                
                if(count($windows_data)  > 1){
                    if($windows_data[0]["count"] > $windows_data[1]["count"]){
                        $chosen = Window::find($windows_data[1]["window_id"]);
                    }else{
                        $chosen = Window::find($windows_data[0]["window_id"]);
                    }
                }else if(count($windows_data) > 0){
                    $chosen = Window::find($windows_data[0]["window_id"]);
                }else{
                    $data = [
                        "status" => "0",
                        "message" => "There is no currently available service provider."
                    ];
    
                    echo json_encode($data);
                    return;
                }

                if($this->has_active_transaction($chosen->id)){
                    if(!$this->has_active_transaction($priority_window->id)){
                        $data = [
                            "status" => "1",
                            "window_id" => $priority_window->id,
                            "profile_id" => $priority_window->profile->id
                        ];
        
                        echo json_encode($data);
                        return;
                    }else{
                        $data = [
                            "status" => "1",
                            "window_id" => $chosen->id,
                            "profile_id" => $chosen->profile->id
                        ];
        
                        echo json_encode($data);
                        return;
                    }
                }else{
                    $data = [
                        "status" => "1",
                        "window_id" => $chosen->id,
                        "profile_id" => $chosen->profile->id
                    ];
    
                    echo json_encode($data);
                    return;
                }


            }else{
                $data = [
                    "status" => "0",
                    "message" => "This service is still not available."
                ];

                echo json_encode($data);
                return;
            }

        }
    }

    
    

    public function has_available_user($branch_id, $service_id){
        $has_available_user = Profile::whereHas("services", function (Builder $query) use ($service_id){
            $query->where("services.id", "=", $service_id);
        })->where("branch_id", "=", $branch_id)->get()->all();

        $available_users = [];

        foreach($has_available_user as $user){
            if($user->window != null){
                array_push($available_users, $user);
            }
        }
        return count($available_users) > 0;
    }

    public function get_available_user($branch_id, $service_id){
        $has_available_user = Profile::whereHas("services", function (Builder $query) use ($service_id){
            $query->where("services.id", "=", $service_id);
        })->where("branch_id", "=", $branch_id)->get()->all();

        $available_users = [];

        foreach($has_available_user as $user){
            if($user->window != null){
                array_push($available_users, $user);
            }
        }

        return $available_users;
    }

    public function user_has_this_service($profile_id, $service_id){
        $profile = Profile::find($profile_id);
        return in_array($service_id, $profile->service_ids);
    }

    public function has_active_transaction($window_id){
        $transactions = Transaction::all()->where("window_id", "=", $window_id)->whereIn("state", ["waiting", "serving"])->all();
        return count($transactions) > 0;
    }

    public function get_number_active_transactions($window_id){
        $transactions = Transaction::all()->where("window_id", "=", $window_id)->whereIn("state", ["waiting", "serving"])->all();
        return count($transactions);
    }
}
