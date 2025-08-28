<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcessingStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'department_id',
        'step_order',
    ];

    // Relationship: Each step belongs to one department
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
