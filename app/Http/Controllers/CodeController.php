<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
class CodeController extends Controller
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

    public function createCode($number){
        $alphabets = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P",
            "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
        $numerics = ["0", "1", "2", "3", "4", "5", "6", "7", "8", "9"];
        $allCodes = (array)Code::all()->pluck("code");
        $code = "";

        while(true){
            for($i = 0; $i < 4; $i++){
                $flag = (rand(0, 1) == 0) ? true : false;
        
                if($flag){
                    $randomNumber = rand(0, count($alphabets) - 1);
                    $code .= $alphabets[$randomNumber];
                }else{
                    $randomNumber = rand(0, count($numerics) - 1);
                    $code .= $numerics[$randomNumber];
                }
            }
        
            if(in_array($code, $allCodes)){
                $code = "";
                continue;
            }else{
                break;
            }
        
        }

        $codeobs = Code::create([
            "code" => $code,
            "number" => $number
        ]);


        while(true){
            $success = $this->send_message($codeobs->number, "Your OTP is " . $codeobs->code);

            if(intval($success) == 0){
                break;
            }
        }

    }

    public function send_message($to, $message){
        if(strlen($to) == 10){
            $to = "0" . $to;
        }
        
        $client = new Client;
        $endpoint = 'https://www.itexmo.com/php_api/';

        $res = $client->post($endpoint . 'api.php',["form_params" => [
            '1' => $to,
            '2' => $message,
            '3' => "ST-SAIRA416151_ILJ5C",
            "passwd" => "%p5d)1]pq9"
        ]]);
        
        return $res->getBody()->getContents();
    }

    public function checker($number, $code){
        $codeobs = DB::table("codes")->orderBy("id", "DESC")->first();
        $data = [];
        

        if($code == $codeobs->code){
            $data["status"] = 1;
        }else{
            $data["status"] = 0;
        }


        return $data;
    }

}
