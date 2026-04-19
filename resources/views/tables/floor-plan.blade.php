@extends('layouts.admin')

@section('content')
<div class="container-fluid pb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold"><i class="fa-solid fa-map me-2"></i> Sơ Đồ Bàn</h3>
            <p class="text-secondary">Click vào bàn để xem trạng thái và chỉnh sửa</p>
        </div>
        <a href="{{ route('tables.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-list"></i> Danh sách bàn
        </a>
    </div>

    <div class="row">
        <!-- Khu vực Tầng trệt -->
        <div class="col-12 mb-5">
            <h5 class="mb-3 text-primary"><i class="fa-solid fa-layer-group"></i> Tầng Trệt</h5>
            <div class="floor-grid">
                @foreach($tables->where('area', 'Tầng trệt') as $table)
                    <div class="table-box {{ $table->status }}" onclick="editTable({{ $table->id }})">
                        <div class="table-number">{{ $table->name }}</div>
                        <div class="table-status">
                            @if($table->status == 'occupied')
                                <span class="badge bg-danger">Có khách</span>
                            @elseif($table->status == 'waiting')
                                <span class="badge bg-warning">Đang chờ</span>
                            @else
                                <span class="badge bg-success">Trống</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Khu vực Lầu 1 -->
        <div class="col-12 mb-5">
            <h5 class="mb-3 text-primary"><i class="fa-solid fa-layer-group"></i> Lầu 1</h5>
            <div class="floor-grid">
                @foreach($tables->where('area', 'Lầu 1') as $table)
                    <div class="table-box {{ $table->status }}" onclick="editTable({{ $table->id }})">
                        <div class="table-number">{{ $table->name }}</div>
                        <div class="table-status">
                            @if($table->status == 'occupied')
                                <span class="badge bg-danger">Có khách</span>
                            @elseif($table->status == 'waiting')
                                <span class="badge bg-warning">Đang chờ</span>
                            @else
                                <span class="badge bg-success">Trống</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Khu vực Sân vườn -->
        <div class="col-12">
            <h5 class="mb-3 text-primary"><i class="fa-solid fa-layer-group"></i> Sân Vườn</h5>
            <div class="floor-grid">
                @foreach($tables->where('area', 'Sân vườn') as $table)
                    <div class="table-box {{ $table->status }}" onclick="editTable({{ $table->id }})">
                        <div class="table-number">{{ $table->name }}</div>
                        <div class="table-status">
                            @if($table->status == 'occupied')
                                <span class="badge bg-danger">Có khách</span>
                            @elseif($table->status == 'waiting')
                                <span class="badge bg-warning">Đang chờ</span>
                            @else
                                <span class="badge bg-success">Trống</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Modal chỉnh sửa nhanh bàn -->
<div class="modal fade" id="editTableModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa bàn <span id="modalTableName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="modalTableId">
                <div class="mb-3">
                    <label class="form-label">Trạng thái bàn</label>
                    <select id="modalStatus" class="form-select">
                        <option value="empty">Trống</option>
                        <option value="occupied">Có khách</option>
                        <option value="waiting">Đang chờ</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary" onclick="saveTableStatus()">Lưu thay đổi</button>
            </div>
        </div>
    </div>
</div>

<style>
    .floor-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 16px;
    }
    .table-box {
        background: white;
        border-radius: 16px;
        padding: 20px 15px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        border: 2px solid #eee;
    }
    .table-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .table-box.occupied { border-color: #dc3545; background: #fff5f5; }
    .table-box.waiting   { border-color: #ffc107; background: #fffdf0; }

    .table-number {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 8px;
    }
</style>

<script>
function editTable(id) {
    // Lấy thông tin bàn (có thể cải tiến sau bằng AJAX)
    const tableBox = document.querySelector(`.table-box[onclick="editTable(${id})"]`);
    const tableName = tableBox.querySelector('.table-number').textContent;
    const isOccupied = tableBox.classList.contains('occupied');
    const isWaiting = tableBox.classList.contains('waiting');

    document.getElementById('modalTableId').value = id;
    document.getElementById('modalTableName').textContent = tableName;

    const statusSelect = document.getElementById('modalStatus');
    if (isOccupied) statusSelect.value = 'occupied';
    else if (isWaiting) statusSelect.value = 'waiting';
    else statusSelect.value = 'empty';

    new bootstrap.Modal(document.getElementById('editTableModal')).show();
}

function saveTableStatus() {
    const id = document.getElementById('modalTableId').value;
    const status = document.getElementById('modalStatus').value;

    // Gửi request cập nhật (sử dụng route update hiện có)
    fetch(`/tables/${id}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        alert('Cập nhật trạng thái bàn thành công!');
        location.reload();
    })
    .catch(() => alert('Có lỗi xảy ra!'));
}
</script>

@endsection