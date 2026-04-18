<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <style>
        @page { size: 80mm 200mm; margin: 0; }
        body { font-family: 'Courier New', Courier, monospace; width: 80mm; margin: 0; padding: 10px; color: #000; font-size: 14px; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; }
        .footer { margin-top: 20px; font-size: 12px; }
    </style>
</head>
<body onload="window.print(); window.close();">
    <div class="text-center">
        <h2 style="margin-bottom: 5px;">{{ $shop_setting->shop_name ?? 'MIXI COFFEE' }}</h2>
        <p style="margin: 0;">{{ $shop_setting->address ?? 'HUTECH - TP. Hồ Chí Minh' }}</p>
        <p style="margin: 0;">SĐT: {{ $shop_setting->phone ?? '0123.456.789' }}</p>
        <h3 style="text-transform: uppercase; margin-top: 15px;">Phiếu Thanh Toán</h3>
    </div>

    <div style="margin-bottom: 5px;">
        <div>Bàn: <span class="bold">{{ $order->table->name }}</span></div>
        <div>Ngày: {{ $order->created_at->format('d/m/Y H:i') }}</div>
        <div>HĐ số: #{{ $order->id }}</div>
        <div>NV: {{ Auth::user()->name }}</div>
    </div>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th align="left">Món</th>
                <th align="right">SL</th>
                <th align="right">Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td align="right">{{ $item->quantity }}</td>
                <td align="right">{{ number_format($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="bold">
        <div style="display: flex; justify-content: space-between;">
            <span>TỔNG CỘNG:</span>
            <span>{{ number_format($order->total_amount) }}đ</span>
        </div>
    </div>

    <div class="footer text-center">
        <p>Cảm ơn Quý khách - Hẹn gặp lại!</p>
        <p style="font-style: italic;">Hệ thống POS bởi Phuc Thinh IT</p>
    </div>
</body>
</html>