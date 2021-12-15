<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        "first_name",
        "last_name",
        "middle_name",
        "account_number",
        "customer_type",
        "branch_id",
        "is_sync"
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, "account_id");
    }

    public function notifications() {
        return $this->hasMany(Notification::class, "account_id");
    }

    public function getFullNameAttribute() {
        if ($this->middle_name) {
            return "$this->first_name $this->middle_name $this->last_name";
        }
        return "$this->first_name $this->last_name";
    }

}
