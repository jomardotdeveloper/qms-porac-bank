<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "product_key"
    ];

    public function roles() {
        return $this->hasMany(Role::class, "branch_id");
    }

    public function profiles() {
        return $this->hasMany(Profile::class, "branch_id");
    }

    public function accounts() {
        return $this->hasMany(Account::class, "branch_id");
    }

    public function windows() {
        return $this->hasMany(Window::class, "branch_id");
    }

    public function cutoff() {
        return $this->hasOne(Cutoff::class);
    }

    public function setting() {
        return $this->hasOne(Setting::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, "branch_id");
    }

    
    
}
