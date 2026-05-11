<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /**
     * Khai báo các cột có thể nạp dữ liệu hàng loạt (Mass Assignment).
     * Đã bao gồm các cột để xử lý Voucher cho dự án HUTECH Coffee của Nhóm 3.
     */
    protected $fillable = [
        'table_id',
        'user_id',         // ID nhân viên thu ngân
        'total_amount',    // Tổng tiền gốc chưa giảm
        'discount_amount', // Số tiền được giảm
        'final_amount',    // Số tiền cuối cùng khách phải trả
        'voucher_code',    // Mã voucher đã áp dụng
        'status',          // unconfirmed, pending, preparing, completed, cancelled
        'payment_method',  // cash, card, banking
        'payment_status',  // paid, unpaid
        'note',
        'order_date'
    ];

    /**
     * Quan hệ: Một Đơn hàng có nhiều chi tiết món ăn.
     */
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /**
     * Alias 'details' để tương thích với logic gọi hàm trong PosController.
     */
    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    /**
     * Quan hệ: Một Đơn hàng thuộc về một Bàn cụ thể.
     */
    public function table()
    {
        return $this->belongsTo(CoffeeTable::class, 'table_id');
    }

    /**
     * Quan hệ: Một Đơn hàng được thực hiện/xử lý bởi một Nhân viên (User).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}