<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Admin Dashboard - Kesbangpol Kab. Bogor</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#115cb9",
                        "primary-container": "#e0e7ff",
                        secondary: "#1f477b",
                        "secondary-container": "#f3f4f6",
                        background: "#f8f9fc",
                        surface: "#ffffff",
                        "surface-container-lowest": "#ffffff",
                        "surface-container-low": "#f8f9fc",
                        "surface-container": "#f1f5f9",
                        "surface-container-high": "#e2e8f0",
                        error: "#ef4444",
                        "error-container": "#fee2e2",
                        "on-background": "#1e293b",
                        "on-surface": "#0f172a",
                        "on-surface-variant": "#64748b",
                        outline: "#cbd5e1",
                        "outline-variant": "#e2e8f0"
                    },
                    fontFamily: {
                        "headline-md": ["Plus Jakarta Sans", "sans-serif"],
                        "display-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-md": ["Plus Jakarta Sans", "sans-serif"],
                        "caption": ["Plus Jakarta Sans", "sans-serif"],
                        "title-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "body-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg-mobile": ["Plus Jakarta Sans", "sans-serif"],
                        "headline-lg": ["Plus Jakarta Sans", "sans-serif"],
                        "label-md": ["Plus Jakarta Sans", "sans-serif"]
                    },
                    fontSize: {
                        "headline-md": ["24px", { "lineHeight": "32px", "fontWeight": "600" }],
                        "display-lg": ["48px", { "lineHeight": "60px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-md": ["15px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "caption": ["12px", { "lineHeight": "16px", "fontWeight": "400" }],
                        "title-lg": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-lg": ["18px", { "lineHeight": "28px", "fontWeight": "400" }],
                        "headline-lg-mobile": ["24px", { "lineHeight": "32px", "fontWeight": "700" }],
                        "headline-lg": ["32px", { "lineHeight": "40px", "fontWeight": "700" }],
                        "label-md": ["14px", { "lineHeight": "20px", "fontWeight": "500" }]
                    },
                    boxShadow: {
                        'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                        'hover': '0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    </script>
<style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .icon-filled {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        /* Custom Shadows & Transitions */
        .shadow-level-1 {
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        }
        .shadow-level-2 {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
        .card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-hover:hover {
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            transform: translateY(-4px);
        }
        
        /* Alpine transitions */
        [x-cloak] { display: none !important; }

        /* Fix native select option contrast on hover */
        select option {
            color: initial;
            background-color: initial;
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md antialiased h-screen flex overflow-hidden">
<!-- SideNavBar -->
@include('pelayanan.partials.sidebar_kesbangpol_stitch')
<!-- Main Content Area -->
<main class="flex-1 md:ml-72 flex flex-col h-screen overflow-hidden bg-background">
<!-- TopAppBar -->
<header class="h-16 flex-shrink-0 bg-white border-b border-outline-variant/40 shadow-xs z-40 sticky top-0">
<div class="flex justify-between items-center px-4 md:px-8 w-full h-full">
<div class="flex items-center gap-3">
<button type="button" class="md:hidden text-on-surface hover:text-primary p-1 rounded-lg" onclick="document.querySelector('aside').classList.toggle('hidden')">
    <span class="material-symbols-outlined">menu</span>
</button>
<div class="flex items-center gap-2">
    <span class="material-symbols-outlined text-primary text-[22px]">admin_panel_settings</span>
    <h2 class="text-title-lg font-title-lg font-bold text-on-surface tracking-tight">{{ auth()->user()->name ?? (auth()->user()->dinas->name ?? "Admin") }}</h2>
</div>
</div>
<div class="flex items-center gap-3">
<span class="px-3 py-1 bg-primary/10 text-primary border border-primary/20 rounded-full text-xs font-bold uppercase tracking-wider">
    {{ strtoupper(auth()->user()->role ?? 'ADMIN') }}
</span>
</div>
</div>
</header>
<!-- Scrollable Canvas -->
<div class="flex-1 overflow-y-auto p-6 md:p-8 lg:p-10 bg-background" x-data="{ pageLoad: false }" x-init="setTimeout(() => pageLoad = true, 100)">
    <div x-show="pageLoad" x-transition.opacity.duration.600ms.translate.y.20px x-cloak>
        @yield('content')
    </div>
</div>
</main>
</body>
</html>
