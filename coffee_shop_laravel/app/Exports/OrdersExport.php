<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start, $end;

    public function __construct($start, $end) {
        $this->start = $start;
        $this->end = $end;
    }

    public function collection() {
        return Order::whereBetween('order_date', [$this->start . ' 00:00:00', $this->end . ' 23:59:59'])
                    ->where('payment_status', 'paid')
                    ->with('table')
                    ->get();
    }

    public function headings(): array {
        return ["Mã đơn", "Ngày đặt", "Bàn", "Phương thức", "Tổng tiền (VNĐ)"];
    }

    public function map($order): array {
        return [
            $order->id,
            $order->order_date,
            $order->table->name ?? 'Mang về',
            $order->payment_method,
            $order->total_amount,
        ];
    }
}