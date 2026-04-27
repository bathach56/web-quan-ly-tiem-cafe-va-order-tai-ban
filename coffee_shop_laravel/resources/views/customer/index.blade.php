<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $shop_setting->shop_name ?? 'HUTECH Coffee' }} | Trang chủ</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        :root {
          --coffee: #6f4e37;
          --coffee-light: #8b6347;
          --coffee-dark: #2c1810;
          --cream: #fdf8f5;
          --cream-dark: #f5ede4;
          --warm-50: #faf5f0;
          --warm-100: #f0e4d4;
          --text-main: #3a2a1e;
          --text-muted: #8a7060;
          --gold: #c8960c;
          --shadow-soft: 0 4px 20px rgba(111, 78, 55, 0.10);
          --shadow-hover: 0 16px 40px rgba(111, 78, 55, 0.18);
          --radius-card: 18px;
        }

        * { box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--cream); color: var(--text-main); margin: 0; line-height: 1.6; }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }

        /* NAVBAR */
        #mainNav {
          background-color: rgba(253, 248, 245, 0.97);
          backdrop-filter: blur(12px);
          border-bottom: 1px solid var(--warm-100);
          padding: 0.9rem 0;
          transition: all 0.3s;
          z-index: 1000;
        }
        #mainNav.scrolled { box-shadow: 0 2px 20px rgba(44, 24, 16, 0.10); }
        .navbar-brand { font-size: 1.5rem; font-weight: 700; color: var(--coffee) !important; }

        /* BUTTONS */
        .btn-order { background-color: var(--coffee); color: #fff !important; border-radius: 50px; padding: 0.7rem 1.8rem; font-weight: 600; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; border: none; gap: 8px; }
        .btn-order:hover { background-color: var(--coffee-dark); transform: translateY(-2px); box-shadow: 0 6px 18px rgba(44, 24, 16, 0.2); }
        .btn-hero { background-color: #fff; color: var(--coffee) !important; border-radius: 50px; font-weight: 600; padding: 0.8rem 2.2rem; transition: 0.3s; text-decoration: none; display: inline-flex; align-items: center; gap: 10px; }

        /* HERO SECTION */
        .hero-section { position: relative; min-height: 85vh; display: flex; align-items: center; background-image: url('https://images.pexels.com/photos/1695052/pexels-photo-1695052.jpeg?auto=compress&cs=tinysrgb&w=1920'); background-size: cover; background-position: center; background-attachment: fixed; }
        .hero-overlay { position: absolute; inset: 0; background: linear-gradient(160deg, rgba(44,24,16,0.72) 0%, rgba(111,78,55,0.55) 100%); }
        .hero-content { position: relative; z-index: 2; color: #fff; }
        .hero-title { font-size: clamp(2.5rem, 6vw, 4.5rem); font-weight: 700; line-height: 1.2; }

        /* SCANNER STYLES */
        #reader { border: none !important; border-radius: 20px; overflow: hidden; }
        #reader__scan_region video { object-fit: cover !important; }
        .scanner-container { position: relative; background: #000; border-radius: 20px; overflow: hidden; }
        .scanner-overlay-ui {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            border: 2px solid var(--coffee); pointer-events: none; z-index: 10;
            box-shadow: 0 0 0 4000px rgba(0,0,0,0.5); /* Tạo hiệu ứng tối xung quanh khung quét */
        }
        .scanner-line {
            position: absolute; top: 0; left: 0; width: 100%; height: 2px;
            background: rgba(255,255,255,0.5); box-shadow: 0 0 10px #fff;
            animation: scanAnim 2s linear infinite;
        }
        @keyframes scanAnim { 0% { top: 10%; } 100% { top: 90%; } }

        /* PRODUCT CARDS */
        .section-padding { padding: 90px 0; }
        .product-card { background: #fff; border-radius: var(--radius-card); overflow: hidden; box-shadow: var(--shadow-soft); transition: 0.4s; height: 100%; display: flex; flex-direction: column; }
        .product-img-wrap { height: 230px; overflow: hidden; }
        .product-img { width: 100%; height: 100%; object-fit: cover; transition: 0.5s; }
        .product-body { padding: 1.5rem; flex-grow: 1; display: flex; flex-direction: column; }

        /* MENU ITEMS */
        .category-title { color: var(--coffee); font-weight: 700; border-bottom: 2px solid var(--warm-100); padding-bottom: 12px; margin-bottom: 30px; text-transform: uppercase; }
        .menu-item { display: flex; align-items: baseline; margin-bottom: 18px; }
        .menu-dots { flex-grow: 1; border-bottom: 1px dotted var(--text-muted); margin: 0 10px; opacity: 0.3; }

        footer { background-color: var(--coffee-dark); color: rgba(255, 255, 255, 0.7); padding: 80px 0 30px; }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fa-solid fa-mug-hot me-2"></i>{{ strtoupper($shop_setting->shop_name ?? 'HUTECH COFFEE') }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa fa-bars text-coffee"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Trang chủ</a></li>
                    <li class="nav-item"><a class="nav-link" href="#bestseller">Gợi ý</a></li>
                    <li class="nav-item"><a class="nav-link" href="#full-menu">Thực đơn</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Liên hệ</a></li>
                </ul>
                <button onclick="startScanner()" class="btn-order shadow-sm">
                    <i class="fa-solid fa-qrcode"></i> ĐẶT MÓN TẠI BÀN
                </button>
            </div>
        </div>
    </nav>

    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center animate__animated animate__fadeIn">
            <span class="mb-3 d-block text-uppercase fw-bold" style="letter-spacing: 5px; font-size: 0.9rem;">Năng lượng ngày mới</span>
            <h1 class="hero-title mb-4">Hương vị cà phê<br><em>đích thực</em> nhất</h1>
            <p class="fs-5 mb-5 mx-auto opacity-75" style="max-width: 600px;">
                Từng hạt cà phê tại <strong>{{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}</strong> được tuyển chọn để mang lại trải nghiệm tuyệt vời của Nhóm 3.
            </p>
            <a href="javascript:void(0)" onclick="startScanner()" class="btn-hero shadow">Quét mã QR gọi món <i class="fa-solid fa-camera"></i></a>
        </div>
    </header>

    <section class="section-padding bg-white" id="bestseller">
        <div class="container text-center">
            <h2 class="display-5 fw-bold mb-5">Món Ngon Đề Xuất</h2>
            <div class="row g-4 text-start">
                @forelse($bestSellers ?? [] as $product)
                <div class="col-md-6 col-lg-3">
                    <div class="product-card shadow-sm">
                        <div class="product-img-wrap">
                            <img src="{{ asset('img/'.$product->image) }}" onerror="this.src='https://placehold.co/500x500?text=Product'" class="product-img">
                        </div>
                        <div class="product-body">
                            <h5 class="product-name fw-bold">{{ $product->name }}</h5>
                            <p class="text-muted small">{{ $product->category->name ?? 'Signature' }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-auto pt-3">
                                <span class="fw-bold text-coffee fs-5">{{ number_format($product->price) }}đ</span>
                                <button class="btn btn-sm btn-dark rounded-pill px-3" onclick="startScanner()">Đặt ngay</button>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 opacity-50">Đang cập nhật thực đơn...</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="section-padding" id="full-menu" style="background-color: var(--warm-50);">
        <div class="container">
            <div class="text-center mb-5">
                <span class="mb-2 d-block fw-bold text-uppercase" style="color: var(--coffee);">Menu truyền thống</span>
                <h2 class="display-5 fw-bold">Danh Mục Thực Đơn</h2>
            </div>
            
            <div class="row g-5">
                @foreach($menuCategories ?? [] as $cat)
                <div class="col-lg-4 col-md-6">
                    <h4 class="category-title">{{ $cat->name }}</h4>
                    @foreach($cat->products as $item)
                    <div class="menu-item">
                        <span class="fw-bold">{{ $item->name }}</span>
                        <div class="menu-dots"></div>
                        <span class="fw-bold text-coffee">{{ number_format($item->price) }}đ</span>
                    </div>
                    @endforeach
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <footer id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-4">
                    <div class="mb-4 h3 fw-bold">{{ strtoupper($shop_setting->shop_name ?? 'HUTECH COFFEE') }}</div>
                    <p class="small opacity-75 mb-4">Nhóm 3 - 23DTHB6 - Khoa Công nghệ thông tin HUTECH.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="text-white mb-4">Liên hệ</h5>
                    <div class="small opacity-75">
                        <p><i class="fa fa-location-dot me-2"></i> {{ $shop_setting->address ?? '475A Điện Biên Phủ, Bình Thạnh' }}</p>
                        <p><i class="fa fa-phone me-2"></i> {{ $shop_setting->phone ?? '0123 456 789' }}</p>
                    </div>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <div class="d-inline-block p-2 bg-white rounded-3 shadow-sm">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data={{ url('/') }}" alt="Web QR">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="modal fade" id="scannerModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-white border-0 shadow-lg" style="border-radius: 30px;">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold text-warning"><i class="fa-solid fa-qrcode me-2"></i> QUÉT MÃ TẠI BÀN</h5>
                    <button type="button" class="btn-close btn-close-white" onclick="stopScanner()"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="small opacity-75 mb-4">Vui lòng cấp quyền camera và đưa mã QR trên bàn vào khung quét để tiếp tục.</p>
                    
                    <div class="scanner-container">
                        <div id="reader"></div>
                        <div class="scanner-overlay-ui">
                            <div class="scanner-line"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-outline-light btn-sm rounded-pill px-4" onclick="stopScanner()">HỦY BỎ</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let html5QrCode;

        function startScanner() {
            // Mở Modal
            const modal = new bootstrap.Modal(document.getElementById('scannerModal'));
            modal.show();

            // Khởi tạo máy quét
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 15, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(
                { facingMode: "environment" }, // Dùng camera sau
                config,
                (decodedText) => {
                    // KHI QUÉT THÀNH CÔNG
                    console.log("QR Data: ", decodedText);
                    // Hiệu ứng phản hồi
                    document.querySelector('.scanner-overlay-ui').style.borderColor = "#22c55e";
                    
                    // Chuyển hướng sau 0.5s
                    setTimeout(() => {
                        window.location.href = decodedText;
                    }, 500);
                    
                    stopScanner();
                },
                (errorMessage) => { /* Quét lỗi, bỏ qua */ }
            ).catch((err) => {
                console.error("Camera Error: ", err);
                alert("Vui lòng cấp quyền truy cập Camera để sử dụng tính năng này!");
                stopScanner();
            });
        }

        function stopScanner() {
            if (html5QrCode) {
                html5QrCode.stop().then(() => {
                    bootstrap.Modal.getInstance(document.getElementById('scannerModal')).hide();
                }).catch(() => {
                    bootstrap.Modal.getInstance(document.getElementById('scannerModal')).hide();
                });
            } else {
                bootstrap.Modal.getInstance(document.getElementById('scannerModal')).hide();
            }
        }

        // Đóng scanner nếu khách bấm nút X hoặc phím Esc
        document.getElementById('scannerModal').addEventListener('hidden.bs.modal', function () {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop();
            }
        });
    </script>
</body>
</html>