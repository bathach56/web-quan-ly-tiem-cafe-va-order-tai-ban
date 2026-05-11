@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold text-grey"><i class="fa-solid fa-gear me-2"></i> {{ __('messages.system_settings') }}</h3>
        <p class="text-secondary small">{{ __('messages.system_settings_desc') }}</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-4" style="color: var(--primary)"><i class="fa fa-info-circle me-2"></i> {{ __('messages.store_info') }}</h5>
                    <div class="row g-3">
                        <div class="col-md-7">
                            <label class="small fw-bold mb-1">{{ __('messages.shop_name') }}</label>
                            <input type="text" name="shop_name" class="form-control bg-dark text-white border-secondary" value="{{ $setting->shop_name }}">
                        </div>
                        <div class="col-md-5">
                            <label class="small fw-bold mb-1">{{ __('messages.contact_phone') }}</label>
                            <input type="text" name="phone" class="form-control bg-dark text-white border-secondary" value="{{ $setting->phone }}">
                        </div>
                        <div class="col-12">
                            <label class="small fw-bold mb-1">{{ __('messages.shop_address') }}</label>
                            <input type="text" name="address" class="form-control bg-dark text-white border-secondary" value="{{ $setting->address }}">
                        </div>
                    </div>
                </div>

                <div class="card-custom p-4">
                    <h5 class="fw-bold mb-4 text-success"><i class="fa fa-file-invoice-dollar me-2"></i> {{ __('messages.payment_invoice_config') }}</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="small fw-bold mb-1">{{ __('messages.vat_tax') }}</label>
                            <input type="number" name="vat" class="form-control bg-dark text-white border-secondary" value="{{ $setting->vat }}">
                            <label class="small fw-bold mb-1">{{ __('messages.vat_desc') }}</small>
                        </div>
                        <div class="col-md-8">
                            <label class="small fw-bold mb-1">{{ __('messages.invoice_footer_greeting') }}</label>
                            <textarea name="footer_text" rows="3" class="form-control bg-dark text-white border-secondary">{{ $setting->footer_text }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card-custom p-4 text-center h-100">
                    <h5 class="fw-bold mb-4 text-warning text-start"><i class="fa fa-image me-2"></i> {{ __('messages.brand_logo') }}</h5>
                    
                    <div class="border border-secondary border-dashed rounded p-5 mb-3 position-relative" style="border-style: dashed !important; cursor: pointer;">
                        <input type="file" name="logo" class="position-absolute top-0 start-0 opacity-0 w-100 h-100" style="cursor: pointer;" onchange="previewImage(this)">
                        <img id="logoPreview" src="{{ asset('img/'.$setting->logo) }}" class="img-fluid mb-3" style="max-height: 150px;">
                        <div class="text-secondary small">
                            <i class="fa fa-cloud-upload-alt fa-2x mb-2"></i><br>
                            {{ __('messages.click_to_upload_logo') }}<br>
                            <span style="font-size: 10px;">{{ __('messages.logo_format') }}</span>
                        </div>
                    </div>
                    
                    <p class=" text-start" style="font-size: 11px;">{{ __('messages.logo_desc') }}</p>
                    
                    <div class="mt-auto pt-4 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 fw-bold py-2"><i class="fa fa-save me-2"></i> {{ __('messages.save_config') }}</button>
                        <button type="reset" class="btn btn-outline-secondary px-3">{{ __('messages.cancel') }}</button>
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