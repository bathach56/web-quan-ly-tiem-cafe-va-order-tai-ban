<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 
        'name', 
        'type', 
        'discount_value', 
        'min_order_value', 
        'limit_uses', 
        'used_count', 
        'start_date', 
        'end_date', 
        'status'
    ];

    /**
     * Tự động chuyển đổi kiểu dữ liệu ngày tháng về Carbon instance
     * Giúp Thịnh so sánh thời gian cực kỳ dễ dàng
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'double',
        'min_order_value' => 'double',
    ];

    // =============================================================
    // 1. LOGIC KIỂM TRA HIỆU LỰC (Dùng cho Máy POS)
    // =============================================================

    /**
     * Kiểm tra xem Voucher có thể sử dụng được tại thời điểm này không
     */
    public function isValidNow()
    {
        $now = Carbon::now();

        // Kiểm tra trạng thái kích hoạt
        if ($this->status !== 'active') {
            return false;
        }

        // Kiểm tra ngày bắt đầu (nếu có thiết lập)
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        // Kiểm tra ngày/giờ hết hạn (nếu có thiết lập)
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        // Kiểm tra giới hạn lượt dùng
        if (!is_null($this->limit_uses) && $this->used_count >= $this->limit_uses) {
            return false;
        }

        return true;
    }

    // =============================================================
    // 2. SCOPES (Dùng để truy vấn nhanh trong Controller)
    // =============================================================

    /**
     * Scope chỉ lấy các voucher còn hạn sử dụng
     * Cách dùng trong Controller: Voucher::valid()->get();
     */
    public function scopeValid($query)
    {
        $now = Carbon::now();

        return $query->where('status', 'active')
            ->where(function ($q) use ($now) {
                $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('end_date')->orWhere('end_date', '>=', $now);
            })
            ->where(function ($q) {
                $q->whereNull('limit_uses')->orWhereRaw('used_count < limit_uses');
            });
    }

    // =============================================================
    // 3. ACCESSORS (Định dạng hiển thị cho giao diện)
    // =============================================================

    /**
     * Trả về chuỗi hiển thị giá trị giảm (VD: 20,000đ hoặc 15%)
     */
    public function getDisplayValueAttribute()
    {
        if ($this->type === 'percentage') {
            return (int)$this->discount_value . '%';
        }
        return number_format($this->discount_value) . 'đ';
    }
}