<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model {

    protected $table = 'orders';
    protected $fillable = [
        "customer_id",
    ];

    protected $attributes = [
        'status' => "active", // active, cancelled
    ];


    public function items(){
        return $this->hasMany( OrderItem::class, 'order_id', 'id' );
    }

    public function products(){
        return $this->belongsToMany( Product::class, 'order_items', 'order_id', 'product_id' )->withPivot('quantity','price','total');
    }

    public function customer(){
        return $this->belongsTo( Customer::class, 'customer_id', 'id' );
    }

}
