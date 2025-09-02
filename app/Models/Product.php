<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_name','type_id','image'];

    public function type()
    {
        return $this->belongsTo(Type::class);
    }
}
