<?php

namespace App\Imports;

use App\Models\Account;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class AccountsImport implements ToCollection
{
    private $branch_id;
    private $is_sync;

    private $count_updated = 0;
    private $count_denied = 0;
    private $count_inserted = 0;

    public function __construct($is_sync = false, $branch_id) 
    {
        $this->is_sync = $is_sync;
        $this->branch_id = $branch_id;
    }

    public function counts(){
        return [
            "updated" => $this->count_updated,
            "inserted" => $this->count_inserted,
            "denied" => $this->count_denied
        ];
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) 
        {
            $account_exists = Account::all()->where("account_number", "=", $row[3])->first();
            if($account_exists){
                if($this->is_sync){
                    $account_exists->fill([
                        "first_name" => $row[0],
                        "last_name" => $row[1],
                        "middle_name" => $row[2],
                        "customer_type" => $row[4],
                        "branch_id" => $this->branch_id
                    ]);
                    $this->count_updated++;
                    $account_exists->save();
                }else{
                    $this->count_denied++;
                }
                
            }else{
                $account = Account::create([
                    "first_name" => $row[0],
                    "last_name" => $row[1],
                    "middle_name" => $row[2],
                    "account_number" => $row[3],
                    "customer_type" => $row[4],
                    "branch_id" => $this->branch_id
                ]);
                $this->count_inserted++;
                $account->save();
            }
        }
        
    }
}
