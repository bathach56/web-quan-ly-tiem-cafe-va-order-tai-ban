<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CoffeeTable;

class TableSeeder extends Seeder
{
    public function run(): void
    {
        $tables = [
            // Tầng trệt
            ['name' => 'Bàn 01', 'area' => 'Tầng trệt', 'status' => 'empty'],
            ['name' => 'Bàn 02', 'area' => 'Tầng trệt', 'status' => 'empty'],
            ['name' => 'Bàn 03', 'area' => 'Tầng trệt', 'status' => 'empty'],
            ['name' => 'Bàn 04', 'area' => 'Tầng trệt', 'status' => 'empty'],
            
            // Lầu 1
            ['name' => 'Bàn 05', 'area' => 'Lầu 1', 'status' => 'empty'],
            ['name' => 'Bàn 06', 'area' => 'Lầu 1', 'status' => 'empty'],
            ['name' => 'Bàn 07', 'area' => 'Lầu 1', 'status' => 'empty'],
            
            // Sân vườn
            ['name' => 'Bàn 08', 'area' => 'Sân vườn', 'status' => 'empty'],
            ['name' => 'Bàn 09', 'area' => 'Sân vườn', 'status' => 'empty'],
            ['name' => 'Bàn 10', 'area' => 'Sân vườn', 'status' => 'empty'],
        ];

        foreach ($tables as $table) {
            CoffeeTable::create($table);
        }
    }
}