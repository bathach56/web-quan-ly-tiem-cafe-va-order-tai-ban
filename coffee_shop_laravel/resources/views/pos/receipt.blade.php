<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>In hóa đơn #{{ $order->id }}</title>
    <style>
        /* Thiết lập khổ giấy in nhiệt chuyên dụng 80mm */
        @page { size: 80mm auto; margin: 0; }
        body { 
            font-family: 'Courier New', Courier, monospace; 
            width: 72mm; /* Trừ lề an toàn để nội dung không bị mất khi in */
            margin: 0 auto; 
            padding: 10px 0; 
            color: #000; 
            font-size: 13px; 
            line-height: 1.4;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { border-top: 1px dashed #000; margin: 8px 0; }
        .double-divider { border-top: 2px double #000; margin: 8px 0; }
        table { width: 100%; border-collapse: collapse; }
        .shop-name { font-size: 18px; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; }
        .info-row { display: flex; justify-content: space-between; margin-bottom: 2px; }
        .footer { margin-top: 15px; font-size: 11px; font-style: italic; }
        
        /* Ẩn các thành phần không cần thiết khi nhấn Ctrl + P */
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">

    <div class="text-center">
        <div class="shop-name">{{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }}</div>
        <div style="font-size: 11px;">{{ $shop_setting->address ?? 'HUTECH - TP. Hồ Chí Minh' }}</div>
        <div style="font-size: 11px;">SĐT: {{ $shop_setting->phone ?? '0123.456.789' }}</div>
        
        <div class="divider"></div>
        <h3 style="margin: 10px 0; letter-spacing: 2px;">PHIẾU THANH TOÁN</h3>
    </div>

    <div style="font-size: 12px;">
        <div class="info-row">
            <span>Bàn: <span class="bold">{{ $order->table->name }}</span></span> 
            <span>Khu vực: {{ $order->table->area }}</span>
        </div>
        <div class="info-row">
            <span>Số HĐ: #{{ $order->id }}</span> 
            <span>Thu ngân: {{ Auth::user()->name }}</span>
        </div>
        <div class="info-row">
            <span>Thời gian:</span> 
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
    </div>

    <div class="divider"></div>

    <table style="font-size: 12px;">
        <thead>
            <tr style="text-align: left;">
                <th width="45%">Tên món</th>
                <th width="15%" class="text-center">SL</th>
                <th width="40%" class="text-right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->details as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div class="bold" style="font-size: 15px;">
        <div class="info-row">
            <span>TỔNG CỘNG:</span>
            <span>{{ number_format($order->total_amount) }}đ</span>
        </div>
    </div>
    
    <div style="font-size: 11px; margin-top: 5px;">
        <div class="info-row">
            <span>Hình thức thanh toán:</span>
            <span>
                @if($order->payment_method == 'cash') Tiền mặt
                @elseif($order->payment_method == 'card') Thẻ/POS
                @else Chuyển khoản @endif
            </span>
        </div>
    </div>

    <div class="double-divider"></div>

    <div class="footer text-center">
        <p style="margin-bottom: 2px;">
            {{ $shop_setting->footer_text ?? 'Cảm ơn Quý khách - Hẹn gặp lại!' }}
        </p>
    </div>

</body>
</html>