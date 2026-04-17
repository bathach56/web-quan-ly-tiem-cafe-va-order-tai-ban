<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index() {
        $ingredients = Ingredient::all();
        return view('inventory.index', compact('ingredients'));
    }

    // Khai báo nguyên liệu mới
    public function store(Request $request) {
        Ingredient::create($request->all());
        return back()->with('success', 'Đã thêm nguyên liệu mới!');
    }

    // Xử lý Nhập/Xuất kho
    public function updateStock(Request $request) {
        $ingredient = Ingredient::find($request->id);
        if($request->type == 'import') {
            $ingredient->stock += $request->quantity;
        } else {
            if($ingredient->stock < $request->quantity) {
                return back()->with('error', 'Số lượng tồn kho không đủ!');
            }
            $ingredient->stock -= $request->quantity;
        }
        $ingredient->save();
        return back()->with('success', 'Cập nhật kho thành công!');
    }
}