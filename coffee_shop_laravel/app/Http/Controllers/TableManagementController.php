<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\CoffeeTable;
use Illuminate\Support\Facades\DB;

class TableManagementController extends Controller
{
    // 1. CHUYỂN BÀN: Đưa toàn bộ đơn từ bàn A sang bàn B (trống)
    public function transferTable(Request $request)
    {
        $fromTableId = $request->from_table_id;
        $toTableId = $request->to_table_id;

        DB::beginTransaction();
        try {
            // Tìm đơn hàng đang hoạt động của bàn cũ
            $order = Order::where('table_id', $fromTableId)->where('status', '!=', 'completed')->first();
            
            if (!$order) return response()->json(['success' => false, 'message' => 'Bàn gốc không có khách!']);

            // 1. Cập nhật ID bàn trong đơn hàng
            $order->update(['table_id' => $toTableId]);

            // 2. Cập nhật trạng thái 2 bàn
            CoffeeTable::find($fromTableId)->update(['status' => 'available']);
            CoffeeTable::find($toTableId)->update(['status' => 'occupied']);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Chuyển bàn thành công!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // 2. GỘP BÀN: Gộp món từ bàn A vào bàn B đã có khách
    public function mergeTable(Request $request)
    {
        $fromTableId = $request->from_table_id;
        $toTableId = $request->to_table_id;

        DB::beginTransaction();
        try {
            $orderA = Order::where('table_id', $fromTableId)->where('status', '!=', 'completed')->first();
            $orderB = Order::where('table_id', $toTableId)->where('status', '!=', 'completed')->first();

            if (!$orderA || !$orderB) return response()->json(['success' => false, 'message' => 'Cần 2 bàn đều có khách để gộp!']);

            // Chuyển toàn bộ món từ đơn A sang đơn B
            OrderDetail::where('order_id', $orderA->id)->update(['order_id' => $orderB->id]);

            // Tính toán lại tổng tiền cho đơn B
            $newTotal = OrderDetail::where('order_id', $orderB->id)->sum(DB::raw('price * quantity'));
            $orderB->update(['total_amount' => $newTotal]);

            // Xóa đơn A và trả bàn A về trống
            $orderA->delete(); 
            CoffeeTable::find($fromTableId)->update(['status' => 'available']);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false]);
        }
    }

    // 3. TÁCH BÀN: Tách 1 vài món từ bàn A sang bàn B mới
    public function splitTable(Request $request)
    {
        // $items: Danh sách ID của order_details cần tách
        $itemsToMove = $request->order_detail_ids; 
        $toTableId = $request->to_table_id;

        DB::beginTransaction();
        try {
            $oldOrder = Order::whereHas('orderDetails', function($q) use ($itemsToMove) {
                $q->whereIn('id', $itemsToMove);
            })->first();

            // 1. Tạo đơn hàng mới cho bàn mới
            $newOrder = Order::create([
                'table_id' => $toTableId,
                'order_date' => now(),
                'status' => 'occupied',
                'total_amount' => 0,
                'employee_id' => auth()->id()
            ]);

            // 2. Chuyển các món được chọn sang đơn mới
            OrderDetail::whereIn('id', $itemsToMove)->update(['order_id' => $newOrder->id]);

            // 3. Tính lại tiền cho cả 2 đơn
            $this->recalculateTotal($oldOrder->id);
            $this->recalculateTotal($newOrder->id);

            CoffeeTable::find($toTableId)->update(['status' => 'occupied']);

            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false]);
        }
    }

    private function recalculateTotal($orderId) {
        $total = OrderDetail::where('order_id', $orderId)->sum(DB::raw('price * quantity'));
        Order::find($orderId)->update(['total_amount' => $total]);
    }
}