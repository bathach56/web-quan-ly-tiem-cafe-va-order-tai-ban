@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4 animate__animated animate__fadeIn">
        <div>
            <h4 class="fw-bold text-warning mb-1"><i class="fa-solid fa-ticket-simple me-2"></i>QUẢN LÝ MÃ GIẢM GIÁ</h4>
            <p class="text-secondary small mb-0">Thiết lập chương trình khuyến mãi và thời hạn sử dụng tại HUTECH Coffee</p>
        </div>
        <button class="btn btn-primary rounded-pill px-4 shadow" data-bs-toggle="modal" data-bs-target="#addVoucherModal">
            <i class="fa fa-plus me-2"></i>Tạo Voucher Mới
        </button>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden animate__animated animate__fadeInUp" style="background: var(--card-bg);">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="color: var(--text-gray);">
                    <thead style="background: rgba(0,0,0,0.2);">
                        <tr class="text-uppercase small">
                            <th class="ps-4 py-3">Mã Code</th>
                            <th>Chương trình</th>
                            <th>Giá trị giảm</th>
                            <th>Lượt dùng</th>
                            <th>Hiệu lực</th>
                            <th>Trạng thái</th>
                            <th class="text-end pe-4">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($vouchers as $v)
                        <tr class="border-bottom border-secondary border-opacity-10">
                            <td class="ps-4">
                                <span class="badge bg-warning text-dark fw-800 px-3 py-2">{{ $v->code }}</span>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $v->name }}</div>
                                <div class="x-small text-muted" style="font-size: 0.75rem;">Đơn tối thiểu: {{ number_format($v->min_order_value) }}đ</div>
                            </td>
                            <td>
                                <div class="fw-bold text-success">
                                    {{ $v->type == 'percentage' ? '-'.$v->discount_value.'%' : '-'.number_format($v->discount_value).'đ' }}
                                </div>
                                <small class="opacity-50 small">{{ $v->type == 'percentage' ? 'Phần trăm' : 'Tiền mặt' }}</small>
                            </td>
                            <td>
                                <div class="small">
                                    {{ $v->used_count }} / {{ $v->limit_uses ?? '∞' }}
                                    <div class="progress mt-1" style="height: 4px; width: 80px;">
                                        @php
                                            $percent = $v->limit_uses ? ($v->used_count / $v->limit_uses) * 100 : 0;
                                            $barClass = $percent >= 100 ? 'bg-danger' : ($percent >= 80 ? 'bg-warning' : 'bg-info');
                                        @endphp
                                        <div class="progress-bar {{ $barClass }}" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small text-info"><i class="fa-regular fa-calendar-check me-1"></i> {{ $v->start_date ? $v->start_date->format('d/m/Y H:i') : 'Ngay lập tức' }}</div>
                                <div class="small {{ $v->end_date && $v->end_date->isPast() ? 'text-danger' : 'text-muted' }}">
                                    <i class="fa-regular fa-calendar-xmark me-1"></i> {{ $v->end_date ? $v->end_date->format('d/m/Y H:i') : 'Vô hạn' }}
                                </div>
                            </td>
                            <td>
                                @if($v->end_date && $v->end_date->isPast())
                                    <span class="badge rounded-pill bg-danger">Đã hết hạn</span>
                                @elseif($v->start_date && $v->start_date->isFuture())
                                    <span class="badge rounded-pill bg-info">Sắp diễn ra</span>
                                @else
                                    <span class="badge rounded-pill {{ $v->status == 'active' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $v->status == 'active' ? 'Đang chạy' : 'Tạm dừng' }}
                                    </span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info rounded-circle me-2" onclick="showQR('{{ $v->code }}')" title="Xem mã QR">
                                        <i class="fa fa-qrcode"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-warning rounded-circle me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $v->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <form action="{{ route('vouchers.destroy', $v->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Xóa voucher này?')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- MODAL CHỈNH SỬA (FIX LỖI HIỂN THỊ THỜI GIAN) -->
                        <div class="modal fade" id="editModal{{ $v->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="background: var(--card-bg);">
                                    <form action="{{ route('vouchers.update', $v->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <div class="modal-header border-secondary">
                                            <h5 class="modal-title fw-bold text-warning">SỬA VOUCHER: {{ $v->code }}</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <div class="row g-3">
                                                <div class="col-12 mb-3">
                                                    <label class="small fw-bold mb-2">Tên chương trình</label>
                                                    <input type="text" name="name" class="form-control bg-dark border-secondary text-white" value="{{ $v->name }}" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="small fw-bold mb-2">Trạng thái</label>
                                                    <select name="status" class="form-select bg-dark border-secondary text-white">
                                                        <option value="active" {{ $v->status == 'active' ? 'selected' : '' }}>Kích hoạt</option>
                                                        <option value="inactive" {{ $v->status == 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                                                    </select>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="small fw-bold mb-2">Lượt dùng tối đa</label>
                                                    <input type="number" name="limit_uses" class="form-control bg-dark border-secondary text-white" value="{{ $v->limit_uses }}">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="small fw-bold mb-2">Ngày bắt đầu</label>
                                                    <input type="datetime-local" name="start_date" class="form-control bg-dark border-secondary text-white" 
                                                           value="{{ $v->start_date ? $v->start_date->format('Y-m-d\TH:i') : '' }}">
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="small fw-bold mb-2">Ngày kết thúc</label>
                                                    <input type="datetime-local" name="end_date" class="form-control bg-dark border-secondary text-white" 
                                                           value="{{ $v->end_date ? $v->end_date->format('Y-m-d\TH:i') : '' }}">
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-warning w-100 fw-bold py-2 mt-3 shadow">LƯU THAY ĐỔI</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 opacity-25">
                                <i class="fa fa-ticket-alt fa-3x mb-3 text-secondary"></i>
                                <p>Chưa có mã giảm giá nào được tạo.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- MODAL THÊM MỚI (FIX LỖI NAME INPUT) -->
<div class="modal fade" id="addVoucherModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg" style="background: var(--card-bg);">
            <form action="{{ route('vouchers.store') }}" method="POST">
                @csrf
                <div class="modal-header border-secondary">
                    <h5 class="modal-title fw-bold text-primary">THIẾT LẬP VOUCHER MỚI</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="small fw-bold mb-2">Mã Giảm Giá (VD: SINHVIENHUTECH)</label>
                        <input type="text" name="code" class="form-control bg-dark border-secondary text-white py-2" style="text-transform: uppercase" placeholder="Nhập mã viết liền không dấu..." required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold mb-2">Tên Chương Trình</label>
                        <input type="text" name="name" class="form-control bg-dark border-secondary text-white py-2" placeholder="Ví dụ: Khai trương chi nhánh mới" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold mb-2">Loại Giảm</label>
                            <select name="type" class="form-select bg-dark border-secondary text-white">
                                <option value="fixed">Tiền mặt (VNĐ)</option>
                                <option value="percentage">Phần trăm (%)</option>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold mb-2">Giá Trị Giảm</label>
                            <input type="number" name="discount_value" class="form-control bg-dark border-secondary text-white" placeholder="VD: 20000 hoặc 10" required>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold mb-2">Đơn Tối Thiểu</label>
                            <input type="number" name="min_order_value" class="form-control bg-dark border-secondary text-white" value="0">
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold mb-2">Số Lượng Mã</label>
                            <input type="number" name="limit_uses" class="form-control bg-dark border-secondary text-white" placeholder="Để trống nếu vô hạn">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small fw-bold mb-2">Ngày Bắt Đầu</label>
                            <input type="datetime-local" name="start_date" class="form-control bg-dark border-secondary text-white">
                        </div>
                        <div class="col-6">
                            <label class="small fw-bold mb-2">Ngày Hết Hạn</label>
                            <input type="datetime-local" name="end_date" class="form-control bg-dark border-secondary text-white">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="submit" class="btn btn-primary w-100 fw-bold py-3 shadow-sm">LƯU VÀ PHÁT HÀNH VOUCHER</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL QR (WHITE THEME FOR SCANNING) -->
<div class="modal fade" id="qrModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content text-center p-4" style="background: white; border-radius: 20px;">
            <h6 class="fw-bold mb-3 text-dark">MÃ QR VOUCHER</h6>
            <div id="qrPlaceholder" class="mb-3 p-3 border rounded bg-light"></div>
            <p class="small fw-bold text-primary mb-1" id="qrText"></p>
            <p class="x-small text-muted mb-3">Đưa mã này cho nhân viên tại quầy</p>
            <button class="btn btn-dark w-100 rounded-pill" data-bs-dismiss="modal">Đóng</button>
        </div>
    </div>
</div>

<script>
    function showQR(code) {
        // Sử dụng QRServer API để tạo mã QR từ code
        let qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${code}`;
        document.getElementById('qrPlaceholder').innerHTML = `<img src="${qrUrl}" class="img-fluid animate__animated animate__zoomIn">`;
        document.getElementById('qrText').innerText = code;
        new bootstrap.Modal(document.getElementById('qrModal')).show();
    }
</script>
@endsection 