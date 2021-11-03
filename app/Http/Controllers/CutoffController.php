<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cutoff;
class CutoffController extends Controller
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
    public function update(Request $request, Cutoff $cutoff)
    {
        $cutoff->fill($request->all());
        $cutoff->save();

        return back()->withErrors([
            "success-cutoff" => "Your changes were successfully applied."
        ]);
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

    public function get_cutoff_data($branch_id){
        $cutoff = Cutoff::all()->where("branch_id", "=", $branch_id)->first();
        $data = [];
        
        if($cutoff){
            $data = [
                "success" => 1,
                "data" => $cutoff
            ];
        }else{
            $data = [
                "success" => 0,
                "message" => "Branch doesn't exists."
            ];
        }
        
        echo json_encode($data);
    }
}
