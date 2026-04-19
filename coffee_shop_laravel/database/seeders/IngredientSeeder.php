<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'code' => 'NL-001',
                'name' => 'Cà phê hạt pha máy (Arabica/Robusta)',
                'unit' => 'Kg',
                'stock' => 15,
                'min_stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-003',
                'name' => 'Đường cát trắng Biên Hòa',
                'unit' => 'Kg',
                'stock' => 50,
                'min_stock' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-005',
                'name' => 'Ly nhựa dập màng size M',
                'unit' => 'Cái',
                'stock' => 1550,
                'min_stock' => 100,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-002',
                'name' => 'Sữa tươi thanh trùng Đà Lạt Milk',
                'unit' => 'Hộp 1L',
                'stock' => 22,
                'min_stock' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 'NL-004',
                'name' => 'Syrup Caramel Monin',
                'unit' => 'Chai',
                'stock' => 6,
                'min_stock' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('ingredients')->insert($data);
    }
}