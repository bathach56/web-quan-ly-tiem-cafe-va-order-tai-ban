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
     * Tự động chuyển đổi kiểu dữ liệu khi truy vấn.
     * Cực kỳ quan trọng để fix lỗi hiển thị sai thời gian của Thịnh.
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'discount_value' => 'double',
        'min_order_value' => 'double',
        'used_count' => 'integer',
        'limit_uses' => 'integer',
    ];

    // =============================================================
    // 1. LOGIC KIỂM TRA HIỆU LỰC (Dùng cho Máy POS & Khách đặt món)
    // =============================================================

    /**
     * Kiểm tra Voucher có hợp lệ để sử dụng ngay bây giờ không.
     */
    public function isValidNow()
    {
        $now = Carbon::now();

        // 1. Kiểm tra trạng thái hoạt động
        if ($this->status !== 'active') {
            return false;
        }

        // 2. Kiểm tra ngày bắt đầu (Nếu có)
        if ($this->start_date && $now->lt($this->start_date)) {
            return false;
        }

        // 3. Kiểm tra ngày hết hạn (Nếu có)
        if ($this->end_date && $now->gt($this->end_date)) {
            return false;
        }

        // 4. Kiểm tra giới hạn số lần sử dụng
        if (!is_null($this->limit_uses) && $this->used_count >= $this->limit_uses) {
            return false;
        }

        return true;
    }

    // =============================================================
    // 2. SCOPES (Truy vấn nhanh trong Controller)
    // =============================================================

    /**
     * Scope lấy danh sách Voucher đang còn hạn.
     * Cách dùng: Voucher::valid()->get();
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
    // 3. ACCESSORS (Định dạng hiển thị lên giao diện Blade)
    // =============================================================

    /**
     * Hiển thị giá trị giảm đẹp mắt (VD: 20% hoặc 50,000đ).
     * Cách dùng trong Blade: {{ $voucher->display_value }}
     */
    public function getDisplayValueAttribute()
    {
        if ($this->type === 'percentage') {
            return number_format($this->discount_value, 0) . '%';
        }
        return number_format($this->discount_value, 0, ',', '.') . 'đ';
    }

    /**
     * Hiển thị ngày hết hạn thân thiện.
     * Cách dùng trong Blade: {{ $voucher->expiry_status }}
     */
    public function getExpiryStatusAttribute()
    {
        if (!$this->end_date) {
            return 'Vô hạn';
        }
        
        if (Carbon::now()->gt($this->end_date)) {
            return 'Đã hết hạn';
        }

        return $this->end_date->format('d/m/Y H:i');
    }
}