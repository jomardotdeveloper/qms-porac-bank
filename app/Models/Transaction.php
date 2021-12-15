<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'order',
        'account_id',
        'state',
        'in',
        'out',
        'drop',
        'serve',
        'amount',
        'mobile_number',
        'is_notifiable',
        'window_id',
        'service_id',
        'branch_id',
        'profile_id',
        "is_sync"
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }

    public function account() {
        return $this->belongsTo(Account::class, "account_id");
    }

    public function window() {
        return $this->belongsTo(Window::class, "window_id");
    }

    public function notifications() {
        return $this->hasMany(Transaction::class, "transaction_id");
    }

    public function profile() {
        return $this->belongsTo(Profile::class, "profile_id");
    }

    public function service(){
        return $this->belongsTo(Service::class, "service_id");
    }

    public function bill(){
        return $this->belongsTo(Bill::class, "bill_id");
    }

    public function loan(){
        return $this->belongsTo(Loan::class, "loan_id");
    }

    public function getDateAttribute()
    {
        return $this->created_at->format('d.m.Y');
    }

}
