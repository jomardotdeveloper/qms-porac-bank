<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "code_name"
    ];

    public function users(){
        return $this->belongsToMany(User::class, 'profile_services', 'service_id', 'profile_id');
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, "service_id");
    }
}
