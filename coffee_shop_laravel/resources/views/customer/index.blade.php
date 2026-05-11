<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $shop_setting->shop_name ?? 'HUTECH Coffee' }} | Trang chủ</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,700;0,900;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        :root {
          /* Tông màu Đỏ sậm (Burgundy) & Nâu (Espresso) đặc trưng */
          --brand-primary: #8b1538; 
          --brand-dark: #5c0b22;
          --brand-accent: #c6893f; /* Vàng đồng */
          --text-main: #333333;
          --text-muted: #666666;
          --bg-light: #f9f6f0;
          --bg-white: #ffffff;
          --shadow-soft: 0 4px 20px rgba(139, 21, 56, 0.08);
          --shadow-hover: 0 15px 35px rgba(139, 21, 56, 0.15);
        }

        * { box-sizing: border-box; }
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-light); 
            color: var(--text-main); 
            margin: 0; 
            line-height: 1.6; 
            overflow-x: hidden;
        }
        h1, h2, h3, h4, h5, .serif-font { 
            font-family: 'Playfair Display', serif; 
        }

        /* --- NAVBAR --- */
        #mainNav {
          background-color: rgba(255, 255, 255, 0.95);
          backdrop-filter: blur(15px);
          border-bottom: 1px solid rgba(0,0,0,0.05);
          padding: 1rem 0;
          transition: all 0.4s ease;
          z-index: 1000;
        }
        #mainNav.scrolled { 
            padding: 0.7rem 0; 
            box-shadow: 0 5px 20px rgba(0,0,0,0.05); 
        }
        .navbar-brand { 
            font-size: 1.6rem; 
            font-weight: 800; 
            color: var(--brand-primary) !important; 
            letter-spacing: 1px;
        }
        .nav-link {
            font-weight: 600;
            color: var(--text-main) !important;
            text-transform: uppercase;
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s;
        }
        .nav-link:hover { color: var(--brand-accent) !important; }

        /* --- BUTTONS --- */
        .btn-brand { 
            background: var(--brand-primary); 
            color: #fff !important; 
            border-radius: 50px; 
            padding: 0.7rem 2rem; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease; 
            display: inline-flex; 
            align-items: center; 
            border: 2px solid var(--brand-primary); 
            gap: 10px; 
        }
        .btn-brand:hover { 
            background: var(--brand-dark); 
            border-color: var(--brand-dark);
            transform: translateY(-3px); 
            box-shadow: 0 10px 20px rgba(139, 21, 56, 0.3); 
        }
        .btn-outline-brand {
            background: transparent;
            color: var(--brand-primary) !important;
            border: 2px solid var(--brand-primary);
            border-radius: 50px; 
            padding: 0.7rem 2rem; 
            font-weight: 700; 
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s ease; 
        }
        .btn-outline-brand:hover {
            background: var(--brand-primary);
            color: #fff !important;
            transform: translateY(-3px); 
            box-shadow: 0 10px 20px rgba(139, 21, 56, 0.2); 
        }

        /* --- HERO SECTION --- */
        .hero-section { 
            position: relative; 
            min-height: 90vh; 
            display: flex; 
            align-items: center; 
            background-image: url('https://images.pexels.com/photos/103124/pexels-photo-103124.jpeg?auto=compress&cs=tinysrgb&w=1920'); 
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed; 
        }
        .hero-overlay { 
            position: absolute; inset: 0; 
            background: linear-gradient(135deg, rgba(92,11,34,0.85) 0%, rgba(20,20,20,0.6) 100%); 
        }
        .hero-content { position: relative; z-index: 2; color: #fff; padding-top: 80px; }
        .hero-subtitle { 
            color: var(--brand-accent); 
            text-transform: uppercase; 
            letter-spacing: 4px; 
            font-weight: 700; 
            font-size: 1rem; 
            display: block; 
            margin-bottom: 1.5rem;
        }
        .hero-title { 
            font-size: clamp(3rem, 7vw, 5.5rem); 
            font-weight: 900; 
            line-height: 1.1; 
            margin-bottom: 1.5rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        /* --- SECTION TITLES --- */
        .section-padding { padding: 100px 0; }
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-title h2 {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--brand-primary);
            margin-bottom: 15px;
        }
        .section-title p {
            color: var(--brand-accent);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 600;
            font-size: 0.9rem;
        }
        .title-divider {
            width: 80px;
            height: 3px;
            background: var(--brand-accent);
            margin: 0 auto;
        }

        /* --- OUR STORY --- */
        .story-img-wrapper {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        .story-img-wrapper img {
            width: 100%;
            display: block;
            transition: transform 0.7s ease;
        }
        .story-img-wrapper:hover img {
            transform: scale(1.05);
        }
        .story-badge {
            position: absolute;
            bottom: -30px;
            right: 30px;
            background: var(--brand-primary);
            color: white;
            padding: 30px;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: 800;
            border: 5px solid var(--bg-light);
        }

        /* --- PRODUCT CARDS (SIGNATURE) --- */
        .product-card { 
            background: var(--bg-white); 
            border-radius: 20px; 
            overflow: hidden; 
            box-shadow: var(--shadow-soft); 
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1); 
            height: 100%; 
            display: flex; 
            flex-direction: column; 
            border: 1px solid rgba(0,0,0,0.03); 
            position: relative;
        }
        .product-card:hover { 
            transform: translateY(-10px); 
            box-shadow: var(--shadow-hover); 
        }
        .product-img-wrap { 
            height: 280px; 
            overflow: hidden; 
            position: relative;
            background: #fff;
        }
        .product-img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            transition: transform 0.6s ease; 
        }
        .product-card:hover .product-img { transform: scale(1.1); }
        .product-body { padding: 2rem 1.5rem; flex-grow: 1; display: flex; flex-direction: column; text-align: center; }
        .product-name { font-weight: 800; font-size: 1.2rem; margin-bottom: 10px; color: var(--text-main); }
        .product-price { color: var(--brand-primary); font-weight: 900; font-size: 1.4rem; margin-top: auto; }

        /* --- MENU TRUYỀN THỐNG --- */
        .menu-bg { background-color: var(--bg-white); border-top: 1px solid rgba(0,0,0,0.05); }
        .category-title { 
            color: var(--brand-primary); 
            font-weight: 800; 
            border-bottom: 2px solid var(--bg-light); 
            padding-bottom: 12px; 
            margin-bottom: 30px; 
            text-transform: uppercase; 
            position: relative; 
            font-size: 1.5rem;
        }
        .category-title::after { content: ''; position: absolute; bottom: -2px; left: 0; width: 60px; height: 3px; background: var(--brand-accent); }
        .menu-item { display: flex; align-items: baseline; margin-bottom: 20px; padding: 8px 15px; border-radius: 12px; transition: 0.3s; margin-left: -15px; margin-right: -15px; }
        .menu-item:hover { background: var(--bg-light); transform: translateX(5px); }
        .menu-dots { flex-grow: 1; border-bottom: 2px dotted rgba(0,0,0,0.1); margin: 0 15px; }

        /* --- NEWS SECTION --- */
        .news-card {
            background: var(--bg-white);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
            transition: 0.3s;
        }
        .news-card:hover {
            box-shadow: var(--shadow-hover);
        }
        .news-img {
            height: 220px;
            width: 100%;
            object-fit: cover;
        }
        .news-body {
            padding: 1.5rem;
        }
        .news-date {
            color: var(--brand-accent);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 10px;
            display: block;
        }
        .news-title {
            font-weight: 800;
            font-size: 1.2rem;
            line-height: 1.4;
            margin-bottom: 15px;
            color: var(--text-main);
        }

        /* --- FOOTER --- */
        footer { 
            background-color: var(--brand-dark); 
            color: rgba(255, 255, 255, 0.8); 
            padding: 80px 0 30px; 
            border-top: 5px solid var(--brand-accent);
        }
        .footer-title { color: #fff; font-weight: 800; margin-bottom: 25px; text-transform: uppercase; letter-spacing: 1px; }
        .footer-link { color: rgba(255,255,255,0.7); text-decoration: none; display: block; margin-bottom: 12px; transition: 0.3s; }
        .footer-link:hover { color: var(--brand-accent); transform: translateX(5px); }

        /* --- SCANNER --- */
        #reader { border: none !important; border-radius: 20px; overflow: hidden; }
        #reader__scan_region video { object-fit: cover !important; }
        .scanner-container { position: relative; background: #000; border-radius: 20px; overflow: hidden; }
        .scanner-overlay-ui { position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 3px solid var(--brand-accent); pointer-events: none; z-index: 10; box-shadow: 0 0 0 4000px rgba(0,0,0,0.6); }
        .scanner-line { position: absolute; top: 0; left: 0; width: 100%; height: 3px; background: var(--brand-accent); box-shadow: 0 0 15px var(--brand-accent); animation: scanAnim 2s ease-in-out infinite; }
        @keyframes scanAnim { 0% { top: 5%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 95%; opacity: 0; } }
        html { scroll-behavior: smooth; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                @if(isset($shop_setting->logo))
                    <img src="{{ asset('img/' . $shop_setting->logo) }}" height="40" class="me-2" alt="Logo">
                @else
                    <i class="fa-solid fa-mug-hot me-2 text-warning"></i>
                @endif
                {{ strtoupper($shop_setting->shop_name ?? 'HUTECH COFFEE') }}
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fa fa-bars fs-2" style="color: var(--brand-primary);"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-2">
                    <li class="nav-item"><a class="nav-link" href="#">{{ __('messages.home') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#story">{{ __('messages.our_story') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#bestseller">{{ __('messages.suggestions') }}</a></li>
                    <li class="nav-item"><a class="nav-link" href="#full-menu">{{ __('messages.menu') }}</a></li>
                </ul>

                <div class="d-flex align-items-center flex-wrap gap-3">
                    <!-- LANGUAGE SWITCHER -->
                    <div class="dropdown">
                        <div class="d-flex align-items-center gap-2" data-bs-toggle="dropdown" style="cursor: pointer; font-weight: 700;">
                            @if(App::getLocale() == 'en')
                                <img src="https://flagcdn.com/w20/gb.png" alt="English" style="border-radius: 2px;"> EN
                            @else
                                <img src="https://flagcdn.com/w20/vn.png" alt="Tiếng Việt" style="border-radius: 2px;"> VI
                            @endif
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-3" style="border-radius: 12px; z-index: 1050;">
                            <li><a class="dropdown-item py-2 {{ App::getLocale() == 'vi' ? 'active fw-bold' : '' }}" href="{{ route('lang.switch', 'vi') }}" style="{{ App::getLocale() == 'vi' ? 'background-color: var(--bg-light); color: var(--brand-primary)' : '' }}"><img src="https://flagcdn.com/w20/vn.png" class="me-2"> Tiếng Việt</a></li>
                            <li><a class="dropdown-item py-2 {{ App::getLocale() == 'en' ? 'active fw-bold' : '' }}" href="{{ route('lang.switch', 'en') }}" style="{{ App::getLocale() == 'en' ? 'background-color: var(--bg-light); color: var(--brand-primary)' : '' }}"><img src="https://flagcdn.com/w20/gb.png" class="me-2"> English</a></li>
                        </ul>
                    </div>

                    <button onclick="startScanner()" class="btn-brand shadow-sm">
                        <i class="fa-solid fa-qrcode"></i> {{ __('messages.order_at_table') }}
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container hero-content text-center animate__animated animate__fadeInUp">
            <span class="hero-subtitle">{{ __('messages.morning_energy') }}</span>
            <h1 class="hero-title serif-font">{!! __('messages.authentic_coffee_taste') !!}</h1>
            <p class="fs-5 mb-5 mx-auto opacity-75" style="max-width: 700px; font-weight: 300;">
                {!! __('messages.hero_desc', ['shop_name' => $shop_setting->shop_name ?? 'HUTECH Coffee']) !!}
            </p>
            <div class="d-flex gap-3 justify-content-center flex-wrap">
                <a href="javascript:void(0)" onclick="startScanner()" class="btn-brand btn-lg">{{ __('messages.scan_qr_order') }} <i class="fa-solid fa-expand ms-1"></i></a>
                <a href="#bestseller" class="btn-outline-brand btn-lg" style="color: white !important; border-color: white;">{{ __('messages.discover_more') }}</a>
            </div>
        </div>
    </header>

    <!-- OUR STORY SECTION -->
    <section class="section-padding bg-white" id="story">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-6">
                    <div class="story-img-wrapper">
                        <img src="https://images.pexels.com/photos/1749303/pexels-photo-1749303.jpeg?auto=compress&cs=tinysrgb&w=800" alt="Coffee Roasting">
                        <div class="story-badge">
                            <div>
                                <span class="fs-3 d-block lh-1">100%</span>
                                <small style="font-size: 0.6rem; text-transform: uppercase;">Robusta</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 ps-lg-5">
                    <span class="text-uppercase fw-bold mb-2 d-block" style="color: var(--brand-accent); letter-spacing: 2px;">Về Chúng Tôi</span>
                    <h2 class="serif-font mb-4" style="color: var(--brand-primary); font-size: 3rem; font-weight: 900;">{{ __('messages.our_story') }}</h2>
                    <p class="fs-5 text-muted mb-4" style="line-height: 1.8;">
                        {{ __('messages.our_story_desc') }}
                    </p>
                    <a href="#full-menu" class="btn-outline-brand">{{ __('messages.read_more_story') }}</a>
                </div>
            </div>
        </div>
    </section>

    <!-- BEST SELLERS -->
    <section class="section-padding" id="bestseller">
        <div class="container">
            <div class="section-title">
                <p>{{ __('messages.suggestions') }}</p>
                <h2 class="serif-font">{{ __('messages.recommended_dishes') }}</h2>
                <div class="title-divider"></div>
            </div>
            
            <div class="row g-4 justify-content-center">
                @forelse($bestSellers ?? [] as $product)
                <div class="col-sm-6 col-lg-3">
                    <div class="product-card">
                        <div class="product-img-wrap">
                            <img src="{{ asset('img/'.$product->image) }}" onerror="this.src='https://placehold.co/500x500?text=Product'" class="product-img">
                        </div>
                        <div class="product-body">
                            <p class="text-uppercase fw-bold mb-1" style="font-size: 0.75rem; color: var(--brand-accent); letter-spacing: 1px;">{{ $product->category->name ?? 'Signature' }}</p>
                            <h5 class="product-name">{{ $product->name }}</h5>
                            <div class="product-price">{{ number_format($product->price) }}đ</div>
                            <button class="btn btn-outline-brand btn-sm mt-3 w-100 rounded-pill" onclick="startScanner()">{{ __('messages.order_now') }}</button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5 opacity-50">{{ __('messages.updating_menu') }}</div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- FULL MENU -->
    <section class="section-padding menu-bg" id="full-menu">
        <div class="container">
            <div class="section-title">
                <p>{{ __('messages.traditional_menu') }}</p>
                <h2 class="serif-font">{{ __('messages.menu_categories') }}</h2>
                <div class="title-divider"></div>
            </div>
            
            <div class="row g-5">
                @foreach($menuCategories ?? [] as $cat)
                <div class="col-lg-6">
                    <h4 class="category-title serif-font">{{ $cat->name }}</h4>
                    <div class="mt-4">
                        @foreach($cat->products as $item)
                        <div class="menu-item">
                            <span class="fw-bold fs-5">{{ $item->name }}</span>
                            <div class="menu-dots"></div>
                            <span class="fw-bold fs-5" style="color: var(--brand-primary);">{{ number_format($item->price) }}đ</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- NEWS & PROMOTIONS -->
    <section class="section-padding bg-white" id="news">
        <div class="container">
            <div class="section-title">
                <p>{{ __('messages.discover_more') }}</p>
                <h2 class="serif-font">{{ __('messages.community_news') }}</h2>
                <div class="title-divider"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-4">
                    <div class="news-card h-100">
                        <img src="https://images.pexels.com/photos/374023/pexels-photo-374023.jpeg?auto=compress&cs=tinysrgb&w=600" class="news-img" alt="News">
                        <div class="news-body">
                            <span class="news-date">12 Tháng 5, 2026</span>
                            <h4 class="news-title">{{ __('messages.news_1_title') }}</h4>
                            <p class="text-muted small mb-0">{{ __('messages.news_1_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="news-card h-100">
                        <img src="https://images.pexels.com/photos/1193335/pexels-photo-1193335.jpeg?auto=compress&cs=tinysrgb&w=600" class="news-img" alt="News">
                        <div class="news-body">
                            <span class="news-date">05 Tháng 5, 2026</span>
                            <h4 class="news-title">{{ __('messages.news_2_title') }}</h4>
                            <p class="text-muted small mb-0">{{ __('messages.news_2_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="news-card h-100">
                        <img src="https://images.pexels.com/photos/1556991/pexels-photo-1556991.jpeg?auto=compress&cs=tinysrgb&w=600" class="news-img" alt="News">
                        <div class="news-body">
                            <span class="news-date">28 Tháng 4, 2026</span>
                            <h4 class="news-title">{{ __('messages.news_3_title') }}</h4>
                            <p class="text-muted small mb-0">{{ __('messages.news_3_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-5">
                <a href="#" class="btn-outline-brand">{{ __('messages.view_all_news') }}</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="contact">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-5 pe-lg-5">
                    <div class="mb-4 d-flex align-items-center">
                        @if(isset($shop_setting->logo))
                            <img src="{{ asset('img/' . $shop_setting->logo) }}" height="50" class="me-3 bg-white rounded p-1" alt="Logo">
                        @else
                            <i class="fa-solid fa-mug-hot fa-2x text-warning me-3"></i>
                        @endif
                        <span class="h4 fw-900 m-0 text-white">{{ strtoupper($shop_setting->shop_name ?? 'HUTECH COFFEE') }}</span>
                    </div>
                    <p class="opacity-75 mb-4" style="font-size: 0.95rem; line-height: 1.8;">{{ __('messages.footer_desc') }}</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-light rounded-circle" style="width:40px; height:40px; padding:0; line-height:38px;"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle" style="width:40px; height:40px; padding:0; line-height:38px;"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle" style="width:40px; height:40px; padding:0; line-height:38px;"><i class="fa-brands fa-tiktok"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">{{ __('messages.quick_links') }}</h5>
                    <a href="#" class="footer-link">{{ __('messages.home') }}</a>
                    <a href="#story" class="footer-link">{{ __('messages.our_story') }}</a>
                    <a href="#full-menu" class="footer-link">{{ __('messages.menu') }}</a>
                    <a href="#news" class="footer-link">{{ __('messages.community_news') }}</a>
                </div>
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">{{ __('messages.contact') }}</h5>
                    <div class="opacity-75 mb-3 d-flex">
                        <i class="fa fa-location-dot mt-1 me-3 text-warning"></i> 
                        <span>{{ $shop_setting->address ?? '475A Điện Biên Phủ, P.25, Q.Bình Thạnh, TP.HCM' }}</span>
                    </div>
                    <div class="opacity-75 mb-3 d-flex">
                        <i class="fa fa-phone mt-1 me-3 text-warning"></i> 
                        <span>{{ $shop_setting->phone ?? '0123 456 789' }}</span>
                    </div>
                    <div class="opacity-75 mb-3 d-flex">
                        <i class="fa fa-envelope mt-1 me-3 text-warning"></i> 
                        <span>contact@hutechcoffee.com</span>
                    </div>
                </div>
            </div>
            <div class="border-top mt-5 pt-4 text-center opacity-50 small">
                &copy; 2026 {{ $shop_setting->shop_name ?? 'HUTECH Coffee' }}. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- SCANNER MODAL -->
    <div class="modal fade" id="scannerModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content text-white border-0 shadow-lg" style="border-radius: 30px; background: var(--text-main);">
                <div class="modal-header border-0 p-4 pb-0">
                    <h5 class="modal-title fw-bold" style="color: var(--brand-accent);"><i class="fa-solid fa-qrcode me-2"></i> {{ __('messages.scan_at_table_title') }}</h5>
                    <button type="button" class="btn-close btn-close-white" onclick="stopScanner()"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <p class="small opacity-75 mb-4">{{ __('messages.scan_instruction') }}</p>
                    
                    <div class="scanner-container">
                        <div id="reader"></div>
                        <div class="scanner-overlay-ui">
                            <div class="scanner-line"></div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <button class="btn btn-outline-light rounded-pill px-5 py-2 fw-bold" onclick="stopScanner()">{{ __('messages.cancel_btn') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                document.getElementById('mainNav').classList.add('scrolled');
            } else {
                document.getElementById('mainNav').classList.remove('scrolled');
            }
        });

        let html5QrCode;
        function startScanner() {
            const modal = new bootstrap.Modal(document.getElementById('scannerModal'));
            modal.show();
            html5QrCode = new Html5Qrcode("reader");
            const config = { fps: 15, qrbox: { width: 250, height: 250 } };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    document.querySelector('.scanner-overlay-ui').style.borderColor = "#22c55e";
                    setTimeout(() => { window.location.href = decodedText; }, 500);
                    stopScanner();
                },
                (errorMessage) => { }
            ).catch((err) => {
                alert("{{ __('messages.camera_error') }}");
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

        document.getElementById('scannerModal').addEventListener('hidden.bs.modal', function () {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop();
            }
        });
    </script>
</body>
</html>