<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        "branch_id",
        "user_id",
        "role_id",
        "first_name",
        "middle_name",
        "last_name",
        "photo",
        "is_sync"
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }

    public function role() {
        return $this->belongsTo(Role::class, "role_id");
    }

    public function services(){
        return $this->belongsToMany(Service::class, 'profile_services', 'profile_id', 'service_id');
    }

    public function window() {
        return $this->hasOne(Window::class);
    }

    public function getFullNameAttribute() {
        if ($this->middle_name) {
            return "$this->first_name $this->middle_name $this->last_name";
        }
        return "$this->first_name $this->last_name";
    }

    public function getSuccessfulTransactionsAttribute(){
        return $this->transactions->where("state", "=", "out")->all();
    }

    public function getDropTransactionsAttribute(){
        return $this->transactions->where("state", "=", "drop")->all();
    }

    public function getServiceIdsAttribute()
    {
        return $this->services->pluck('id')->all();
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, "profile_id");
    }


}
