<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {

    protected $table = 'order_items';

    protected $fillable = [
        "order_id",
        "product_id",
        "quantity",
        "price",
        "total",
    ];

    public function order(){
        return $this->belongsTo( Order::class, 'order_id', 'id' );
    }

    public function product(){
        return $this->belongsTo( Product::class, 'product_id', 'id' );
    }

}
