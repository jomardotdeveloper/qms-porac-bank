<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Setting;
use App\Models\Server;
use App\Models\Window;
use App\Models\Cutoff;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.branch.index", ["branches" => Branch::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.branch.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "name" => "required",
            "product_key" => "unique:branches|required",
            "email" => "required"
        ]);

        $branch = Branch::create($validated);
        $branch->save();

        $setting1 = Setting::create(["branch_id" => $branch->id]);
        $setting1->save();

        $cutoff = Cutoff::create(["branch_id" => $branch->id]);
        $cutoff->save();

        $window1_1 = Window::create(["name" => "Window 1", "order" => 1, "branch_id" => $branch->id]);
        $window1_1->save();

        $window2_1 = Window::create(["name" => "Window 2", "order" => 2, "branch_id" => $branch->id]);
        $window2_1->save();

        $window3_1 = Window::create(["name" => "Window 3", "order" => 3, "branch_id" => $branch->id]);
        $window3_1->save();

        $server = Server::create([
            "name" => $branch->name . "'s" . " Server",
            "branch_id" => $branch->id
        ]);

        return redirect()->route("branches.show", ["branch" => $branch]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Branch $branch)
    {
        return view("admin.branch.show", ["branch" => $branch]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Branch $branch)
    {
        return view("admin.branch.edit", ["branch" => $branch]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Branch $branch)
    {
        $branch->fill($request->all());
        $branch->save();
        return redirect()->route("branches.show", ["branch" => $branch]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route("branches.index", ["branches" => Branch::all()]);
    }

    public function get_branch_data($product_key)
    {
        $branch = Branch::all()->where("product_key", "=", $product_key)->first();
        $data = [];

        if ($branch) {
            $data = [
                "success" => 1,
                "id" => $branch->id,
                "name" => $branch->name
            ];
        } else {
            $data = [
                "success" => 0,
                "message" => "Product key doesn't exists."
            ];
        }

        echo json_encode($data);
    }

    public function all()
    {
        echo json_encode(DB::table('branches')->orderBy('name', 'ASC')->get()->all());
    }
}
