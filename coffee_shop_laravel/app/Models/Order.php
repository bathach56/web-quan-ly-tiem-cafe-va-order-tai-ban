<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Khai báo các cột khớp hoàn toàn với Database của Thịnh
    protected $fillable = [
        'table_id',
        'user_id',       // ID nhân viên (Foreign Key)
        'total_amount',
        'status',         // unconfirmed, pending, preparing, completed, cancelled
        'payment_method', // cash, card, banking
        'payment_status', // paid, unpaid
        'note',
        'order_date'
    ];

    /**
     * Một Đơn hàng sẽ có nhiều món ăn chi tiết (Order Details)
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /**
     * Alias để tương thích với các đoạn code gọi ->details
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /**
     * Một Đơn hàng thuộc về một Bàn cụ thể
     */
    public function table()
    {
        return $this->belongsTo(CoffeeTable::class, 'table_id');
    }

    /**
     * FIX LỖI: Đổi tên từ employee() sang user() để khớp với Controller
     * Một Đơn hàng được thực hiện bởi một Nhân viên (User)
     */
    public function user()
    {
        // Liên kết user_id của bảng orders với id của bảng users
        return $this->belongsTo(User::class, 'user_id');
    }
}