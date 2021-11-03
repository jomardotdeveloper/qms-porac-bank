<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_automatic_sms',
        'sms_interval',
        'is_qrcode_automatic',
        'qrcode_interval',
        'branch_id'
    ];

    public function branch() {
        return $this->belongsTo(Branch::class);
    }
}
