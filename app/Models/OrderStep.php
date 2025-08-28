<?php
// app/Models/OrderStep.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStep extends Model
{
    use HasFactory;

    protected $table = 'order_steps';

    protected $fillable = [
        'date',
        'o_id',
        'd_id',
        'step_order',
        'status',
        'note'
    ];

    protected $casts = [
        'date' => 'date',
        'step_order' => 'integer'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_PROGRESS = 'progress';

    /**
     * Get the order that owns the step
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'o_id');
    }

    /**
     * Scope a query to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by order ID
     */
    public function scopeOrder($query, $orderId)
    {
        return $query->where('o_id', $orderId);
    }

    /**
     * Scope a query to filter by dealer ID
     */
    public function scopeDealer($query, $dealerId)
    {
        return $query->where('d_id', $dealerId);
    }

    /**
     * Check if step is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Check if step is in progress
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_PROGRESS;
    }
}