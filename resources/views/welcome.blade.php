<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO Utama -->
    <title>Niema Digital - Jasa Undangan Online Elegan & Hemat</title>
    <meta name="description"
          content="Jasa pembuatan undangan pernikahan digital. Desain kekinian, fitur lengkap (RSVP, Amplop Digital, Peta), harga mulai 114rb. Siap sebar hitungan menit.">
    <meta name="keywords"
          content="undangan digital, undangan online, undangan pernikahan, undangan website, undangan murah medan, niema digital">
    <meta name="author" content="Niema Digital">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://niemadigital.com/">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://niemadigital.com/">
    <meta property="og:title" content="Niema Digital - Undangan Online Paling Sat-set">
    <meta property="og:description"
          content="Buat undangan digitalmu sekarang. Fitur lengkap, desain premium, harga kawan.">
    <meta property="og:image" content="https://niemadigital.com/assets/og-image.jpg">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">

    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --font-sans: 'Poppins', sans-serif;
            --font-serif: 'Playfair Display', serif;
            --font-script: 'Great Vibes', cursive;

            /* Warna Brand Niema (Pink Magenta - Vibrant) */
            --brand-color: #F50057;
            --brand-dark: #C51162;
            --brand-light: #fff0f5;
            --brand-subtle: #fce4ec;

            --text-dark: #1e293b;
            --text-muted: #64748b;
            --section-spacing: 6rem; /* Jarak antar section lebih lega */
        }

        body {
            font-family: var(--font-sans), serif;
            color: var(--text-dark);
            background-color: #fff;
            overflow-x: hidden;
            line-height: 1.6;
        }

        h1, h2, h3, h4, h5 {
            font-family: var(--font-serif);
            color: var(--text-dark);
        }

        /* --- Typography Responsiveness --- */
        @media (max-width: 768px) {
            h1.display-3 {
                font-size: 2.5rem;
            }

            h2.display-6 {
                font-size: 2rem;
            }

            .lead {
                font-size: 1rem;
            }

            :root {
                --section-spacing: 4rem;
            }
        }

        /* --- Logo Styling --- */
        .logo-text {
            font-family: var(--font-script);
            font-size: 2.2rem;
            color: var(--brand-color);
            line-height: 0.8;
        }

        .logo-sub {
            font-family: var(--font-sans);
            font-size: 0.65rem;
            letter-spacing: 3px;
            font-weight: 700;
            color: var(--brand-color);
            text-transform: uppercase;
            margin-left: 2px;
        }

        /* --- Navbar --- */
        .navbar-niema {
            background-color: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            padding: 0.8rem 0;
            border-bottom: 1px solid var(--brand-subtle);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .nav-link {
            font-weight: 500;
            color: var(--text-dark);
            transition: color 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--brand-color);
        }

        /* --- Buttons --- */
        .btn-brand {
            background-color: var(--brand-color);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(245, 0, 87, 0.25);
        }

        .btn-brand:hover {
            background-color: var(--brand-dark);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(245, 0, 87, 0.35);
        }

        .btn-outline-brand {
            color: var(--brand-color);
            border: 2px solid var(--brand-color);
            padding: 10px 28px;
            border-radius: 50px;
            font-weight: 600;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-outline-brand:hover {
            background-color: var(--brand-color);
            color: white;
            box-shadow: 0 4px 15px rgba(245, 0, 87, 0.15);
        }

        /* --- Hero Section --- */
        .hero {
            background: linear-gradient(135deg, #fff 40%, var(--brand-light) 100%);
            padding: var(--section-spacing) 0;
            min-height: 90dvh;
            display: flex;
            align-items: center;
            position: relative;
        }

        .phone-mockup-container {
            border: 8px solid #333;
            border-radius: 35px;
            overflow: hidden;
            width: 100%;
            max-width: 280px;
            margin: 0 auto;
            position: relative;
            background: #fff;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            aspect-ratio: 9/19;
        }

        .phone-content {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* --- Card Styling --- */
        .card-feature {
            border: 1px solid var(--brand-subtle);
            border-radius: 24px;
            padding: 2.5rem 2rem;
            transition: all 0.3s ease;
            height: 100%;
            background: white;
        }

        .card-feature:hover {
            border-color: var(--brand-color);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        }

        .icon-circle {
            width: 64px;
            height: 64px;
            background-color: var(--brand-light);
            color: var(--brand-color);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
        }

        .card-feature:hover .icon-circle {
            transform: scale(1.1);
        }

        /* --- Steps / Cara Kerja --- */
        .step-card {
            text-align: center;
            position: relative;
            padding: 1.5rem;
        }

        .step-number {
            width: 40px;
            height: 40px;
            background: var(--brand-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 10px rgba(245, 0, 87, 0.3);
        }

        /* Garis penghubung di desktop */
        @media (min-width: 992px) {
            .step-card::after {
                content: '';
                position: absolute;
                top: 35px;
                right: -50%;
                width: 100%;
                height: 2px;
                background: #e2e8f0;
                z-index: -1;
            }

            .step-card:last-child::after {
                display: none;
            }
        }

        /* --- Testimonial --- */
        .testimonial-card {
            background: #fff;
            border: 1px solid #f1f5f9;
            padding: 2rem;
            border-radius: 20px;
            height: 100%;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .avatar-small {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--brand-light);
        }

        /* --- Theme Section --- */
        .theme-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
        }

        .theme-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .theme-placeholder {
            background-color: #f1f5f9;
            height: 250px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #cbd5e1;
            position: relative;
        }

        .theme-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* --- Pricing --- */
        .pricing-section {
            background-color: var(--brand-light);
            padding: var(--section-spacing) 0;
        }

        .pricing-card {
            border: 1px solid var(--brand-subtle);
            border-radius: 24px;
            padding: 3rem 2rem;
            background: white;
            position: relative;
            overflow: hidden;
            height: 100%;
            transition: transform 0.3s;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .pricing-card.popular {
            border: 2px solid var(--brand-color);
            background: linear-gradient(to bottom, #fff, #fff0f5);
            transform: scale(1.05);
            z-index: 2;
            box-shadow: 0 25px 50px rgba(245, 0, 87, 0.15);
        }

        @media (max-width: 991px) {
            .pricing-card.popular {
                transform: scale(1);
                margin-top: 1rem;
                margin-bottom: 1rem;
            }
        }

        .badge-popular {
            background: var(--brand-color);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 8px 20px;
            border-radius: 0 0 16px 16px;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
        }

        .price-strike {
            text-decoration: line-through;
            color: var(--text-muted);
            font-size: 1.1rem;
            opacity: 0.7;
        }

        .price-main {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--brand-color);
            line-height: 1;
            margin: 0.5rem 0;
        }

        /* --- FAQ Accordion --- */
        .accordion-button:not(.collapsed) {
            color: var(--brand-color);
            background-color: var(--brand-light);
            box-shadow: inset 0 -1px 0 rgba(0, 0, 0, .125);
        }

        .accordion-button:focus {
            box-shadow: 0 0 0 0.25rem rgba(245, 0, 87, 0.25);
            border-color: var(--brand-color);
        }

        /* --- CTA Section --- */
        .cta-section {
            background: linear-gradient(135deg, var(--brand-color), var(--brand-dark));
            color: white;
            border-radius: 30px;
            padding: 4rem 2rem;
            margin-bottom: -4rem; /* Overlap footer effect */
            position: relative;
            z-index: 10;
            box-shadow: 0 20px 50px rgba(245, 0, 87, 0.3);
        }

        .cta-section h2 {
            color: white;
        }

        /* --- Footer --- */
        footer {
            background-color: #fff;
            padding-top: 8rem; /* Extra padding for CTA overlap */
            padding-bottom: 2rem;
            border-top: 1px solid #f1f5f9;
        }

        footer a {
            color: var(--text-muted);
            text-decoration: none;
            transition: color 0.2s;
            font-size: 0.95rem;
        }

        footer a:hover {
            color: var(--brand-color);
        }

        .social-icon {
            color: var(--text-muted);
            transition: all 0.3s;
        }

        .social-icon:hover {
            color: var(--brand-color);
            transform: translateY(-3px);
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-niema sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#" aria-label="Niema Digital Home">
            <div class="d-flex flex-column">
                <span class="logo-text">Niema</span>
                <span class="logo-sub">DIGITAL</span>
            </div>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link px-3" href="#tentang">Tentang</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#cara-kerja">Cara Kerja</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#tema">Tema</a></li>
                <li class="nav-item"><a class="nav-link px-3" href="#harga">Harga</a></li>
            </ul>
            <div class="d-flex gap-2 mt-3 mt-lg-0">
                <a href="{{ config('app.url') }}" class="btn btn-outline-brand btn-sm px-4">Login</a>
                <a href="#harga" class="btn btn-brand btn-sm px-4">Pesan</a>
            </div>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center flex-column-reverse flex-lg-row">
            <!-- Text Content -->
            <div class="col-lg-6 mt-5 mt-lg-0 text-center text-lg-start">
                    <span class="badge rounded-pill px-3 py-2 mb-3 fw-medium d-inline-flex align-items-center gap-2"
                          style="background-color: var(--brand-subtle); color: var(--brand-color);">
                        <i data-lucide="zap" size="14"></i> Jasa Undangan Digital Paling Sat-set
                    </span>
                <h1 class="display-3 fw-bold mb-4 lh-tight">
                    Undangan Digital <br>
                    <span style="color: var(--brand-color);">Elegan & Praktis.</span>
                </h1>
                <p class="lead text-secondary mb-5 pe-lg-5">
                    Bagikan kabar bahagiamu dengan cara yang lebih modern. Tanpa ribet cetak, langsung sebar ke
                    WhatsApp, dan hemat biaya jutaan rupiah.
                </p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="#harga" class="btn btn-brand btn-lg d-flex align-items-center gap-2">
                        Pesan Sekarang <i data-lucide="arrow-right" size="20"></i>
                    </a>
                    <a href="#tema" class="btn btn-outline-brand btn-lg d-flex align-items-center gap-2">
                        <i data-lucide="play-circle" size="20"></i> Lihat Contoh
                    </a>
                </div>
                <div
                    class="mt-4 d-flex align-items-center justify-content-center justify-content-lg-start gap-2 text-muted small">
                    <div class="d-flex">
                        <span class="bg-secondary rounded-circle border border-white"
                              style="width:24px; height:24px; opacity:0.5"></span>
                        <span class="bg-secondary rounded-circle border border-white"
                              style="width:24px; height:24px; opacity:0.5; margin-left:-8px"></span>
                        <span class="bg-secondary rounded-circle border border-white"
                              style="width:24px; height:24px; opacity:0.5; margin-left:-8px"></span>
                    </div>
                    <span>Dipercaya oleh 1.200+ Pasangan</span>
                </div>
            </div>

            <!-- Visual Content -->
            <div class="col-lg-6 text-center">
                <div class="position-relative d-inline-block w-100">
                    <div class="phone-mockup-container">
                        <img
                            src="https://images.unsplash.com/photo-1511285560982-1351cdeb9821?q=80&w=600&auto=format&fit=crop"
                            class="phone-content"
                            alt="Preview Tampilan Undangan Digital di HP">
                        <div class="position-absolute bottom-0 start-0 w-100 p-4 text-white text-center"
                             style="background: linear-gradient(to top, rgba(245, 0, 87, 0.95), transparent);">
                            <h3 class="font-serif mb-1 fs-2">Dian & Budi</h3>
                            <p class="small opacity-90 mb-3">Minggu, 25 Oktober 2025</p>
                            <button class="btn btn-sm btn-light text-danger fw-bold rounded-pill px-4 shadow-sm"
                                    style="color: var(--brand-color)!important;">
                                Buka Undangan
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: TENTANG KAMI (NEW) -->
<section class="py-5" id="tentang">
    <div class="container py-5">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <span class="text-uppercase fw-bold small text-muted tracking-wider">Tentang Kami</span>
                <h2 class="fw-bold display-6 mt-2 mb-4">Solusi Undangan Masa Kini</h2>
                <p class="text-secondary lead">
                    Niema Digital adalah penyedia jasa pembuatan undangan pernikahan berbasis website yang berbasis di
                    Medan. Sejak 2023, kami telah membantu lebih dari 1.200 pasangan di seluruh Indonesia merayakan
                    momen spesial mereka dengan cara yang lebih hemat, praktis, dan ramah lingkungan. Misi kami
                    sederhana: <strong class="text-dark">Membuat hari bahagiamu bebas ribet.</strong>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES -->
<section class="py-5 bg-light" id="fitur">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6 mt-2">Fitur Lengkap, Hasil Memikat</h2>
            <p class="text-secondary w-md-75 mx-auto">Kami memastikan undangan Anda terlihat profesional dan berfungsi
                sempurna.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-6 col-lg-4">
                <div class="card-feature h-100">
                    <div class="icon-circle"><i data-lucide="smartphone" size="28"></i></div>
                    <h4 class="h5 fw-bold mb-3">Tampilan Responsif</h4>
                    <p class="text-secondary small mb-0">Desain otomatis menyesuaikan layar HP, Tablet, dan Laptop.
                        Dijamin rapi dan enak dibaca tamu.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature h-100">
                    <div class="icon-circle"><i data-lucide="send" size="28"></i></div>
                    <h4 class="h5 fw-bold mb-3">Sebar via WhatsApp</h4>
                    <p class="text-secondary small mb-0">Fitur kirim undangan otomatis dengan menyebut nama tamu
                        (Auto-mention). Lebih personal & sopan.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature h-100">
                    <div class="icon-circle"><i data-lucide="map-pin" size="28"></i></div>
                    <h4 class="h5 fw-bold mb-3">Peta Lokasi Akurat</h4>
                    <p class="text-secondary small mb-0">Terintegrasi Google Maps. Pastikan tamu sampai di lokasi acara
                        tanpa tersasar.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature h-100">
                    <div class="icon-circle"><i data-lucide="music" size="28"></i></div>
                    <h4 class="h5 fw-bold mb-3">Bebas Pilih Musik</h4>
                    <p class="text-secondary small mb-0">Hidupkan suasana dengan lagu latar favoritmu. Mulai dari pop,
                        jazz, hingga akustik.</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature h-100">
                    <div class="icon-circle"><i data-lucide="credit-card" size="28"></i></div>
                    <h4 class="h5 fw-bold mb-3">Amplop Digital</h4>
                    <p class="text-secondary small mb-0">Solusi praktis terima hadiah atau angpao via transfer bank &
                        E-Wallet (QRIS).</p>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="card-feature h-100">
                    <div class="icon-circle"><i data-lucide="infinity" size="28"></i></div>
                    <h4 class="h5 fw-bold mb-3">Revisi Tanpa Batas</h4>
                    <p class="text-secondary small mb-0">Typo nama atau ganti jam? Tenang, Anda bisa edit data kapan
                        saja sepuasnya.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: CARA KERJA (NEW) -->
<section class="py-5" id="cara-kerja">
    <div class="container py-5">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold small text-muted tracking-wider">Mudah & Cepat</span>
            <h2 class="fw-bold display-6 mt-2">Cara Pemesanan</h2>
        </div>
        <div class="row g-4 justify-content-center">
            <div class="col-md-3 col-6">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h5 class="fw-bold h6">Pilih Tema</h5>
                    <p class="small text-muted">Pilih desain favorit dari katalog kami.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h5 class="fw-bold h6">Kirim Data</h5>
                    <p class="small text-muted">Isi formulir data mempelai & acara.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h5 class="fw-bold h6">Proses & Revisi</h5>
                    <p class="small text-muted">Kami kerjakan (1-2 hari). Revisi jika perlu.</p>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="step-card">
                    <div class="step-number">4</div>
                    <h5 class="fw-bold h6">Siap Sebar</h5>
                    <p class="small text-muted">Link undangan jadi dan siap dibagikan!</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: PORTFOLIO & TEMA -->
<section class="py-5 bg-light" id="tema">
    <div class="container py-5">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-5">
            <div class="text-center text-md-start mb-4 mb-md-0">
                <h2 class="fw-bold display-6 mb-2">Contoh Undangan / Portfolio</h2>
                <p class="text-secondary mb-0">Lihat hasil karya yang telah kami buat untuk klien.</p>
            </div>
            <a href="#" class="btn btn-outline-brand rounded-pill">Lihat Katalog Lengkap</a>
        </div>

        <div class="row g-4">
            <!-- Portfolio Item 1 -->
            <div class="col-md-6 col-lg-4">
                <div class="theme-card bg-white h-100">
                    <div class="theme-placeholder">
                        <span class="theme-badge">Real Client</span>
                        <div class="text-center">
                            <i data-lucide="image" size="48" class="mb-2 opacity-50"></i>
                            <p class="small m-0">Pernikahan Rizky & Putri</p>
                        </div>
                    </div>
                    <div class="p-3">
                        <h5 class="fw-bold h6">Theme: Romantic Floral</h5>
                        <a href="#" class="small text-decoration-none text-muted stretched-link">Lihat Preview <i
                                data-lucide="external-link" size="12" class="ms-1"></i></a>
                    </div>
                </div>
            </div>
            <!-- Portfolio Item 2 -->
            <div class="col-md-6 col-lg-4">
                <div class="theme-card bg-white h-100">
                    <div class="theme-placeholder">
                        <span class="theme-badge">Best Seller</span>
                        <div class="text-center">
                            <i data-lucide="image" size="48" class="mb-2 opacity-50"></i>
                            <p class="small m-0">Pernikahan Andi & Sari</p>
                        </div>
                    </div>
                    <div class="p-3">
                        <h5 class="fw-bold h6">Theme: Clean Minimalist</h5>
                        <a href="#" class="small text-decoration-none text-muted stretched-link">Lihat Preview <i
                                data-lucide="external-link" size="12" class="ms-1"></i></a>
                    </div>
                </div>
            </div>
            <!-- Portfolio Item 3 -->
            <div class="col-md-6 col-lg-4">
                <div class="theme-card bg-white h-100">
                    <div class="theme-placeholder">
                        <span class="theme-badge">New</span>
                        <div class="text-center">
                            <i data-lucide="image" size="48" class="mb-2 opacity-50"></i>
                            <p class="small m-0">Pernikahan Budi & Citra</p>
                        </div>
                    </div>
                    <div class="p-3">
                        <h5 class="fw-bold h6">Theme: Modern Rustic</h5>
                        <a href="#" class="small text-decoration-none text-muted stretched-link">Lihat Preview <i
                                data-lucide="external-link" size="12" class="ms-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SECTION: TESTIMONI (NEW) -->
<section class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Kata Mereka</h2>
            <p class="text-secondary">Apa kata pasangan yang sudah menggunakan Niema Digital?</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <!-- Avatar Placeholder -->
                        <div class="avatar-small bg-secondary me-3"></div>
                        <div>
                            <h6 class="fw-bold mb-0">Sarah & Dimas</h6>
                            <small class="text-muted text-warning">★★★★★</small>
                        </div>
                    </div>
                    <p class="text-secondary small fst-italic">"Adminnya ramah banget, prosesnya cepet cuma sehari udah
                        jadi. Temanya juga cantik-cantik. Recommended banget buat yang mau nikah!"</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-small bg-secondary me-3"></div>
                        <div>
                            <h6 class="fw-bold mb-0">Rina & Bayu</h6>
                            <small class="text-muted text-warning">★★★★★</small>
                        </div>
                    </div>
                    <p class="text-secondary small fst-italic">"Harganya murah tapi kualitasnya nggak murahan. Fitur
                        kirim WA otomatisnya ngebantu banget, jadi nggak capek ngetik satu-satu."</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="testimonial-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar-small bg-secondary me-3"></div>
                        <div>
                            <h6 class="fw-bold mb-0">Putri & Rizky</h6>
                            <small class="text-muted text-warning">★★★★★</small>
                        </div>
                    </div>
                    <p class="text-secondary small fst-italic">"Suka banget sama fitur peta lokasinya, akurat! Tamu-tamu
                        jadi gampang nemu gedung resepsinya. Makasih Niema!"</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- PRICING SECTION -->
<section class="pricing-section" id="harga">
    <div class="container">
        <div class="text-center mb-5">
            <span class="text-uppercase fw-bold small text-muted tracking-wider">Penawaran Spesial</span>
            <h2 class="fw-bold display-6 mt-2">Investasi Hemat untuk Momen Terindah</h2>
            <p class="text-secondary">Bayar sekali, aktif selamanya. Transparan tanpa biaya tersembunyi.</p>
        </div>

        <div class="row g-4 justify-content-center align-items-center">

            <!-- Paket Hemat -->
            <div class="col-md-6 col-lg-4">
                <div class="pricing-card text-center">
                    <h5 class="fw-bold text-muted text-uppercase tracking-wider fs-6">Paket Hemat</h5>
                    <div class="my-4">
                        <div class="price-strike">Rp 300.000</div>
                        <div class="price-main">Rp 114rb</div>
                        <p class="text-muted small">Solusi budget bersahabat</p>
                    </div>
                    <hr class="border-light my-4 opacity-50">
                    <ul class="list-unstyled text-start small mb-4 d-inline-block mx-auto w-100">
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check" size="18"
                                                                      class="text-success me-2"></i> <span>Akses Tema Premium</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check" size="18"
                                                                      class="text-success me-2"></i> <span>Sebar WA Otomatis</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check" size="18"
                                                                      class="text-success me-2"></i> <span>Peta & Musik Latar</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check" size="18"
                                                                      class="text-success me-2"></i> <span>Amplop Digital</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check" size="18"
                                                                      class="text-success me-2"></i> <span>Masa Aktif 1 Tahun</span>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-outline-brand w-100 rounded-pill py-2 fw-bold">Pilih Paket Hemat</a>
                </div>
            </div>

            <!-- Paket Sultan -->
            <div class="col-md-6 col-lg-4">
                <div class="pricing-card popular text-center">
                    <div class="badge-popular">PALING LENGKAP</div>
                    <h5 class="fw-bold mt-4 text-uppercase tracking-wider fs-6" style="color: var(--brand-color);">Paket
                        Sultan</h5>
                    <div class="my-4">
                        <div class="price-strike">Rp 500.000</div>
                        <div class="price-main">Rp 300rb</div>
                        <p class="text-muted small">Fitur maksimal + Buku Tamu</p>
                    </div>
                    <hr class="border-danger-subtle my-4 opacity-50">
                    <ul class="list-unstyled text-start small mb-4 d-inline-block mx-auto w-100">
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check-circle" size="18"
                                                                      class="text-success me-2"></i> <strong>Semua Fitur
                                Paket Hemat</strong></li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check-circle" size="18"
                                                                      class="text-success me-2"></i> <strong>Buku Tamu
                                Digital (QR Code)</strong></li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check-circle" size="18"
                                                                      class="text-success me-2"></i> <strong>Sistem RSVP
                                Real-time</strong></li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check-circle" size="18"
                                                                      class="text-success me-2"></i> <span>Layar Sapa Tamu (Check-in)</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check-circle" size="18"
                                                                      class="text-success me-2"></i> <span>Export Data Tamu (Excel)</span>
                        </li>
                        <li class="mb-3 d-flex align-items-center"><i data-lucide="check-circle" size="18"
                                                                      class="text-success me-2"></i> <span>Prioritas Support Admin</span>
                        </li>
                    </ul>
                    <a href="#" class="btn btn-brand w-100 rounded-pill py-3 fw-bold shadow-sm">Ambil Paket Sultan</a>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- SECTION: FAQ (NEW) -->
<section class="py-5">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-bold display-6">Sering Ditanyakan (FAQ)</h2>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion" id="faqAccordion">
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq1">
                                Berapa lama proses pengerjaannya?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Pengerjaan normal memakan waktu 1x24 jam setelah data lengkap kami terima. Untuk paket
                                Sultan (Prioritas), bisa selesai lebih cepat dalam hitungan jam.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq2">
                                Apakah bisa revisi jika ada kesalahan data?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Tentu saja! Kami memberikan garansi revisi tanpa batas untuk data teks (nama, tanggal,
                                lokasi, dll) sampai hari H acara.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq3">
                                Apakah bisa ganti tema setelah jadi?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Mohon maaf, tema yang sudah dipilih dan diproses tidak bisa diganti. Namun, Anda bisa
                                melihat preview tema secara detail sebelum memutuskan pilihan.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item border-0 mb-3 shadow-sm rounded overflow-hidden">
                        <h2 class="accordion-header">
                            <button class="accordion-button collapsed fw-bold" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faq4">
                                Sampai kapan link undangan aktif?
                            </button>
                        </h2>
                        <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary">
                                Masa aktif undangan adalah 1 tahun (12 bulan) sejak undangan diterbitkan. Cukup panjang
                                untuk kenang-kenangan digital Anda.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER + CTA -->
<footer>
    <!-- Separate CTA Section Overlapping Footer -->
    <div class="container">
        <div class="cta-section text-center">
            <h2 class="fw-bold mb-3">Siap bikin undanganmu hari ini?</h2>
            <p class="mb-4 opacity-75">Jangan tunda lagi, amankan slot pembuatan undanganmu sekarang juga. Proses cepat,
                hasil memikat.</p>
            <a href="#harga" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-danger shadow-sm">Pesan
                Sekarang</a>
        </div>
    </div>

    <!-- Main Footer Content -->
    <div class="container mt-5 pt-5">
        <div class="row gy-5 mb-5">
            <div class="col-lg-4">
                <a class="navbar-brand d-inline-block mb-4" href="#" aria-label="Niema Digital Home">
                    <div class="d-flex flex-column">
                        <span class="logo-text fs-2">Niema</span>
                        <span class="logo-sub text-secondary">DIGITAL INVITATION</span>
                    </div>
                </a>
                <p class="text-secondary mb-4">
                    Kami membantu Anda membagikan kabar bahagia dengan cara yang lebih berkesan, hemat, dan efisien.
                    Solusi terbaik untuk pernikahan modern.
                </p>
                <div class="d-flex align-items-center gap-2 text-secondary fw-medium">
                    <i data-lucide="map-pin" size="18" class="text-danger"></i>
                    <span>Berbasis di Medan, Melayani Nasional</span>
                </div>
            </div>
            <div class="col-6 col-lg-2 offset-lg-2">
                <h6>Menu</h6>
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li><a href="#">Beranda</a></li>
                    <li><a href="#">Fitur Unggulan</a></li>
                    <li><a href="#">Katalog Tema</a></li>
                    <li><a href="#">Cek Harga</a></li>
                </ul>
            </div>
            <div class="col-6 col-lg-2">
                <h6>Bantuan</h6>
                <ul class="list-unstyled d-flex flex-column gap-3">
                    <li><a href="#">Cara Pemesanan</a></li>
                    <li><a href="#">Tanya Jawab (FAQ)</a></li>
                    <li><a href="#">Syarat Layanan</a></li>
                    <li><a href="#">Hubungi Admin</a></li>
                </ul>
            </div>
            <div class="col-lg-2">
                <h6>Ikuti Kami</h6>
                <div class="d-flex gap-3 mt-3">
                    <a href="#" class="social-icon" aria-label="Instagram"><i data-lucide="instagram" size="24"></i></a>
                    <a href="#" class="social-icon" aria-label="Facebook"><i data-lucide="facebook" size="24"></i></a>
                    <a href="#" class="social-icon" aria-label="Twitter"><i data-lucide="twitter" size="24"></i></a>
                </div>
            </div>
        </div>
        <div class="border-top pt-4 text-center">
            <p class="small text-muted mb-0">&copy; 2024 Niema Digital. Dibuat dengan <span
                    style="color: var(--brand-color);">❤</span> untuk Indonesia.</p>
        </div>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    lucide.createIcons();
    const oppa = () => {
        console.log('acd');
    };
    oppa();

    const a = 1;
    const b = 2;
</script>
</body>
</html>
