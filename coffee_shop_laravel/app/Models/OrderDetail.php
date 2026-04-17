<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price'
    ];

    /**
     * Chi tiết món này thuộc về Sản phẩm nào
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Chi tiết món này thuộc về Đơn hàng nào
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}