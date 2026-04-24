@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h3 class="fw-bold dynamic-text"><i class="fa-solid fa-user-gear me-2 text-primary"></i> Hồ sơ cá nhân</h3>
        <p class="text-secondary small">Quản lý thông tin tài khoản và mật khẩu của bạn</p>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card-custom p-4 shadow-sm">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    <div class="text-center mb-4">
                        <div class="avatar-box mx-auto mb-2" style="width: 80px; height: 80px; font-size: 2rem;">
                            {{ substr($user->name ?? session('user_name', 'AD'), 0, 2) }}
                        </div>
                        <h5 class="fw-bold dynamic-text m-0">{{ $user->name ?? session('user_name', 'Quản Trị Viên') }}</h5>
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 mt-2">Quản trị viên</span>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1 dynamic-text">Họ và tên</label>
                        <input type="text" name="name" class="form-control bg-dark text-white border-secondary" value="{{ $user->name ?? session('user_name', 'Quản Trị Viên') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold mb-1 dynamic-text">Địa chỉ Email</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-secondary" value="{{ $user->email ?? 'admin@premiumcoffee.com' }}" required>
                    </div>

                    <hr class="my-4 border-secondary opacity-25">

                    <div class="mb-3">
                        <label class="small fw-bold mb-1 dynamic-text">Mật khẩu mới (Để trống nếu không đổi)</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-secondary" placeholder="********">
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100 fw-bold py-2">
                            <i class="fa-solid fa-floppy-disk me-2"></i> Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-lg-5 ms-lg-auto">
            <div class="card-custom p-4 bg-primary bg-opacity-10 border-primary border-opacity-25 mt-4 mt-lg-0">
                <h6 class="fw-bold text-primary mb-3"><i class="fa-solid fa-shield-halved me-2"></i> Bảo mật tài khoản</h6>
                <ul class="small text-secondary ps-3 mb-0">
                    <li class="mb-2">Sử dụng mật khẩu mạnh bao gồm chữ hoa, chữ thường, số và ký hiệu.</li>
                    <li class="mb-2">Không chia sẻ tài khoản quản trị cho người khác.</li>
                    <li>Thông tin Email được dùng để lấy lại mật khẩu khi cần thiết.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection