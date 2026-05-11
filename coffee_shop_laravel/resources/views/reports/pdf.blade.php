<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        /* Quan trọng: Phải dùng font hỗ trợ tiếng Việt */
        @font-face {
            font-family: 'DejaVu Sans';
            font-style: normal;
            font-weight: normal;
        }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #444; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .text-right { text-align: right; }
        .footer { margin-top: 30px; text-align: right; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h2 style="margin-bottom: 5px;">HUTECH COFFEE - BÁO CÁO DOANH THU</h2>
        <p>Thời gian: {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Mã Đơn</th>
                <th>Ngày đặt</th>
                <th>Bàn</th>
                <th>Thanh toán</th>
                <th>Tổng tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp
            @forelse($orders as $order)
                @php $grandTotal += $order->total_amount; @endphp
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>{{ date('d/m/Y H:i', strtotime($order->order_date)) }}</td>
                    <td>{{ $order->table->name ?? 'Mang về' }}</td>
                    <td>{{ strtoupper($order->payment_method) }}</td>
                    <td class="text-right">{{ number_format($order->total_amount) }}đ</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Không có dữ liệu đơn hàng trong khoảng thời gian này.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        TỔNG DOANH THU: {{ number_format($grandTotal) }} VNĐ
    </div>
</body>
</html>