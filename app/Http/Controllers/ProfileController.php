<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Profile;
use App\Models\Service;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $profiles = null;
        if($user->is_admin){
            $profiles = Profile::all();
        }else{
            $profiles = Profile::all()->where("branch_id", "=", $user->profile->branch->id);
        }

        return view("admin.profile.index", ["profiles" => $profiles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $branches = [];
        $roles = [];

        if(auth()->user()->is_admin){
            $branches =  Branch::all();
            $roles = Role::all();
        }else{
            $branches = [auth()->user()->profile->branch];
            $roles = Role::all()->where("branch_id", "=", $branches[0]->id)->all();
        }

        return view("admin.profile.create", [
            "branches" => $branches,
            "services" => Service::all(),
            "roles" => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userValidated = $request->validate([
            "username" => "unique:users|required",
            "password" => "required|string"
        ]);

        $profileValidated = $request->validate([
            "first_name" => "required|string",
            "middle_name" => "nullable|string",
            "last_name" => "required|string",
            "photo" => "nullable|image"
        ]);

        $userValidated["password"] = Hash::make($userValidated["password"]);
        $user = User::create($userValidated);
        $user->save();

        if(isset($profileValidated["photo"])){
            $path = Storage::putFile("public/images", $request->file("photo"));
            $path = Storage::url($path);
            $profileValidated["photo"] = $path;
        }
        
        $profileValidated["user_id"] = $user->id;
        $profileValidated["branch_id"] = $request->get("branch_id");
        $profileValidated["role_id"] = $request->get("role_id");
        $profile = Profile::create($profileValidated); 

        if(isset($request->only("services")["services"]))
            $profile->services()->attach($request->only("services")["services"]);
        
        $profile->save();
        return redirect()->route("profiles.show", ["profile" => $profile]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Profile $profile)
    {
        return view("admin.profile.show", [
            "profile" => $profile
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Profile $profile)
    {

        $branches = [];
        $roles = [];

        if(auth()->user()->is_admin){
            $branches =  Branch::all();
            $roles = Role::all();
        }else{
            $branches = [auth()->user()->profile->branch];
            $roles = Role::all()->where("branch_id", "=", $branches[0]->id)->all();
        }

        return view("admin.profile.edit", [
            "branches" => $branches,
            "services" => Service::all(),
            "roles" => $roles,
            "profile" => $profile
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Profile $profile)
    {
        // dd($request);
        $profileValidated = $request->validate([
            "first_name" => "required|string",
            "middle_name" => "nullable|string",
            "last_name" => "required|string",
            "photo" => "nullable|image"
        ]);

        
        
        if($request->get("password") != "*********"){
            $user = User::find($profile->user->id);
            $user->password = Hash::make($request->get("password"));
            $user->save();
            // dd("PUMASOK DITO" . $request->get("password"));
        }

        if(isset($profileValidated["photo"])){
            $path = Storage::putFile("public/images", $request->file("photo"));
            $path = Storage::url($path);
            $profileValidated["photo"] = $path;
        }
        
        $profileValidated["branch_id"] = $request->get("branch_id");
        $profileValidated["role_id"] = $request->get("role_id");

        $profile->fill($profileValidated);
        $profile->save();
        

        if(isset($request->only("services")["services"]))
            $profile->services()->sync($request->only("services")["services"]);
        else
            $profile->services()->sync([]);
        return redirect()->route("profiles.show", ["profile" => $profile]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Profile $profile)
    {
        $user = User::find($profile->user->id);
        $user->delete();
        $profile->delete();
        $profile->services()->detach();

        return redirect()->route("profiles.index", ["profiles" => Profile::all()]);
    }

    
}
