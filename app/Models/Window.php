<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'order',
        'branch_id',
        'is_priority'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }

    public function profile() {
        return $this->belongsTo(Profile::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class, "window_id");
    }
}
