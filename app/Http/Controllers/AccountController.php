<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Account;
use App\Models\Branch;
use App\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\AccountsImport;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $accounts = null;
        $branches = null;

        if($user->is_admin){
            $accounts = Account::all();
            $branches = Branch::all();
        }else{
            $accounts = Account::all()->where("branch_id", "=", $user->profile->branch_id);
            $branches = Branch::all()->where("id", "=", $user->profile->branch_id);;
        }

        return view("admin.account.index", [
            "accounts" => $accounts,
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

    public function import(Request $request){
        $request->validate([
            "file" => "required|mimes:csv,txt"
        ]);

        $isSync = isset($request->all()["sync"]);
        $accountImport = new AccountsImport($isSync, $request->get("branch_id"));
        Excel::import($accountImport, $request->file('file')->store('temp'));
        $accountImportData = $accountImport->counts();
        
        return Redirect::back()->withErrors([
            'msg_from_imports' => 'Your feedback has been sent', 
            'updated' => $accountImportData["updated"],
            'inserted' => $accountImportData["inserted"],
            'denied' => $accountImportData["denied"]
        ]);
    }

    public function account_exists($account_number, $branch_id){
        $account = Account::all()->where("account_number", "=", $account_number)->where("branch_id", "=", $branch_id)->first();
        $data = [];

        if($account != null){
            $data = [
                "success" => 1,
                "data" => $account
            ];
        }else{
            $data = [
                "success" => 0,
                "message" => "Account number doesn't exists."
            ];
        }

        echo json_encode($data);
    }
}
