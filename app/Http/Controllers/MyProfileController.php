<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
class MyProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [];
        
        if(auth()->user()->is_admin){
            $data["branches"] = Branch::all();
            
        }else{
            $data["users"] = Profile::all()->
                where("branch_id", "=", auth()->user()->profile->branch->id)->
                where("id", "!=", auth()->user()->profile->id);
            $data["branch_data"] =  $this->getBranchData();

            if(auth()->user()->profile->window != null)
                $data["window_data"] = $this->getWindowData();
        }

        return view("admin.my-profile.index", $data);
        
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
        return view("admin.my-profile.edit");
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
        $vals = $request->all();

        if(isset($vals["is_profile"])){
            $profile = Profile::findOrFail($id);
            $request->validate([
                "photo" => "nullable|image"
            ]);
            
            if(isset($vals["photo"])){
                $path = Storage::putFile("public/images", $request->file("photo"));
                $path = Storage::url($path);
                $vals["photo"] = $path;
            }

            $profile->fill($vals);
            $profile->save();   
        }else{
            $profile = Profile::findOrFail($id);
            $user = User::find($profile->user->id);

            if(!password_verify($vals["old_password"], $user->password)){
                return back()->withErrors([
                    "password-error" => "The old password is invalid."
                ]);
            }else if($vals["new_password"] != $vals["confirm_password"]){
                return back()->withErrors([
                    "password-error" => "The password confirmation does not match."
                ]);
            }else{
                $user->password = Hash::make($vals["new_password"]);
                $user->save(); 
            }
            
        }

        return redirect()->route("my_profile.index");
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

    public function getTop5(){
        //GETTING THE TOP BRANCHES
        $branches = Branch::all();
        $top_5 = [];

        
    }

    public function getBranchData(){
        $branch = auth()->user()->profile->branch;
        $total = count($branch->transactions);
        $success = count($branch->getSuccessfulTransactionsAttribute());
        $drop = count($branch->getDropTransactionsAttribute());
        $unsettled = count($branch->getUnsettledTransactionsAttribute());

        if($success == 0){
            $success_vals = [
                "value" => 0,
                "percentage" => 0
            ];
        }else{
            $success_vals = ["value" => $success, "percentage" => intval(($success/$total) * 100)];
        }

        if($drop == 0){
            $drop_vals = [
                "value" => 0,
                "percentage" => 0
            ];
        }else{
            $drop_vals = ["value" => $drop, "percentage" => intval(($drop/$total) * 100)];
        }

        if($unsettled == 0){
            $unsettled_vals = [
                "value" => 0,
                "percentage" => 0
            ];
        }else{
            $unsettled_vals = ["value" => $unsettled, "percentage" => intval(($unsettled/$total) * 100)];
        }

        $data = [
            "total" => $total,
            "success" => $success_vals,
            "drop" => $drop_vals,
            "unsettled" => $unsettled_vals
        ];

        
        return $data;
    }

    public function getWindowData(){
        $window = auth()->user()->profile->window;
        $success = count($window->getSuccessfulTransactionsAttribute());
        $drop = count($window->getDropTransactionsAttribute());
        $unsettled = count($window->getUnsettledTransactionsAttribute());

        $data = [
            "success" => $success,
            "drop" => $drop,
            "unsettled" => $unsettled
        ];
        return $data;
    }

    
}
