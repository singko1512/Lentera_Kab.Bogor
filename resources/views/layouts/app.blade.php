<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'LENTERA Kab Bogor')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_lentera.png') }}?v=2">

    <!-- Font dari project asli -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Dependencies -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Variabel CSS Original dari project SIMALAM */
        :root {
            --primary: #115cb9;
            --primary-light: #3b82f6;
            --primary-dark: #1f477b;
            --dark: #1a1a2e;
            --text: #2d3436;
            --text-muted: #636e72;
            --text-light: #b2bec3;
            --bg: #f7f8fc;
            --white: #ffffff;
            --border: #eef0f6;
            --green: #00b894;
            --rose: #e17055;
            --amber: #fdcb6e;
            --font: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* Styling Navbar Dummy menyerupai aslinya */
        .site-nav {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 0.9rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            text-decoration: none;
            color: var(--dark);
        }

        .nav-brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1rem;
        }

        .nav-brand-text strong {
            display: block;
            font-size: 0.95rem;
            font-weight: 700;
            line-height: 1.2;
            color: var(--dark);
        }

        .nav-brand-text span {
            display: block;
            font-size: 0.72rem;
            color: var(--text-muted);
            font-weight: 500;
        }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="site-nav">
        <div class="container">
            <a href="{{ route('home') }}" class="nav-brand">
                <img src="{{ asset('assets/certificate/lambang_kabupaten_bogor.png') }}" alt="Logo Tegar Beriman" style="height: 42px; width: auto; object-fit: contain; margin-right: 0.5rem;">
                <div class="nav-brand-text">
                    <strong>LENTERA Kab Bogor</strong>
                    <span>Layanan Integrasi Izin Riset dan Magang Kabupaten Bogor</span>
                </div>
            </a>
        </div>
    </nav>

    <main class="flex-grow-1 d-flex flex-column">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
