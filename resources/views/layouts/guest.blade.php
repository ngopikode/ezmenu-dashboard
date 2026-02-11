<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'EzMenu Admin') }}</title>

    <!-- Fonts: Inter (Professional UI Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Bootstrap Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Professional UI Overrides -->
    <style>
        :root {
            /* Palette Profesional SaaS (Mirip Linear/Vercel) */
            --ez-primary: #4F46E5; /* Indigo 600 */
            --ez-primary-hover: #4338CA; /* Indigo 700 */
            --ez-bg-base: #F9FAFB; /* Slate 50 */
            --ez-text-main: #111827; /* Slate 900 */
            --ez-text-muted: #6B7280; /* Slate 500 */
        }

        body {
            font-family: 'Inter', sans-serif !important;
            background-color: var(--ez-bg-base);
            color: var(--ez-text-main);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Background Abstract Mesh Gradient */
        .bg-mesh {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
            background-color: #F3F4F6;
            background-image: radial-gradient(at 0% 0%, rgba(79, 70, 229, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 0%, rgba(236, 72, 153, 0.08) 0px, transparent 50%),
            radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.08) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(16, 185, 129, 0.05) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* Card Login: Clean, Floating, Shadow Depth */
        .card-auth {
            border: none !important;
            border-radius: 1.25rem; /* 20px - lebih modern */
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px); /* Efek Glass halus */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02),
            0 10px 15px -3px rgba(0, 0, 0, 0.04),
            0 0 0 1px rgba(0, 0, 0, 0.03); /* Border halus via shadow */
            transition: transform 0.3s ease;
        }

        /* Input Fields: Crisp & Clean */
        .form-control {
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            border: 1px solid #E5E7EB;
            background-color: #fff;
            font-size: 0.95rem;
            transition: all 0.2s ease-in-out;
        }

        .form-control:focus {
            border-color: var(--ez-primary);
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); /* Soft Focus Ring */
            background-color: #fff;
        }

        /* Tombol Utama: Gradient halus & Bold */
        .btn-primary-ez {
            background-color: var(--ez-primary);
            border: 1px solid transparent;
            color: white;
            font-weight: 600;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            letter-spacing: -0.01em;
            transition: all 0.2s;
        }

        .btn-primary-ez:hover {
            background-color: var(--ez-primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
            color: white;
        }

        .btn-primary-ez:active {
            transform: translateY(0);
        }

        /* Social Buttons: Outline Style */
        .btn-outline-secondary {
            border-color: #E5E7EB;
            color: var(--ez-text-main);
            background: #fff;
            font-weight: 500;
            border-radius: 0.75rem;
        }

        .btn-outline-secondary:hover {
            background-color: #F9FAFB;
            border-color: #D1D5DB;
            color: #000;
        }

        /* Labels */
        .form-floating > label {
            padding-left: 1rem;
            color: var(--ez-text-muted);
            font-size: 0.9rem;
        }

        /* Logo Container */
        .brand-logo-container {
            width: 56px;
            height: 56px;
            background: linear-gradient(135deg, var(--ez-primary), #ec4899);
            color: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
            margin-bottom: 1.5rem;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">

<!-- Background Element -->
<div class="bg-mesh"></div>

<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5 px-3">

    <!-- Header Branding (Terpisah dari card agar lebih elegan) -->
    <div class="text-center mb-4 d-flex flex-column align-items-center">
        <div class="brand-logo-container">
            <i class="bi bi-layers-fill fs-3"></i>
        </div>
        <h4 class="fw-bold m-0 text-dark tracking-tight" style="letter-spacing: -0.03em;">EzMenu System</h4>
        <p class="text-muted small mt-1">Platform Manajemen Restoran Terintegrasi</p>
    </div>

    <!-- Slot Konten Utama (Login Form) -->
    <div class="w-100" style="max-width: 440px;">
        {{ $slot }}
    </div>

    <!-- Footer yang menyatu dengan background -->
    <div class="mt-5 text-center">
        <p class="text-muted small mb-0 opacity-75">
            &copy; {{ date('Y') }} EzMenu Ecosystem. <br>
            <span class="fw-medium">Privacy Policy</span> &bull; <span class="fw-medium">Terms of Service</span>
        </p>
    </div>

</div>
</body>
</html>
