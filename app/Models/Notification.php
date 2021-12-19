<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        "account_id",
        "message",
        "transaction_id",
        "branch_id",
        "is_push"
    ];


    public function transaction() {
        return $this->belongsTo(Transaction::class, "transaction_id");
    }

    public function account() {
        return $this->belongsTo(Account::class, "account_id");
    }

    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }
    
    

    
}
