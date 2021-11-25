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

    public function getSuccessfulTransactionsAttribute(){
        return $this->transactions->where("state", "=", "out")->all();
    }

    public function getDropTransactionsAttribute(){
        return $this->transactions->where("state", "=", "drop")->all();
    }

    public function getUnsettledTransactionsAttribute(){
        return $this->transactions->where("state", "!=", "drop")->where("state", "!=", "out")->all();
    }

}
