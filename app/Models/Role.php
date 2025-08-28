<?php

namespace App\Models;

use App\Traits\HasDynamicTable;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasDynamicTable; 
    protected $fillable = [
        'id',
        'role_name',
    ];

    /**
     * Get the users associated with the role.
     */
    
    public function branchUsers()
    {
        return $this->hasMany(User::class);
    }
}
