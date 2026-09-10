<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LENTERA Kab Bogor - Layanan Integrasi Izin Riset dan Magang Kabupaten Bogor')</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/images/logo_lentera.png') }}?v=2">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        html.dark {
            --dark: #f8f9ff;
            --text: #eaf1ff;
            --text-muted: #a7c8ff;
            --text-light: #43474f;
            --bg: #0b1c30;
            --white: #001e40;
            --border: #1f477b;

            /* Tailwind Dark Mode Overrides */
            --tw-primary: #a7c8ff;
            --tw-background: #0b1c30;
            --tw-on-surface: #f8f9ff;
            --tw-on-surface-variant: #a7c8ff;
            --tw-surface-container-lowest: #0c1f36;
            --tw-surface-container-low: #0f243d;
            --tw-surface-container: #152d4b;
            --tw-surface-variant: #1f477b;
            --tw-outline-variant: #3e5066;
        }

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

            /* Tailwind Custom Colors mapping */
            --tw-primary: #001e40;
            --tw-background: #f8f9ff;
            --tw-on-surface: #0b1c30;
            --tw-on-surface-variant: #43474f;
            --tw-surface-container-lowest: #ffffff;
            --tw-surface-container-low: #eff4ff;
            --tw-surface-container: #e5eeff;
            --tw-surface-variant: #d3e4fe;
            --tw-outline-variant: #c3c6d1;
        }

        /* Smooth transition for theme switching */
        *, *::before, *::after {
            transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                        border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                        color 0.3s cubic-bezier(0.4, 0, 0.2, 1), 
                        box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--font);
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        /* ── Navbar ── */
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

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .nav-links a {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.5rem 0.9rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: var(--primary);
            background: rgba(17, 92, 185, 0.06);
        }

        /* ── Glass Cards ── */
        .glass-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.03);
            transition: all 0.3s cubic-bezier(0.25, 1, 0.5, 1);
        }

        .glass-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        /* ── Buttons ── */
        .btn-premium-primary {
            background: linear-gradient(135deg, var(--primary) 0%, #7c6cf0 100%);
            border: none;
            color: #fff;
            font-weight: 600;
            border-radius: 14px;
            padding: 0.8rem 1.8rem;
            box-shadow: 0 4px 14px rgba(17, 92, 185, 0.25);
            transition: all 0.25s ease;
        }

        .btn-premium-primary:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            box-shadow: 0 6px 20px rgba(17, 92, 185, 0.35);
            transform: translateY(-1px);
            color: #fff;
        }

        .btn-premium-secondary {
            background: var(--white);
            border: 1px solid var(--border);
            color: var(--text);
            font-weight: 600;
            border-radius: 14px;
            padding: 0.8rem 1.8rem;
            transition: all 0.25s ease;
        }

        .btn-premium-secondary:hover {
            border-color: #d1d5db;
            background: #fafbff;
            transform: translateY(-1px);
        }

        /* ── Form Inputs ── */
        .form-control-premium,
        .form-select-premium {
            border: 1px solid var(--border);
            background: var(--white);
            border-radius: 14px;
            padding: 0.85rem 1.1rem;
            font-size: 0.92rem;
            color: var(--text);
            transition: all 0.2s ease;
        }

        .form-control-premium:focus,
        .form-select-premium:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(17, 92, 185, 0.1);
            outline: none;
        }

        .form-label-premium {
            font-weight: 600;
            font-size: 0.88rem;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }

        /* ── Badges ── */
        .header-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            padding: 0.4rem 1rem;
            border-radius: 100px;
            border: 1px solid var(--border);
            background: var(--white);
            margin-bottom: 1.25rem;
        }

        .badge-status {
            padding: 0.35em 0.75em;
            font-size: 0.72em;
            font-weight: 700;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .badge-hadir { background: rgba(0,184,148,0.1); color: var(--green); }
        .badge-wfh { background: rgba(17, 92, 185,0.1); color: var(--primary); }
        .badge-sakit { background: rgba(225,112,85,0.1); color: var(--rose); }
        .badge-izin { background: rgba(253,203,110,0.15); color: #e17055; }

        /* ── Footer ── */
        footer {
            margin-top: auto;
            padding: 1.5rem 0;
            text-align: center;
            font-size: 0.8rem;
            color: var(--text-light);
            border-top: 1px solid var(--border);
        }
    </style>
    @yield('styles')
</head>
<body>

    @include('pelayanan.partials.navbar')

    <!-- Main -->
    <main class="flex-grow-1 d-flex flex-column">
        @yield('content')
    </main>

    @include('pelayanan.partials.footer')



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function installInspectGuards() {
            document.addEventListener('contextmenu', function(e) {
                e.preventDefault();
            });

            document.addEventListener('keydown', function(e) {
                const key = e.key.toLowerCase();
                const blocked =
                    e.key === 'F12' ||
                    (e.ctrlKey && e.shiftKey && ['i', 'j', 'c'].includes(key)) ||
                    (e.ctrlKey && ['u', 's'].includes(key));

                if (blocked) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }

        installInspectGuards();

        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                confirmButtonColor: '#115cb9',
                customClass: { popup: 'rounded-4 border-0 shadow-lg', confirmButton: 'rounded-3 px-4' }
            });
        @endif
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Dikirim!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#115cb9',
                customClass: { popup: 'rounded-4 border-0 shadow-lg', confirmButton: 'rounded-3 px-4 fw-bold' }
            });
        @endif
        @if (session('success_swal'))
            Swal.fire({
                toast: true,
                position: 'top',
                icon: 'success',
                title: "{{ session('success_swal') }}",
                showConfirmButton: false,
                timer: 3000,
                customClass: { popup: 'rounded-3 shadow-sm border' }
            });
        @endif
        @if (session('error_swal') && !Route::is('home'))
            Swal.fire({ icon:'error', title:'Gagal', text:"{{ session('error_swal') }}", confirmButtonColor:'#115cb9', customClass:{popup:'rounded-4 border-0 shadow-lg', confirmButton:'rounded-3 px-4'} });
        @endif
        @if (session('warning_swal'))
            Swal.fire({ icon:'warning', title:'Perhatian', text:"{{ session('warning_swal') }}", confirmButtonColor:'#115cb9', customClass:{popup:'rounded-4 border-0 shadow-lg', confirmButton:'rounded-3 px-4'} });
        @endif
        @if (session('error'))
            Swal.fire({ icon:'warning', title:'Perhatian', text:"{{ session('error') }}", confirmButtonColor:'#115cb9', customClass:{popup:'rounded-4 border-0 shadow-lg', confirmButton:'rounded-3 px-4'} });
        @endif

        // Global CSRF Token Keep-Alive & Auto-Sync
        function updateCsrfTokens(token) {
            if (!token) return;
            const meta = document.querySelector('meta[name="csrf-token"]');
            if (meta) meta.setAttribute('content', token);
            document.querySelectorAll('input[name="_token"]').forEach(input => {
                input.value = token;
            });
        }

        async function syncCsrfToken() {
            try {
                const res = await fetch("{{ route('refresh.csrf') }}", {
                    headers: { 'Accept': 'application/json' }
                });
                if (res.ok) {
                    const data = await res.json();
                    if (data && data.csrf_token) {
                        updateCsrfTokens(data.csrf_token);
                    }
                }
            } catch (e) {
                // Silently handle offline/temporary network hiccups
            }
        }

        // Periodic sync every 10 minutes
        setInterval(syncCsrfToken, 10 * 60 * 1000);

        // Sync token whenever user switches back to this tab
        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'visible') {
                syncCsrfToken();
            }
        });
        window.addEventListener('focus', syncCsrfToken);
    </script>
    @yield('scripts')
</body>
</html>

