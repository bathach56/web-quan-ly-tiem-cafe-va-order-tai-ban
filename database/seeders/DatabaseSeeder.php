<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {

        $this->call([
        IngredientSeeder::class, // Đổ dữ liệu kho hàng
        TableSeeder::class,      // Đổ dữ liệu bàn (vừa tạo)
    ]);
    
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $catCoffee = Category::create(['name' => 'Cà Phê']);
        $catCake   = Category::create(['name' => 'Bánh Ngọt']);
        $catTea    = Category::create(['name' => 'Trà Trái Cây']);

        $products = [
            ['name' => 'Bạc Xỉu', 'category_id' => $catCoffee->id, 'price' => 30000, 'status' => 'active', 'image' => 'bac-xiu.jpg'],
            ['name' => 'Cà Phê Đen Đá', 'category_id' => $catCoffee->id, 'price' => 25000, 'status' => 'active', 'image' => 'ca-phe-den.jpg'],
            ['name' => 'Tiramisu', 'category_id' => $catCake->id, 'price' => 35000, 'status' => 'active', 'image' => 'tiramisu.jpg'],
            ['name' => 'Trà chanh', 'category_id' => $catTea->id, 'price' => 15000, 'status' => 'active', 'image' => 'tra-chanh.jpg'],
            ['name' => 'Trà Đào Cam Sả', 'category_id' => $catTea->id, 'price' => 45000, 'status' => 'active', 'image' => 'tra-dao.jpg'],
            ['name' => 'Trà Vải', 'category_id' => $catTea->id, 'price' => 40000, 'status' => 'active', 'image' => 'tra-vai.jpg'],
        ];

        foreach ($products as $item) {
            Product::create($item);
        }
    }
}