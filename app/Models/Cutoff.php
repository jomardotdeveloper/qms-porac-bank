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

    public function getDayToday(){
        $day = date("w");


        switch ($day) {
            case 1:
                return ["Monday", $this->m];
                break;
            case 2:
                return ["Tuesday", $this->t];
                break;
            case 3:
                return ["Wednesday", $this->w];
                break;
            case 4:
                return ["Thursday", $this->th];
                break;
            case 5:
                return ["Friday", $this->f];
                break;
            case 6:
                return ["Saturday", $this->s];
                break;
            case 7:
                return [ "Sunday", $this->sd];
                break;
          }

    }
}
