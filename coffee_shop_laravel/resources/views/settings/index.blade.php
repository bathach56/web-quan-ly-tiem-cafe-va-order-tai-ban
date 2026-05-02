@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-grey"><i class="fa-solid fa-gear me-2"></i> Cài đặt Hệ thống</h3>
        <p class="text-secondary small">Tùy chỉnh thông tin và cấu hình hoạt động của quán</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-4" style="color: var(--primary)"><i class="fa fa-info-circle me-2"></i> Thông tin cửa hàng</h5>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="small fw-bold mb-1">Tên quán (Hiển thị trên Bill & Menu)</label>
                            <input type="text" name="shop_name" class="form-control bg-dark text-white border-secondary" value="{{ $setting->shop_name }}">
                        </div>
                        <div class="col-md-5">
                            <label class="small fw-bold mb-1">Số điện thoại liên hệ</label>
                            <input type="text" name="phone" class="form-control bg-dark text-white border-secondary" value="{{ $setting->phone }}">
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold mb-1">Địa chỉ quán</label>
                            <input type="text" name="address" class="form-control bg-dark text-white border-secondary" value="{{ $setting->address }}">
                        </div>
                    </div>
                </div>

                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-4 text-success"><i class="fa fa-file-invoice-dollar me-2"></i> Cấu hình Thanh toán & Hóa đơn</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold mb-1">Thuế VAT áp dụng (%)</label>
                            <input type="number" name="vat" class="form-control bg-dark text-white border-secondary" value="{{ $setting->vat }}">
                            <label class="small fw-bold mb-1">Hệ thống sẽ tự động cộng % này vào mỗi hóa đơn.</small>
                        </div>
                        <div class="col-md-8">
                            <label class="small fw-bold mb-1">Lời chào cuối hóa đơn</label>
                            <textarea name="footer_text" rows="3" class="form-control bg-dark text-white border-secondary">{{ $setting->footer_text }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-custom p-4 text-center h-100">
                    <h5 class="fw-bold mb-4 text-warning text-start"><i class="fa fa-image me-2"></i> Logo Thương hiệu</h5>
                    
                    <div class="border border-secondary border-dashed rounded p-5 mb-3 position-relative" style="border-style: dashed !important; cursor: pointer;">
                        <input type="file" name="logo" class="position-absolute top-0 start-0 opacity-0 w-100 h-100" style="cursor: pointer;" onchange="previewImage(this)">
                        <img id="logoPreview" src="{{ asset('img/'.$setting->logo) }}" class="img-fluid mb-3" style="max-height: 150px;">
                        <div class="text-secondary small">
                            <i class="fa fa-cloud-upload-alt fa-2x mb-2"></i><br>
                            Bấm vào đây để tải logo lên<br>
                            <span style="font-size: 10px;">Định dạng: JPG, PNG (Max 2MB)</span>
                        </div>
                    </div>
                    
                    <p class=" text-start" style="font-size: 11px;">Logo này sẽ xuất hiện trên màn hình quét QR của khách hàng và trên Hóa đơn in ra.</p>
                    
                    <div class="mt-auto pt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold py-2"><i class="fa fa-save me-2"></i> LƯU CẤU HÌNH</button>
                        <button type="reset" class="btn btn-outline-secondary px-3">HỦY BỎ</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection