<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Cho phép nạp dữ liệu vào các cột này
    protected $fillable = [
        'name', 
        'category_id', 
        'price', 
        'image', 
        'status'
    ];

    /**
     * Khai báo: Một sản phẩm THUỘC VỀ một danh mục
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}