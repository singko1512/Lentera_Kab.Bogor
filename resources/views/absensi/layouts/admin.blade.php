<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'LENTERA - Dashboard Admin')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #115cb9;
            --primary-light: #3b82f6;
            --primary-dark: #1f477b;
            --green: #10b981;
            --red: #ef4444;
            --dark: #1e293b;
            --text: #334155;
            --text-muted: #64748b;
            --text-light: #94a3b8;
            --bg: #f0f4f8;
            --white: #ffffff;
            --border: #e2e8f0;
            --table-head: #f1f5f9;
            --font: 'Plus Jakarta Sans', -apple-system, sans-serif;
        }

        * { box-sizing: border-box; }

        body {
            font-family: var(--font);
            background: linear-gradient(180deg, #eff6ff 0%, var(--bg) 40%, #f8fafc 100%);
            color: var(--text);
            min-height: 100vh;
            margin: 0;
        }

        .admin-wrap {
            width: min(100% - 2rem, 1440px);
            max-width: 1440px;
            margin: 0 auto;
            padding: 2rem 0 2.75rem;
        }

        .admin-logo {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.1rem;
            flex-shrink: 0;
            box-shadow: 0 4px 14px rgba(17, 92, 185, 0.25);
        }

        .admin-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        /* Modern Button Hover & Micro-animations */
        button, .btn, .tab-btn, .btn-logout, .btn-export-excel, .btn-export-pdf, .btn-add, .btn-action {
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        button:active, .btn:active, .tab-btn:active, .btn-logout:active, .btn-export-excel:active, .btn-export-pdf:active, .btn-add:active, .btn-action:active {
            transform: translateY(0) scale(0.96) !important;
        }

        .btn-logout {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.55rem 1.1rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: var(--text);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
        }

        .btn-logout i {
            transition: transform 0.3s ease;
        }

        .btn-logout:hover {
            background: #fff;
            border-color: #cbd5e1;
            color: #ef4444;
            transform: translateY(-2px) scale(1.02);
            box-shadow: 0 6px 18px -2px rgba(239, 68, 68, 0.18);
        }

        .btn-logout:hover i {
            transform: translateX(3px);
        }

        .admin-tabs {
            position: relative;
            display: inline-flex;
            gap: 0.25rem;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            flex-wrap: wrap;
        }

        .tab-indicator {
            position: absolute;
            top: 4px;
            left: 4px;
            height: calc(100% - 8px);
            width: 0;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            border-radius: 11px;
            box-shadow: 0 4px 14px rgba(17, 92, 185, 0.35);
            transition: left 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                        top 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                        width 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                        height 0.38s cubic-bezier(0.34, 1.56, 0.64, 1),
                        opacity 0.2s ease;
            pointer-events: none;
            z-index: 1;
            opacity: 0;
        }

        .admin-tabs .tab-btn {
            position: relative;
            z-index: 2;
            border: none;
            background: transparent !important;
            border-radius: 11px;
            padding: 0.6rem 1.25rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            cursor: pointer;
            transition: color 0.3s ease, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) !important;
        }

        .admin-tabs .tab-btn i {
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .admin-tabs .tab-btn:hover i {
            transform: translateY(-1px) scale(1.18);
        }

        .admin-tabs .tab-btn.active {
            color: #fff !important;
        }

        .admin-tabs .tab-btn:not(.active):hover {
            color: var(--primary);
            transform: translateY(-1.5px) scale(1.02);
        }

        .filter-label {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
        }

        .filter-select {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.55rem 2rem 0.55rem 0.85rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--dark);
            background: var(--white);
            min-width: 130px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            transition: all 0.25s ease;
        }

        .filter-select:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
            transform: translateY(-1px);
        }

        .filter-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(17, 92, 185, 0.15);
        }

        .btn-export-excel {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.64rem 1.2rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(16, 185, 129, 0.25);
        }

        .btn-export-excel i {
            transition: transform 0.3s ease;
        }

        .btn-export-excel:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            color: #fff;
            transform: translateY(-2.5px) scale(1.03);
            box-shadow: 0 8px 22px -2px rgba(16, 185, 129, 0.45);
        }

        .btn-export-excel:hover i {
            transform: scale(1.22) rotate(-8deg);
        }

        .btn-export-pdf {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.64rem 1.2rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            box-shadow: 0 3px 10px rgba(239, 68, 68, 0.25);
        }

        .btn-export-pdf i {
            transition: transform 0.3s ease;
        }

        .btn-export-pdf:hover {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: #fff;
            transform: translateY(-2.5px) scale(1.03);
            box-shadow: 0 8px 22px -2px rgba(239, 68, 68, 0.45);
        }

        .btn-export-pdf:hover i {
            transform: scale(1.22) rotate(8deg);
        }

        .search-input {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.65rem 1rem 0.65rem 2.5rem;
            font-size: 0.88rem;
            width: 100%;
            background: var(--white);
            transition: all 0.2s;
        }

        .search-input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(17, 92, 185, 0.12);
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap i {
            position: absolute;
            left: 0.9rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .status-select {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.65rem 2rem 0.65rem 0.85rem;
            font-size: 0.88rem;
            font-weight: 500;
            color: var(--dark);
            background: var(--white);
            min-width: 150px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
        }

        .status-select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(17, 92, 185, 0.12);
        }

        .data-table {
            width: 100%;
            min-width: 980px;
            border-collapse: collapse;
        }

        .data-table thead th {
            background: var(--table-head);
            color: var(--text-muted);
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 0.78rem 1rem;
            border: none;
            text-align: left;
            white-space: nowrap;
        }

        .data-table tbody td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border);
            font-size: 0.88rem;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .data-table tbody tr:hover {
            background: #fafbff;
        }

        .badge-status {
            display: inline-block;
            padding: 0.3em 0.75em;
            font-size: 0.72rem;
            font-weight: 700;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-hadir { background: rgba(16, 185, 129, 0.12); color: #059669; }
        .badge-wfh { background: rgba(17, 92, 185, 0.12); color: var(--primary); }
        .badge-sakit { background: rgba(239, 68, 68, 0.12); color: var(--red); }
        .badge-izin { background: rgba(245, 158, 11, 0.15); color: #d97706; }

        .empty-state {
            text-align: center;
            padding: 3.5rem 1.5rem;
        }

        .empty-state h6 {
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.35rem;
        }

        .empty-state p {
            color: var(--text-muted);
            font-size: 0.85rem;
            margin: 0;
        }

        .attachment-thumb {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 1px solid var(--border);
        }

        .attachment-link {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--primary);
            text-decoration: none;
        }

        .attachment-link:hover { color: var(--primary-dark); }

        .btn-add {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.15rem;
            background: linear-gradient(135deg, var(--primary) 0%, #3b82f6 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 3px 10px rgba(17, 92, 185, 0.25);
            text-decoration: none;
        }

        .btn-add i {
            transition: transform 0.3s ease;
        }

        .btn-add:hover {
            background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
            color: #fff;
            transform: translateY(-2.5px) scale(1.02);
            box-shadow: 0 8px 22px -2px rgba(17, 92, 185, 0.45);
        }

        .btn-add:hover i {
            transform: scale(1.22) rotate(90deg);
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--white);
            color: var(--text-muted);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }

        .btn-action i {
            transition: transform 0.25s ease;
        }

        .btn-action:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: rgba(17, 92, 185, 0.3);
            transform: translateY(-2px) scale(1.08);
            box-shadow: 0 4px 12px rgba(17, 92, 185, 0.18);
        }

        .btn-action:hover i {
            transform: scale(1.15);
        }

        .btn-action.danger:hover {
            background: #fef2f2;
            color: var(--red);
            border-color: #fecaca;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.22);
        }

        .modal-clean .modal-content {
            border-radius: 12px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        }

        .form-control-admin {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0.75rem 1rem;
            font-size: 0.88rem;
        }

        .form-control-admin:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(17, 92, 185, 0.12);
            outline: none;
        }

        .form-label-admin {
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.4rem;
        }

        .admin-footer {
            width: min(100% - 2rem, 1440px);
            max-width: 1440px;
            margin: 0 auto;
            padding: 1.25rem 0 1.75rem;
            border-top: 1px solid var(--border);
            color: var(--text-light);
            font-size: 0.78rem;
            text-align: center;
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')

    <footer class="admin-footer">
        &copy; {{ date('Y') }} LENTERA &middot; Layanan Integrasi Izin Riset dan Magang
    </footer>

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
        @if (session('success_swal'))
            Swal.fire({ icon:'success', title:'Berhasil', text:"{{ session('success_swal') }}", confirmButtonColor:'#115cb9', timer:3000, customClass:{popup:'rounded-4 border-0 shadow-lg', confirmButton:'rounded-3 px-4'} });
        @endif
        @if (session('error_swal'))
            Swal.fire({ icon:'error', title:'Gagal', text:"{{ session('error_swal') }}", confirmButtonColor:'#115cb9', customClass:{popup:'rounded-4 border-0 shadow-lg', confirmButton:'rounded-3 px-4'} });
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
