<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone_no',
        'salary',
        'doj',
        'dob',
        'ifsc_code',
        'account_holder_name',
        'account_no',
        'documents',
        'status'
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'doj' => 'date',
        'dob' => 'date',
        'documents' => 'array'
    ];

    protected $dates = [
        'doj',
        'dob',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    // Accessors
    public function getAgeAttribute()
    {
        return $this->dob->diffInYears(now());
    }

    public function getExperienceAttribute()
    {
        return $this->doj->diffInYears(now());
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeSalaryRange($query, $min, $max)
    {
        return $query->whereBetween('salary', [$min, $max]);
    }
}
