<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'code',
        'name',
        'unit',
        'stock',
        'min_stock',
    ];
}
