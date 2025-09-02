<?php
// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'dealer_id',
        'customer_name',
        'product_id',
        'production_step',
        'price',
        'quantity',
        'shade_number',
        'color',
        'delivery_time',
        'status'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'delivery_time' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'production_step' => 'array',
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'id');
    }
    public function dealer()
    {
        return $this->belongsTo(Dealer::class, 'dealer_id','id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id','id');
    }

    public function Orderstep()
    {
        return $this->hasMany(OrderStep::class, 'o_id', 'id');
    }
}