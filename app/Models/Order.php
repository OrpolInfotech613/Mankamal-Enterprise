<?php
// app/Models/MigrationOrder.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'dealer_name',
        'customer_name',
        'product_name',
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

}