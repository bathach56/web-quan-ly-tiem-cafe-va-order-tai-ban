<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoffeeTable extends Model
{
    use HasFactory;

    // ĐÂY LÀ DÒNG QUAN TRỌNG NHẤT:
    protected $fillable = [
        'name',   // Tên bàn
        'area',   // Khu vực
        'status', // Trạng thái
    ];
}