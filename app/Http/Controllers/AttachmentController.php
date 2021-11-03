<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Models\Attachment;
use Stevebauman\Location\Facades\Location;

class AttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("admin.attachment.index", ["attachments" => Attachment::all()]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.attachment.create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $vals = $request->all();
        $path = Storage::putFile("public/attachment", $request->file("src"));
        $path = Storage::url($path);
        $vals["src"] = $path;
        $vals["file_name"] = $request->file("src")->getClientOriginalName();

        $attachment = Attachment::create($vals);
        $attachment->save();
        return redirect()->route("attachments.show", [
            "attachment" => $attachment
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Attachment $attachment)
    {
        return view("admin.attachment.show",[
            "attachment" => $attachment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Attachment $attachment)
    {
        return view("admin.attachment.edit",[
            "attachment" => $attachment
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attachment $attachment)
    {
        $vals = $request->all();
        
        if(isset($vals["src"])){
            $vals["file_name"] = $request->file("src")->getClientOriginalName();
            $path = Storage::putFile("public/attachment", $request->file("src"));
            $path = Storage::url($path);
            $vals["src"] = $path;
        }
        $attachment->fill($vals);
        $attachment->save();
        return redirect()->route("attachments.show", [
            "attachment" => $attachment
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attachment $attachment)
    {
        $attachment->delete();
        return redirect()->route("attachments.index", ["attachments" => Attachment::all()]);
    }

    public function download(Attachment $attachment){
        return response()->download(public_path($attachment->src), $attachment->file_name);
    }

    public function active(Attachment $attachment){
        if($attachment->is_active){
            $attachment->is_active = 0;
            $attachment->save();
        }else{
            $active_attachments = Attachment::all()->where("is_active", "=", true);
            foreach($active_attachments as $attc){
                $current = Attachment::find($attc["id"]);
                $current->is_active = 0;
                $current->save();
            }

            $attachment->is_active = 1;
            $attachment->save();
        }
        
        return redirect()->route("attachments.show", [
            "attachment" => $attachment
        ]);
    }

}
