<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    // Khai báo các cột được phép lưu dữ liệu
    protected $fillable = [
        'table_id',
        'user_id',       // ID nhân viên thực hiện (nếu có)
        'total_amount',
        'status',         // pending, completed, cancelled
        'payment_method', // cash, card, banking
        'payment_status', // paid, unpaid
        'note',
        'order_date'
    ];

    /**
     * Một Đơn hàng sẽ có nhiều món ăn chi tiết (Order Details)
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
        // Lưu ý: Đảm bảo tên Model bàn của bạn là CoffeeTable hoặc Table cho khớp
        return $this->belongsTo(CoffeeTable::class, 'table_id');
    }
}