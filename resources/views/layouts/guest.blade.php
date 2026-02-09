<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Niema Digital') }}</title>

    <!-- Fonts (Sama persis dengan Landing Page) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap"
        rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<!-- Background Gradient Halus -->
<body style="background: linear-gradient(135deg, #fff 0%, var(--brand-light) 100%); min-height: 100vh;">

<div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5 px-3">

    <!-- Logo Section -->
    <div class="text-center mb-4">
        <a href="/" wire:navigate class="text-decoration-none">
            <div class="d-flex flex-column align-items-center">
                <!-- Logo Text: Great Vibes -->
                <span style="font-family: 'Great Vibes', cursive; font-size: 3rem; color: #F50057; line-height: 0.8;">
                            Niema
                        </span>
                <!-- Sub Logo: Poppins -->
                <span
                    style="font-family: 'Poppins', sans-serif; font-size: 0.75rem; letter-spacing: 4px; font-weight: 700; color: #F50057; margin-left: 4px; text-transform: uppercase;">
                            DIGITAL
                        </span>
            </div>
        </a>
    </div>

    <!-- Card Container -->
    <div class="w-100" style="max-width: 420px;">
        {{ $slot }}
    </div>

    <!-- Footer Copyright -->
    <div class="mt-4 text-muted small text-center opacity-75">
        &copy; {{ date('Y') }} Niema Digital. All rights reserved.
    </div>

</div>
</body>
</html>
