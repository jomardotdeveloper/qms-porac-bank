<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use App\Models\Profile;
use App\Models\Branch;
use App\Models\Transaction;
use DateTime;

class PerformanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $tellers = [];

        if ($user->is_admin) {
            $all = Profile::all();

            foreach ($all as $profile) {

                if (!$profile->user->is_admin) {
                    $access_rights = $profile->role->getPermissionCodenamesAttribute();

                    if (in_array("CA", $access_rights)) {
                        array_push($tellers, $profile);
                    }
                }
            }
        } else {
            $all = Profile::all()->where("branch_id", "=", $user->profile->branch->id)->all();

            foreach ($all as $profile) {
                $access_rights = $profile->role->getPermissionCodenamesAttribute();

                if (in_array("CA", $access_rights)) {
                    array_push($tellers, $profile);
                }
            }
        }


        return view("admin.performance.index", [
            "tellers" => $tellers,
            "branches" => Branch::all()
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

    public function getTellerByBranch($branch_id)
    {
        $all = Profile::all()->where("branch_id", "=", $branch_id)->all();
        $tellers = [];

        foreach ($all as $profile) {
            $access_rights = $profile->role->getPermissionCodenamesAttribute();

            if (in_array("CA", $access_rights)) {
                array_push($tellers, $profile);
            }
        }

        return $tellers;
    }


    public function export(Request $request)
    {
        $branch_id = 0;
        $data = [
            "from" => date_format(date_create($request->get("from")), "F d, Y"),
            "to" => date_format(date_create($request->get("to")), "F d, Y")
        ];
        $pdf_obj = App::make('dompdf.wrapper');
        $date_from = $request->get("from");
        $date_to =  $request->get("to");

        if ($request->get("from") > $request->get("to")) {
            return back()->withErrors([
                "date-error" => "Date from must not be greater than date to."
            ]);
        }


        if (intval($request->get("teller_id")) != 0) {
            $data["tellers"] = [
                [
                    "profile" => Profile::find(intval($request->get("teller_id"))),
                    "transactions" =>  Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ?", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all(),
                    "service_data" => [
                        "1" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 1 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 1 AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 1 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                        ],
                        "2" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 2 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 2 AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 2 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                        ],
                        "3" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 3 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 3 AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 3 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                        ],
                        "4" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 4 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 4 AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 4 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                        ],
                        "5" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 5 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 5 AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 5 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                        ],
                        "6" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 6 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 6 AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 6 AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                        ]
                    ],
                    "total_data" => [
                        "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                        "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND state = 'drop'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()),
                        "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND state = 'out'", [$request->get("from"), $request->get("to"), $request->get("teller_id")])->get()->all()))
                    ]
                ]
            ];
        }

        if (auth()->user()->is_admin) {
            $branch_id = $request->get("branch_id");
        } else {
            $branch_id = auth()->user()->profile->branch->id;
        }

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);

        if ($request->get("pdf") != null) {

            if (intval($request->get("teller_id")) == 0) {
                $data["tellers"] = [];
                $allProfiles = Profile::where("branch_id", "=", $branch_id)->get()->all();
                foreach ($allProfiles as $prof) {
                    $access_rights = $prof->role->getPermissionCodenamesAttribute();
                    if (in_array("CA", $access_rights)) {
                        array_push($data["tellers"], [
                            "profile" => $prof,
                            "transactions" => Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ?", [$request->get("from"), $request->get("to"), $prof->id])->get()->all(),
                            "service_data" => [
                                "1" => [
                                    "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 1 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 1 AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 1 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                                ],
                                "2" => [
                                    "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 2 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 2 AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 2 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                                ],
                                "3" => [
                                    "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 3 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 3 AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 3 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                                ],
                                "4" => [
                                    "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 4 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 4 AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 4 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                                ],
                                "5" => [
                                    "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 5 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 5 AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 5 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                                ],
                                "6" => [
                                    "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 6 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 6 AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                    "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND service_id = 6 AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                                ]
                            ],
                            "total_data" => [
                                "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND state = 'drop'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()),
                                "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) >= ? AND DATE(transactions.in) <= ? AND profile_id = ? AND state = 'out'", [$request->get("from"), $request->get("to"), $prof->id])->get()->all()))
                            ]
                        ]);
                    }
                }
                $pdf = $pdf_obj->loadView('admin.reports.performance', ["data" => $data]);
                return $pdf->download("Performance Reports($date_from - $date_to).pdf");
            }
        }

        $pdf = $pdf_obj->loadView('admin.reports.performance', ["data" => $data]);
        return $pdf->download("Performance Reports($date_from - $date_to).pdf");
    }

    public function getAverage($data)
    {
        $average = 0;
        $total = 0;

        if (count($data) < 1)
            return $average;

        foreach ($data as $d) {
            if (intval($d->servedtime) > 0)
                $total++;
            $average += intval($d->servedtime);
        }

        if ($total < 1)
            return 0;

        $average /= $total;

        return ceil($average);
    }

    public function formattedServingTime($servedtime)
    {
        $hour = gmdate("H", intval($servedtime));
        $min = gmdate("i", intval($servedtime));
        $sec = gmdate("s", intval($servedtime));
        $time = "";
        if (intval($hour) != 0) {
            $time .= strval($hour) . " h ";
        }

        if (intval($min) != 0) {
            $time .= strval($min) . " m ";
        }

        if (intval($sec) != 0) {
            $time .= strval($sec) . " s ";
        } else {
            return "0 s";
        }

        return $time;
    }

    public function publicDaily($branch_id, $date)
    {
        $data = [
            "from" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y"),
            "to" => date_format(DateTime::createFromFormat("Y-m-d", $date), "F d, Y")
        ];
        $pdf_obj = App::make('dompdf.wrapper');

        $data["branch"] = strtoupper(Branch::find($branch_id)->name);
        $data["tellers"] = [];
        $allProfiles = Profile::where("branch_id", "=", $branch_id)->get()->all();
        foreach ($allProfiles as $prof) {
            $access_rights = $prof->role->getPermissionCodenamesAttribute();
            if (in_array("CA", $access_rights)) {
                array_push($data["tellers"], [
                    "profile" => $prof,
                    "transactions" => Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ?", [$date, $prof->id])->get()->all(),
                    "service_data" => [
                        "1" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 1 AND state = 'out'", [$date, $prof->id])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 1 AND state = 'drop'", [$date, $prof->id])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 1 AND state = 'out'", [$date, $prof->id])->get()->all()))
                        ],
                        "2" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 2 AND state = 'out'", [$date, $prof->id])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 2 AND state = 'drop'", [$date, $prof->id])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 2 AND state = 'out'", [$date, $prof->id])->get()->all()))
                        ],
                        "3" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 3 AND state = 'out'", [$date, $prof->id])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 3 AND state = 'drop'", [$date, $prof->id])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 3 AND state = 'out'", [$date, $prof->id])->get()->all()))
                        ],
                        "4" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 4 AND state = 'out'", [$date, $prof->id])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 4 AND state = 'drop'", [$date, $prof->id])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 4 AND state = 'out'", [$date, $prof->id])->get()->all()))
                        ],
                        "5" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 5 AND state = 'out'", [$date, $prof->id])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 5 AND state = 'drop'", [$date, $prof->id])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 5 AND state = 'out'", [$date, $prof->id])->get()->all()))
                        ],
                        "6" => [
                            "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 6 AND state = 'out'", [$date, $prof->id])->get()->all()),
                            "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 6 AND state = 'drop'", [$date, $prof->id])->get()->all()),
                            "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND service_id = 6 AND state = 'out'", [$date, $prof->id])->get()->all()))
                        ]
                    ],
                    "total_data" => [
                        "success" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND state = 'out'", [$date, $prof->id])->get()->all()),
                        "drop" => count(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND state = 'drop'", [$date, $prof->id])->get()->all()),
                        "ave" => $this->formattedServingTime($this->getAverage(Transaction::with(["account",  "service"])->whereRaw("DATE(transactions.in) = ? AND profile_id = ? AND state = 'out'", [$date, $prof->id])->get()->all()))
                    ]
                ]);
            }
        }
        $pdf = $pdf_obj->loadView('admin.reports.performance', ["data" => $data]);
        return $pdf->download("Performance Daily Reports($date).pdf");
    }
}
