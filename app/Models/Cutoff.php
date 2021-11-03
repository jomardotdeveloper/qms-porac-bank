<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cutoff extends Model
{
    use HasFactory;

    protected $fillable = [
        'm',
        't',
        'w',
        'th',
        'f',
        's',
        'sd',
        'is_cutoff',
        'branch_id'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
