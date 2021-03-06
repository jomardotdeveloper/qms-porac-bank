<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        "starting_point",
        "ending_point"
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
