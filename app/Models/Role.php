<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    
    protected $fillable = [
        "name",
        "branch_id"
    ];

    public function branch() {
        return $this->belongsTo(Branch::class, "branch_id");
    }
    
    public function profiles() {
        return $this->hasMany(Profile::class, "role_id");
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
    }

    public function getPermissionIdsAttribute()
    {
        return $this->permissions->pluck('id')->all();
    }

    public function getPermissionCodenamesAttribute()
    {
        return $this->permissions->pluck('code_name')->all();
    }
}
