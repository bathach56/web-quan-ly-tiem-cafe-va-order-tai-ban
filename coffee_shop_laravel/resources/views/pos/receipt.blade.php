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
            width: 72mm; 
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
        
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print(); window.onafterprint = function(){ window.close(); }">

    <div class="text-center">
        <!-- Thông tin quán lấy từ Shop Settings -->
        <div class="shop-name">{{ $shop_setting->shop_name ?? 'HUTECH COFFEE' }}</div>
        <div style="font-size: 11px;">{{ $shop_setting->address ?? 'TP. Hồ Chí Minh' }}</div>
        <div style="font-size: 11px;">SĐT: {{ $shop_setting->phone ?? '0123.456.789' }}</div>
        
        <div class="divider"></div>
        <h3 style="margin: 10px 0; letter-spacing: 2px;">PHIẾU THANH TOÁN</h3>
    </div>

    <div style="font-size: 12px;">
        <div class="info-row">
            <!-- FIX LỖI: Kiểm tra nếu có bàn thì hiện tên bàn, không thì hiện MANG VỀ -->
            @if($order->table)
                <span>Bàn: <span class="bold text-uppercase">{{ $order->table->name }}</span></span> 
                <span>Khu vực: {{ $order->table->area ?? 'Tầng trệt' }}</span>
            @else
                <span class="bold" style="font-size: 14px;">MANG VỀ (TAKEAWAY)</span>
            @endif
        </div>
        <div class="info-row">
            <span>Số HĐ: #{{ $order->id }}</span> 
            <span>Thu ngân: {{ $order->user->name ?? 'Admin' }}</span>
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
                <th width="40%" class="text-right">T.Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderDetails as $item)
            <tr>
                <td style="text-transform: uppercase;">{{ $item->product->name }}</td>
                <td class="text-center">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->price * $item->quantity) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <div style="font-size: 13px;">
        <!-- Tổng tiền tạm tính -->
        <div class="info-row">
            <span>Tạm tính:</span>
            <span>{{ number_format($order->total_amount) }}đ</span>
        </div>

        <!-- Hiển thị giảm giá nếu có (Voucher/Giảm tay) -->
        @if($order->discount_amount > 0)
        <div class="info-row">
            <span>Giảm giá {{ $order->voucher_code ? '('.$order->voucher_code.')' : '' }}:</span>
            <span class="bold">-{{ number_format($order->discount_amount) }}đ</span>
        </div>
        @endif

        <!-- Số tiền thực tế khách phải trả -->
        <div class="info-row bold" style="font-size: 16px; margin-top: 5px; border-top: 1px solid #000; padding-top: 5px;">
            <span>TỔNG CỘNG:</span>
            <span>{{ number_format($order->final_amount) }}đ</span>
        </div>
    </div>
    
    <div style="font-size: 11px; margin-top: 8px;">
        <div class="info-row">
            <span>Hình thức thanh toán:</span>
            <span class="bold">
                @if($order->payment_method == 'cash') Tiền mặt
                @elseif($order->payment_method == 'card') Thẻ/POS
                @else Banking @endif
            </span>
        </div>
    </div>

    <div class="double-divider"></div>

    <div class="footer text-center">
        <p style="margin-bottom: 2px;">
            {{ $shop_setting->footer_text ?? 'Chào tạm biệt và hẹn gặp lại quý khách!!' }}
        </p>
    </div>

</body>
</html>