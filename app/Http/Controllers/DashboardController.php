<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Branch;
use App\Models\Feedback;
use App\Models\Transaction;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use DateTime;
class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd();
        $user = auth()->user();
        $data = ["quotes" => Inspiring::quote()];
        
        if($user->is_admin){
            $data["period"] = $this->getNumberOfTransactionPerPeriod(true);
            $data["transactions"] = Transaction::all();
            $data["branches"] = Branch::all();
            $data["profiles"] = Profile::all();
            $data["accounts"] = Account::all();
            $data["feedbacks"] = Feedback::all();
            $data["recent_feedback"] = Feedback::orderByDesc("created_at")->first();
            $data["apex_data"] = $this->getApexData();
        }else{

            if(in_array("CA", $user->profile->role->getPermissionCodenamesAttribute()) && $user->profile->window != null){
                $data["queue_data"] = [
                    "priority" => count($this->getDayNowPriority()),
                    "regular" => count($this->getDayNowRegular())
                ];
                $data["apex_data"] = $this->getApexData($user->profile->branch->id, $user->profile->window->id);
            }else{
                $data["apex_data"] = $this->getApexData($user->profile->branch->id);
            }
            
            $data["period"] = $this->getNumberOfTransactionPerPeriod();
            $data["transactions"] = Transaction::all()->where("branch_id", "=", $user->profile->branch->id)->all();
            $data["profiles"] = Profile::all()->where("branch_id", "=", $user->profile->branch->id)->where("id", "!=", $user->profile->id)->all();
            $data["accounts"] = Account::all()->where("branch_id", "=", $user->profile->branch->id)->all();
        }


        return view("admin.dashboard.index", $data);
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
    public function getDayNowPriority(){
        $profile = auth()->user()->profile;
        $transactions = DB::table("transactions")->join("accounts", "transactions.account_id", "=", "accounts.id")->select("transactions.*", "accounts.*")->whereRaw("DATE(transactions.in) = CURDATE() AND transactions.state IN ('waiting', 'serving') AND transactions.branch_id = ? AND transactions.window_id = ? AND accounts.customer_type = 'priority'", [$profile->branch->id, $profile->window->id])->get()->all();
        return $transactions;
    }

    public function getDayNowRegular($is_admin = false){
        $profile = auth()->user()->profile;
        $transactions = DB::table("transactions")->join("accounts", "transactions.account_id", "=", "accounts.id")->select("transactions.*", "accounts.*")->whereRaw("DATE(transactions.in) = CURDATE() AND transactions.state IN ('waiting', 'serving') AND transactions.branch_id = ? AND transactions.window_id = ? AND accounts.customer_type = 'regular'", [$profile->branch->id, $profile->window->id])->get()->all();
        return $transactions;
    }
    // 2021-10-26
    public function getDayNow($is_admin = false){
        $transactions = null;
        
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE()")->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = CURDATE() AND branch_id = ?", [
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        
        return $transactions;
    }

    public function getDayPrev($is_admin = false){
        $transactions = null;

        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = ?", [
                date("Y-m-d", strtotime("-1 days"))
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("DATE(transactions.in) = ? AND branch_id = ?", [
                date("Y-m-d", strtotime("-1 days")),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        
        return $transactions;
    }

    public function getMonthNow($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ?", [
                date("m"),
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ? AND branch_id = ?", [
                date("m"),
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getMonthPrev($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ?", [
                date("m" ,strtotime("-1 months")),
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("MONTH(transactions.in) = ? AND YEAR(transactions.in) = ? AND branch_id = ?", [
                date("m", strtotime("-1 months")),
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getYearNow($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ?", [
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND branch_id = ?", [
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getYearPrev($is_admin = false){
        $transactions = null;
        if($is_admin){
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ?", [
                date("Y")
            ])->get()->all();
        }else{
            $transactions = DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND branch_id = ?", [
                date("Y"),
                auth()->user()->profile->branch->id
            ])->get()->all();
        }
        return $transactions;
    }

    public function getNumberOfTransactionPerPeriod($is_admin = false){
        $data = [];

        $day_now = count($this->getDayNow($is_admin));
        $day_prev = count($this->getDayPrev($is_admin));

        $month_now = count($this->getMonthNow($is_admin));
        $month_prev =count($this->getMonthPrev($is_admin));

        $year_now = count($this->getYearNow($is_admin));
        $year_prev = count($this->getYearPrev($is_admin));


        if($day_now == 0 && $day_prev == 0){
            $data["day"] = [
                "now" => 0,
                "prev" => 0,
                "percent" => 0,
                "is_decreased" => -1
            ];
        }else if($day_now == 0){
            $data["day"] = [
                "now" => 0,
                "prev" => $day_prev,
                "percent" => 100,
                "is_decreased" => 1
            ];
        }else if($day_prev == 0){
            $data["day"] = [
                "now" => $day_now,
                "prev" => 0,
                "percent" => 100,
                "is_decreased" => 0
            ];
        }else{
            if($day_prev > $day_now){
                $data["day"] = [
                    "now" => $day_now,
                    "prev" => $day_prev,
                    "percent" => ($day_now / $day_prev) * 100,
                    "is_decreased" => 1
                ];
            }else if($day_prev < $day_now){
                $data["day"] = [
                    "now" => $day_now,
                    "prev" => $day_prev,
                    "percent" => ($day_prev / $day_now) * 100,
                    "is_decreased" => 0
                ];
            }else{
                $data["day"] = [
                    "now" => $day_now,
                    "prev" => $day_prev,
                    "percent" => 0,
                    "is_decreased" => -1
                ];
            }
        }

        if($month_now == 0 && $month_prev == 0){
            $data["month"] = [
                "now" => 0,
                "prev" => 0,
                "percent" => 0,
                "is_decreased" => -1
            ];
        }else if($month_now == 0){
            $data["month"] = [
                "now" => 0,
                "prev" => $month_prev,
                "percent" => 100,
                "is_decreased" => 1
            ];
        }else if($month_prev == 0){
            $data["month"] = [
                "now" => $month_now,
                "prev" => 0,
                "percent" => 100,
                "is_decreased" => 0
            ];
        }else{
            if($month_prev > $month_now){
                $data["month"] = [
                    "now" => $month_now,
                    "prev" => $month_prev,
                    "percent" => ($month_now / $month_prev) * 100,
                    "is_decreased" => 1
                ];
            }else if($month_prev < $month_now){
                $data["month"] = [
                    "now" => $month_now,
                    "prev" => $month_prev,
                    "percent" => ($month_prev / $month_now) * 100,
                    "is_decreased" => 0
                ];
            }else{
                $data["month"] = [
                    "now" => $month_now,
                    "prev" => $month_prev,
                    "percent" => 0,
                    "is_decreased" => -1
                ];
            }
        }

        if($year_now == 0 && $year_prev == 0){
            $data["year"] = [
                "now" => 0,
                "prev" => 0,
                "percent" => 0,
                "is_decreased" => -1
            ];
        }else if($year_now == 0){
            $data["year"] = [
                "now" => 0,
                "prev" => $year_prev,
                "percent" => 100,
                "is_decreased" => 1
            ];
        }else if($year_prev == 0){
            $data["year"] = [
                "now" => $year_now,
                "prev" => 0,
                "percent" => 100,
                "is_decreased" => 0
            ];
        }else{
            if($year_prev > $year_now){
                $data["year"] = [
                    "now" => $year_now,
                    "prev" => $year_prev,
                    "percent" => ($year_now / $year_prev) * 100,
                    "is_decreased" => 1
                ];
            }else if($year_prev < $year_now){
                $data["year"] = [
                    "now" => $year_now,
                    "prev" => $year_prev,
                    "percent" => ($year_prev / $year_now) * 100,
                    "is_decreased" => 0
                ];
            }else{
                $data["year"] = [
                    "now" => $year_now,
                    "prev" => $year_prev,
                    "percent" => 0,
                    "is_decreased" => -1
                ];
            }
        }
        
        return $data;
    }

    // public function getNumberOfTransactionPerPeriodWithWindow($window_id, $is_admin = false){
    //     $data = [];

    //     $day_now = count($this->getDayNow($is_admin));
    //     $day_prev = count($this->getDayPrev($is_admin));

    //     $month_now = count($this->getMonthNow($is_admin));
    //     $month_prev =count($this->getMonthPrev($is_admin));

    //     $year_now = count($this->getYearNow($is_admin));
    //     $year_prev = count($this->getYearPrev($is_admin));


    //     if($day_now == 0 && $day_prev == 0){
    //         $data["day"] = [
    //             "now" => 0,
    //             "prev" => 0,
    //             "percent" => 0,
    //             "is_decreased" => -1
    //         ];
    //     }else if($day_now == 0){
    //         $data["day"] = [
    //             "now" => 0,
    //             "prev" => $day_prev,
    //             "percent" => 100,
    //             "is_decreased" => 1
    //         ];
    //     }else if($day_prev == 0){
    //         $data["day"] = [
    //             "now" => $day_now,
    //             "prev" => 0,
    //             "percent" => 100,
    //             "is_decreased" => 0
    //         ];
    //     }else{
    //         if($day_prev > $day_now){
    //             $data["day"] = [
    //                 "now" => $day_now,
    //                 "prev" => $day_prev,
    //                 "percent" => ($day_now / $day_prev) * 100,
    //                 "is_decreased" => 1
    //             ];
    //         }else if($day_prev < $day_now){
    //             $data["day"] = [
    //                 "now" => $day_now,
    //                 "prev" => $day_prev,
    //                 "percent" => ($day_prev / $day_now) * 100,
    //                 "is_decreased" => 0
    //             ];
    //         }else{
    //             $data["day"] = [
    //                 "now" => $day_now,
    //                 "prev" => $day_prev,
    //                 "percent" => 0,
    //                 "is_decreased" => -1
    //             ];
    //         }
    //     }

    //     if($month_now == 0 && $month_prev == 0){
    //         $data["month"] = [
    //             "now" => 0,
    //             "prev" => 0,
    //             "percent" => 0,
    //             "is_decreased" => -1
    //         ];
    //     }else if($month_now == 0){
    //         $data["month"] = [
    //             "now" => 0,
    //             "prev" => $month_prev,
    //             "percent" => 100,
    //             "is_decreased" => 1
    //         ];
    //     }else if($month_prev == 0){
    //         $data["month"] = [
    //             "now" => $month_now,
    //             "prev" => 0,
    //             "percent" => 100,
    //             "is_decreased" => 0
    //         ];
    //     }else{
    //         if($month_prev > $month_now){
    //             $data["month"] = [
    //                 "now" => $month_now,
    //                 "prev" => $month_prev,
    //                 "percent" => ($month_now / $month_prev) * 100,
    //                 "is_decreased" => 1
    //             ];
    //         }else if($month_prev < $month_now){
    //             $data["month"] = [
    //                 "now" => $month_now,
    //                 "prev" => $month_prev,
    //                 "percent" => ($month_prev / $month_now) * 100,
    //                 "is_decreased" => 0
    //             ];
    //         }else{
    //             $data["month"] = [
    //                 "now" => $month_now,
    //                 "prev" => $month_prev,
    //                 "percent" => 0,
    //                 "is_decreased" => -1
    //             ];
    //         }
    //     }

    //     if($year_now == 0 && $year_prev == 0){
    //         $data["year"] = [
    //             "now" => 0,
    //             "prev" => 0,
    //             "percent" => 0,
    //             "is_decreased" => -1
    //         ];
    //     }else if($year_now == 0){
    //         $data["year"] = [
    //             "now" => 0,
    //             "prev" => $year_prev,
    //             "percent" => 100,
    //             "is_decreased" => 1
    //         ];
    //     }else if($year_prev == 0){
    //         $data["year"] = [
    //             "now" => $year_now,
    //             "prev" => 0,
    //             "percent" => 100,
    //             "is_decreased" => 0
    //         ];
    //     }else{
    //         if($year_prev > $year_now){
    //             $data["year"] = [
    //                 "now" => $year_now,
    //                 "prev" => $year_prev,
    //                 "percent" => ($year_now / $year_prev) * 100,
    //                 "is_decreased" => 1
    //             ];
    //         }else if($year_prev < $year_now){
    //             $data["year"] = [
    //                 "now" => $year_now,
    //                 "prev" => $year_prev,
    //                 "percent" => ($year_prev / $year_now) * 100,
    //                 "is_decreased" => 0
    //             ];
    //         }else{
    //             $data["year"] = [
    //                 "now" => $year_now,
    //                 "prev" => $year_prev,
    //                 "percent" => 0,
    //                 "is_decreased" => -1
    //             ];
    //         }
    //     }
        
    //     return $data;
    // }

    public function getApexData($branch_id = null, $window_id = null){
        return [
            "total_success" => $this->getTotalSuccess($branch_id, $window_id),
            "total_drop" => $this->getTotalDrop($branch_id, $window_id),
            "total_un" => $this->getTotalUn($branch_id, $window_id),
            "total_overall" => $this->getTotalOverall($branch_id, $window_id)
        ];
    }

    public function get_monthly($year, $branch_id = null, $window_id = null){
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $data = [];
        foreach($months as $month){

            if($window_id != null){
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ? AND window_id = ?",[$year, $month, $window_id])->get()->all());
            }else if($branch_id != null){
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ? AND branch_id = ?",[$year, $month, $branch_id])->get()->all());
            }else{
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ?",[$year, $month])->get()->all());
            }
            
        }

        return $data;
    }

    public function get_quarterly($year, $branch_id = null, $window_id = null){
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $data = [];
        foreach($months as $month){

            if($window_id != null){
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ? AND window_id = ?",[$year, $month, $window_id])->get()->all());
            }else if($branch_id != null){
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ? AND branch_id = ?",[$year, $month, $branch_id])->get()->all());
            }else{
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ?",[$year, $month])->get()->all());
            }
            
        }

        return [
            "1" => $data[1] + $data[2] + $data[3],
            "2" =>  $data[4] + $data[5] + $data[6],
            "3" => $data[7] + $data[8] + $data[9] ,
            "4" => $data[10] + $data[11] + $data[12]
        ];
    }

    public function get_half($year, $branch_id = null, $window_id = null){
        $months = [1,2,3,4,5,6,7,8,9,10,11,12];
        $data = [];
        foreach($months as $month){

            if($window_id != null){
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ? AND window_id = ?",[$year, $month, $window_id])->get()->all());
            }else if($branch_id != null){
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ? AND branch_id = ?",[$year, $month, $branch_id])->get()->all());
            }else{
                $data[$month] = count(DB::table("transactions")->whereRaw("YEAR(transactions.in) = ? AND MONTH(transactions.in) = ?",[$year, $month])->get()->all());
            }
            
        }


        return [
            "1" => $data[1] + $data[2] +  $data[3] + $data[4] +  $data[5] + $data[6],
            "2" => $data[7] + $data[8] +  $data[9] + $data[10] +  $data[11] + $data[12]
        ];
    }

    public function getTotalSuccess($branch_id = null, $window_id=null){
        $data = [];

        if($window_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state = 'out' AND window_id=?",[$window_id])->get()->all());
        }else if($branch_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state = 'out' AND branch_id=?",[$branch_id])->get()->all());
        }else{
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state = 'out'")->get()->all());
        }

        return $data;
    }

    public function getTotalDrop($branch_id = null, $window_id = null){
        $data = [];

        if($window_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state = 'drop' AND window_id=?",[$window_id])->get()->all());
        }else if($branch_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state = 'drop' AND branch_id=?",[$branch_id])->get()->all());
        }else{
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state = 'drop'")->get()->all());
        }

        return $data;
    }

    public function getTotalUn($branch_id = null, $window_id=null){
        $data = [];

        if($window_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state IN ('waiting', 'serving') AND window_id=?",[$window_id])->get()->all());
        }else if($branch_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state IN ('waiting', 'serving') AND branch_id=?",[$branch_id])->get()->all());
        }else{
            $data["count"] = count(DB::table("transactions")->whereRaw("transactions.state IN ('waiting', 'serving')")->get()->all());
        }

        return $data;
    }

    public function getTotalOverall($branch_id = null, $window_id = null){
        $data = [];

        if($window_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("window_id=?",[$window_id])->get()->all());
        }else if($branch_id != null){
            $data["count"] = count(DB::table("transactions")->whereRaw("branch_id=?",[$branch_id])->get()->all());
        }else{
            $data["count"] = count(Transaction::all());
        }

        return $data;
    }

}
