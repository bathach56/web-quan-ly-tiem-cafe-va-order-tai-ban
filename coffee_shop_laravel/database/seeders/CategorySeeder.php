<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    \DB::table('categories')->insert([
        ['name' => 'Cà Phê', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Trà Trái Cây', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Bánh Ngọt', 'created_at' => now(), 'updated_at' => now()],
    ]);
}
}
