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
        $request->validate([
            'code'      => 'required|string|unique:ingredients,code',
            'name'      => 'required|string|max:255',
            'unit'      => 'required|string|max:50',
            'min_stock' => 'nullable|numeric|min:0',
        ]);

        Ingredient::create([
            'code'      => $request->code,
            'name'      => $request->name,
            'unit'      => $request->unit,
            'stock'     => 0,
            'min_stock' => $request->min_stock ?? 5,
        ]);
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