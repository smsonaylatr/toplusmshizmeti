<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Admin' }} — TopluSMS Admin</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        // ═══ Theme Persistence — works with Livewire wire:navigate ═══
        (function() {
            var isLight = localStorage.getItem('admin-theme') === 'light';

            // Apply immediately to prevent FOUC
            function applyTheme() {
                if (localStorage.getItem('admin-theme') === 'light') {
                    document.documentElement.classList.add('light-mode');
                } else {
                    document.documentElement.classList.remove('light-mode');
                }
            }
            applyTheme();

            // Re-apply after Livewire navigation (wire:navigate swaps the page)
            document.addEventListener('livewire:navigated', applyTheme);
            document.addEventListener('livewire:navigating', function() {
                // Store current state before navigation
                window.__themeBeforeNav = localStorage.getItem('admin-theme');
            });

            // Alpine store registration
            document.addEventListener('alpine:init', function() {
                if (Alpine.store('theme')) return; // prevent duplicate registration
                Alpine.store('theme', {
                    dark: localStorage.getItem('admin-theme') !== 'light',
                    toggle: function() {
                        this.dark = !this.dark;
                        localStorage.setItem('admin-theme', this.dark ? 'dark' : 'light');
                        document.documentElement.classList.toggle('light-mode', !this.dark);
                    }
                });
            });
        })();
    </script>

    <style>
        [x-cloak] { display: none !important; }

        /* ═══════════════════════════════════════
           DARK MODE — Default Theme
           ═══════════════════════════════════════ */
        :root {
            --admin-bg: #0f172a;
            --admin-surface: #1e293b;
            --admin-card: #1e293b;
            --admin-border: rgba(148,163,184,.15);
            --admin-glow: rgba(37,99,235,.08);
            --admin-accent: #2563eb;
            --admin-accent-dark: #1d4ed8;
            --admin-text: #cbd5e1;
            --admin-text-primary: #ffffff;
            --admin-text-secondary: #94a3b8;
            --admin-text-muted: rgba(148,163,184,.5);
            --admin-input-bg: #0f172a;
            --admin-input-text: #e2e8f0;
            --admin-hover-bg: rgba(37,99,235,.08);
            --admin-row-hover: rgba(99,102,241,.03);
            --admin-select-option-bg: #1e293b;
            --admin-sidebar-bg: #1e293b;
            --admin-sidebar-border: rgba(148,163,184,.1);
            --admin-inner-bg: rgba(99,102,241,.04);
            --admin-inner-border: rgba(99,102,241,.08);
            --admin-card-shadow: none;
            --admin-card-hover-shadow: 0 0 20px rgba(37,99,235,.06);
        }

        /* ═══════════════════════════════════════
           LIGHT MODE — Premium Clean Theme
           ═══════════════════════════════════════ */
        .light-mode {
            --admin-bg: #f0f4f8;
            --admin-surface: #ffffff;
            --admin-card: #ffffff;
            --admin-border: rgba(0,0,0,.08);
            --admin-glow: rgba(37,99,235,.04);
            --admin-accent: #2563eb;
            --admin-accent-dark: #1d4ed8;
            --admin-text: #475569;
            --admin-text-primary: #0f172a;
            --admin-text-secondary: #64748b;
            --admin-text-muted: rgba(100,116,139,.6);
            --admin-input-bg: #ffffff;
            --admin-input-text: #1e293b;
            --admin-hover-bg: rgba(37,99,235,.05);
            --admin-row-hover: rgba(37,99,235,.03);
            --admin-select-option-bg: #ffffff;
            --admin-sidebar-bg: #1e293b;
            --admin-sidebar-border: rgba(148,163,184,.15);
            --admin-sidebar-text: #cbd5e1;
            --admin-sidebar-text-muted: rgba(148,163,184,.55);
            --admin-inner-bg: rgba(37,99,235,.03);
            --admin-inner-border: rgba(37,99,235,.08);
            --admin-card-shadow: 0 1px 3px rgba(0,0,0,.04), 0 1px 2px rgba(0,0,0,.02);
            --admin-card-hover-shadow: 0 4px 20px rgba(0,0,0,.06), 0 1px 3px rgba(0,0,0,.04);
        }

        * { transition-property: background-color, border-color, box-shadow, color; transition-duration: .25s; transition-timing-function: ease; }
        a, button, svg, path { transition-duration: .15s; }

        body { font-family: 'Inter', ui-sans-serif, system-ui, sans-serif; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(99,102,241,.3); border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(99,102,241,.5); }
        .light-mode ::-webkit-scrollbar-thumb { background: rgba(37,99,235,.2); }
        .light-mode ::-webkit-scrollbar-thumb:hover { background: rgba(37,99,235,.35); }

        /* Glass Card */
        .glass-card {
            background: var(--admin-card);
            border: 1px solid var(--admin-border);
            border-radius: 16px;
            box-shadow: var(--admin-card-shadow);
        }
        .glass-card:hover {
            border-color: rgba(37,99,235,.2);
            box-shadow: var(--admin-card-hover-shadow);
        }

        /* Gradient Badge */
        .badge-gradient {
            background: linear-gradient(135deg, #2563eb, #3b82f6);
            color: #fff; font-size: 10px; font-weight: 700;
            padding: 2px 8px; border-radius: 99px;
        }

        /* Nav */
        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 14px; font-size: 14px; font-weight: 400;
            color: rgba(255,255,255,.75);
            border-radius: 10px; margin: 1px 10px; position: relative;
        }
        .nav-item:hover { color: #ffffff; background: rgba(255,255,255,.08); }
        .nav-item.active {
            color: #ffffff;
            background: rgba(37,99,235,.35);
            box-shadow: inset 0 0 0 1px rgba(37,99,235,.5);
        }
        /* Light modda da sidebar koyu olduğu için aynı stili koru */
        .light-mode .nav-item { color: rgba(255,255,255,.65); }
        .light-mode .nav-item:hover { color: #ffffff; background: rgba(255,255,255,.08); }
        .light-mode .nav-item.active { color: #ffffff; background: rgba(37,99,235,.4); box-shadow: inset 0 0 0 1px rgba(37,99,235,.55); }
        .nav-item.active::before {
            content: ''; position: absolute; left: 0; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px; border-radius: 0 3px 3px 0;
            background: linear-gradient(180deg, #60a5fa, #3b82f6);
        }

        /* Sidebar ikon arka planları parlaklık artışı */
        [data-sidebar] .nav-item span[style*="background: rgba"],
        nav .nav-item > span:first-child { filter: brightness(1.4) saturate(1.3); }

        /* Sidebar genel override: Koyu arka plan için tüm nav linkleri beyaz  */
        nav > div > div a,
        nav > div > a {
            color: rgba(255,255,255,.6) !important;
        }
        nav > div > div a:hover,
        nav > div > a:hover {
            color: #ffffff !important;
            background: rgba(255,255,255,.07) !important;
        }
        .light-mode nav > div > div a,
        .light-mode nav > div > a {
            color: rgba(255,255,255,.6) !important;
        }

        /* İkon arka plan kutucukları — parlaklık artır */
        nav .nav-item > span:first-child,
        nav > a > span:first-child {
            filter: brightness(1.5) saturate(1.4) !important;
        }

        .sub-nav { padding: 2px 0 2px 40px; }
        .sub-nav a {
            display: flex; align-items: center; justify-content: space-between;
            padding: 5px 12px; font-size: 12px; color: rgba(255,255,255,.5);
            border-radius: 8px; margin: 0;
        }
        .sub-nav a:hover { color: #ffffff; background: rgba(255,255,255,.07); }
        .sub-nav a.active { color: #93c5fd; background: rgba(37,99,235,.25); }
        .light-mode .sub-nav a { color: rgba(255,255,255,.5); }
        .light-mode .sub-nav a:hover { color: #ffffff; background: rgba(255,255,255,.07); }
        .light-mode .sub-nav a.active { color: #93c5fd; background: rgba(37,99,235,.3); }

        /* Stat card */
        .stat-card {
            position: relative; overflow: hidden;
            border-radius: 16px; padding: 20px;
            border: 1px solid var(--admin-border);
            background: var(--admin-card);
            box-shadow: var(--admin-card-shadow);
        }
        .stat-card::before {
            content: ''; position: absolute; top: 0; right: 0;
            width: 100px; height: 100px; border-radius: 50%;
            filter: blur(50px); opacity: .15; pointer-events: none;
        }
        .stat-card:hover { transform: translateY(-2px); box-shadow: var(--admin-card-hover-shadow); }

        /* Pulse dot */
        @keyframes pulse-dot { 0%,100% { opacity:1; } 50% { opacity:.4; } }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* Shimmer */
        @keyframes shimmer { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }

        /* Table */
        .admin-table { width: 100%; }
        .admin-table thead th {
            padding: 12px 16px; font-size: 11px; font-weight: 600;
            text-transform: uppercase; letter-spacing: .05em;
            color: var(--admin-text-muted); border-bottom: 1px solid var(--admin-border);
        }
        .admin-table tbody td {
            padding: 14px 16px; font-size: 13px; color: var(--admin-text);
            border-bottom: 1px solid var(--admin-border);
        }
        .admin-table tbody tr { transition: background .15s ease; }
        .admin-table tbody tr:hover { background: var(--admin-row-hover); }

        /* Input */
        .admin-input {
            width: 100% !important; padding: 10px 14px !important;
            background: var(--admin-input-bg) !important; border: 1px solid var(--admin-border) !important;
            border-radius: 12px !important; font-size: 13px !important; color: var(--admin-input-text) !important;
            outline: none !important; -webkit-appearance: none; appearance: none;
        }
        .admin-input:focus {
            border-color: rgba(37,99,235,.5) !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1) !important;
        }
        .light-mode .admin-input { border-color: rgba(0,0,0,.12) !important; }
        .admin-input::placeholder { color: var(--admin-text-muted) !important; }
        textarea.admin-input { resize: vertical; min-height: 80px; }

        /* Buttons */
        .btn-primary {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px 20px; font-size: 13px; font-weight: 600;
            background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
            color: #fff !important; border-radius: 12px; border: none !important; cursor: pointer;
        }
        .btn-primary:hover { box-shadow: 0 4px 20px rgba(37,99,235,.35); transform: translateY(-1px); }
        .btn-success {
            padding: 6px 14px; font-size: 11px; font-weight: 600;
            background: linear-gradient(135deg, #059669, #10b981) !important; color: #fff !important;
            border-radius: 8px; border: none !important; cursor: pointer;
        }
        .btn-success:hover { box-shadow: 0 2px 12px rgba(16,185,129,.3); }
        .btn-danger {
            padding: 6px 14px; font-size: 11px; font-weight: 600;
            background: linear-gradient(135deg, #dc2626, #ef4444) !important; color: #fff !important;
            border-radius: 8px; border: none !important; cursor: pointer;
        }
        .btn-danger:hover { box-shadow: 0 2px 12px rgba(239,68,68,.3); }

        /* Select */
        .admin-select {
            padding: 10px 36px 10px 14px !important; background: var(--admin-input-bg) !important;
            border: 1px solid var(--admin-border) !important; border-radius: 12px !important;
            font-size: 13px !important; color: var(--admin-input-text) !important; outline: none !important;
            cursor: pointer; -webkit-appearance: none; appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%232563eb' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important; background-position: right 12px center !important; background-size: 14px !important;
        }
        .admin-select:focus { border-color: rgba(37,99,235,.5) !important; box-shadow: 0 0 0 3px rgba(37,99,235,.1) !important; }
        .admin-select option { background: var(--admin-select-option-bg); color: var(--admin-input-text); }

        /* Status badges — work in both modes */
        .status-success { background: rgba(16,185,129,.12); color: #059669; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
        .status-warning { background: rgba(245,158,11,.12); color: #d97706; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
        .status-danger { background: rgba(239,68,68,.12); color: #dc2626; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
        .status-info { background: rgba(59,130,246,.12); color: #2563eb; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }
        .status-purple { background: rgba(139,92,246,.12); color: #7c3aed; padding: 3px 10px; border-radius: 99px; font-size: 11px; font-weight: 600; }

        /* ═══ Light Mode Tailwind Color Overrides ═══ */
        .light-mode .text-white { color: var(--admin-text-primary) !important; }
        .light-mode .text-slate-400,
        .light-mode .text-slate-500,
        .light-mode .text-slate-600 { color: var(--admin-text-secondary) !important; }
        .light-mode .text-slate-700 { color: #94a3b8 !important; }
        .light-mode .text-gray-300 { color: var(--admin-text) !important; }
        .light-mode .text-gray-500 { color: var(--admin-text-secondary) !important; }
        .light-mode .divide-y > :not(:first-child) { border-color: var(--admin-border) !important; }
        .light-mode .bg-black\/60 { background: rgba(0,0,0,.4) !important; }

        /* ═══ Light Mode — Inner Card Elements ═══ */
        .light-mode .glass-card [style*="background: rgba(99,102,241"] { background: rgba(37,99,235,.04) !important; }
        .light-mode .glass-card [style*="border: 1px solid rgba(99,102,241"] { border-color: rgba(37,99,235,.1) !important; }
        .light-mode .glass-card [style*="background: var(--admin-bg)"] { background: rgba(37,99,235,.03) !important; }
        .light-mode .glass-card [style*="border: 1px solid var(--admin-border)"] { border-color: rgba(0,0,0,.06) !important; }
        .light-mode .glass-card [style*="background: rgba(245,158,11,.04)"] { background: rgba(245,158,11,.06) !important; }
        .light-mode .glass-card [style*="background: rgba(245,158,11,.08)"] { background: rgba(245,158,11,.06) !important; }
        .light-mode .glass-card [style*="background: rgba(239,68,68,.04)"] { background: rgba(239,68,68,.05) !important; }
        .light-mode .glass-card [style*="border-top: 1px solid rgba(148,163,184"] { border-color: rgba(0,0,0,.06) !important; }

        /* Light mode stat cards — stronger gradient for visibility */
        .light-mode .stat-card[style*="rgba(99,102,241"] { background: linear-gradient(135deg, rgba(99,102,241,.08), rgba(99,102,241,.02)) !important; }
        .light-mode .stat-card[style*="rgba(16,185,129"] { background: linear-gradient(135deg, rgba(16,185,129,.08), rgba(16,185,129,.02)) !important; }
        .light-mode .stat-card[style*="rgba(59,130,246"] { background: linear-gradient(135deg, rgba(59,130,246,.08), rgba(59,130,246,.02)) !important; }
        .light-mode .stat-card[style*="rgba(245,158,11"] { background: linear-gradient(135deg, rgba(245,158,11,.08), rgba(245,158,11,.02)) !important; }

        /* Light mode accent text — darker for readability */
        .light-mode .text-amber-400 { color: #b45309 !important; }
        .light-mode .text-amber-500 { color: #92400e !important; }
        .light-mode .text-emerald-400 { color: #059669 !important; }
        .light-mode .text-emerald-500 { color: #047857 !important; }
        .light-mode .text-indigo-400 { color: #4338ca !important; }
        .light-mode .text-indigo-500 { color: #3730a3 !important; }
        .light-mode .text-purple-400 { color: #7c3aed !important; }
        .light-mode .text-purple-500 { color: #6d28d9 !important; }
        .light-mode .text-rose-400    { color: #dc2626 !important; }
        .light-mode .text-blue-400    { color: #1d4ed8 !important; }
        .light-mode .text-blue-300    { color: #1d4ed8 !important; }
        .light-mode .text-orange-400  { color: #c2410c !important; }
        .light-mode .text-cyan-400    { color: #0e7490 !important; }
        .light-mode .text-red-400     { color: #b91c1c !important; }
        .light-mode .text-red-300     { color: #991b1b !important; }
        .light-mode .text-green-400   { color: #16a34a !important; }
        .light-mode .text-yellow-400  { color: #a16207 !important; }
        .light-mode .text-teal-400    { color: #0f766e !important; }
        .light-mode .text-sky-400     { color: #0369a1 !important; }
        .light-mode .text-violet-400  { color: #6d28d9 !important; }
        .light-mode .text-fuchsia-400 { color: #86198f !important; }
        .light-mode .text-pink-400    { color: #be185d !important; }

        /* Light mode: gray sınıfları — okunaklı koyu tona taşı */
        .light-mode .text-gray-100 { color: #374151 !important; }
        .light-mode .text-gray-200 { color: #374151 !important; }
        .light-mode .text-gray-300 { color: #374151 !important; }
        .light-mode .text-gray-400 { color: #4b5563 !important; }
        .light-mode .text-gray-500 { color: #6b7280 !important; }
        .light-mode .text-gray-600 { color: #374151 !important; }
        .light-mode .text-gray-700 { color: #1f2937 !important; }
        .light-mode .text-gray-800 { color: #111827 !important; }
        .light-mode .text-gray-900 { color: #030712 !important; }

        /* Light mode: slate variants */
        .light-mode .text-slate-300 { color: #374151 !important; }
        .light-mode .text-slate-400 { color: #4b5563 !important; }
        .light-mode .text-slate-500 { color: #64748b !important; }
        .light-mode .text-slate-600 { color: #334155 !important; }
        .light-mode .text-slate-700 { color: #1e293b !important; }

        /* Light mode: text-white → koyu renk (beyaz BG'da okunmaz) */
        .light-mode .text-white { color: #0f172a !important; }

        /* Sidebar üzerindeki text-white kalıcı beyaz kalmalı (sidebar #1e293b BG) */
        .light-mode [data-sidebar] .text-white,
        .light-mode nav .text-white,
        .light-mode header .text-white { color: #ffffff !important; }

        /* Light mode: Arka plan renk overrides */
        .light-mode .bg-gray-700,
        .light-mode .bg-gray-800,
        .light-mode .bg-gray-900  { background-color: #e2e8f0 !important; }
        .light-mode .bg-white\/5  { background: rgba(0,0,0,.04) !important; }
        .light-mode .bg-white\/8  { background: rgba(0,0,0,.05) !important; }
        .light-mode .bg-white\/10 { background: rgba(0,0,0,.05) !important; }
        .light-mode .bg-white\/20 { background: rgba(0,0,0,.08) !important; }
        .light-mode .bg-black\/60 { background: rgba(0,0,0,.4) !important; }

        /* Light mode: border renkleri */
        .light-mode .border-white\/10 { border-color: rgba(0,0,0,.08) !important; }
        .light-mode .border-white\/20 { border-color: rgba(0,0,0,.12) !important; }
        .light-mode .border-gray-700  { border-color: #cbd5e1 !important; }
        .light-mode .border-gray-800  { border-color: #e2e8f0 !important; }
        .light-mode .divide-y > :not(:first-child) { border-color: var(--admin-border) !important; }

        /* Light mode: Hover states */
        .light-mode .hover\:bg-white\/5:hover  { background: rgba(0,0,0,.04) !important; }
        .light-mode .hover\:bg-white\/10:hover { background: rgba(0,0,0,.06) !important; }
        .light-mode .hover\:text-white:hover   { color: #0f172a !important; }

        /* Command Palette — light mode */
        .light-mode [style*="background: var(--admin-card)"] .text-gray-300 { color: #374151 !important; }
        .light-mode [style*="background: var(--admin-card)"] .text-gray-500 { color: #6b7280 !important; }
        .light-mode [style*="background: var(--admin-card)"] input  { color: #0f172a !important; }
        .light-mode [style*="background: var(--admin-card)"] input::placeholder { color: #9ca3af !important; }
        .light-mode kbd { background: rgba(0,0,0,.06) !important; border-color: rgba(0,0,0,.1) !important; color: #374151 !important; }

        /* Placeholder genel */
        .light-mode ::placeholder { color: #9ca3af !important; opacity: 1; }

        /* Tablo başlıkları */
        .light-mode .admin-table thead th { color: #64748b !important; }
        .light-mode .admin-table tbody td { color: #1e293b !important; }

        /* Light mode — Modal ve overlay içi */
        .light-mode [style*="background: var(--admin-card)"][class*="rounded-2xl"] { box-shadow: 0 25px 50px rgba(0,0,0,.15) !important; }

        /* Inner container dark bg'lar */
        .light-mode [style*="background: rgba(99,102,241,.04)"]  { background: rgba(79,70,229,.06) !important; }
        .light-mode [style*="background: rgba(99,102,241,.08)"]  { background: rgba(79,70,229,.08) !important; }
        .light-mode [style*="background: rgba(15,23,42"]         { background: #f0f4f8 !important; }
        .light-mode [style*="background: rgba(16,185,129,.08)"]  { background: rgba(16,185,129,.08) !important; }
        .light-mode [style*="background: rgba(245,158,11,.08)"]  { background: rgba(245,158,11,.08) !important; }
        .light-mode [style*="background: rgba(239,68,68"]        { background: rgba(239,68,68,.06) !important; }
        .light-mode [style*="background: rgba(59,130,246,.04)"]  { background: rgba(37,99,235,.06) !important; }

        /* glass-card inner elements */
        .light-mode .glass-card [style*="background: var(--admin-bg)"]            { background: #f8fafc !important; }
        .light-mode .glass-card [style*="border: 1px solid var(--admin-border)"]  { border-color: rgba(0,0,0,.06) !important; }
        .light-mode .glass-card [style*="border:1px solid var(--admin-border)"]   { border-color: rgba(0,0,0,.06) !important; }
        .light-mode .glass-card [style*="border: 1px dashed var(--admin-border)"] { border-color: rgba(0,0,0,.1) !important; }
        .light-mode .glass-card [style*="border:1px dashed var(--admin-border)"]  { border-color: rgba(0,0,0,.1) !important; }
        .light-mode .glass-card [style*="border-color:var(--admin-border)"]       { border-color: rgba(0,0,0,.08) !important; }

        /* Sidebar icon bg adjustments */
        .light-mode .nav-item span[style*="background: rgba"] { opacity: .85; }

        /* Profile dropdown */
        .light-mode .profile-dropdown-item { color: #1e293b !important; }
        .light-mode .profile-dropdown-item:hover { background: var(--admin-hover-bg); color: #0f172a !important; }

        /* Animate-pulse dot — görünür kalsın */
        .light-mode .animate-pulse { background: rgba(16,185,129,.8) !important; }

        /* Fractional opacity bg sınıfları */
        .light-mode .bg-white\/\[\.02\] { background: rgba(0,0,0,.025) !important; }
        .light-mode .bg-white\/\[\.03\] { background: rgba(0,0,0,.03) !important; }
        .light-mode .bg-white\/\[\.04\] { background: rgba(0,0,0,.04) !important; }
        .light-mode .bg-white\/\[\.05\] { background: rgba(0,0,0,.05) !important; }
        .light-mode .bg-white\/15 { background: rgba(0,0,0,.07) !important; }

        /* Stat card içindeki text-white ve dark renk sorunları */
        .light-mode .stat-card .text-white { color: #0f172a !important; }
        .light-mode .stat-card [style*=\"background: linear-gradient\"] .text-white { color: #ffffff !important; }

        /* İkon butonlarının içindeki beyaz ikonlar gradient BG üzerinde beyaz kalmalı */
        .light-mode [style*=\"background: linear-gradient\"] svg.text-white,
        .light-mode [style*=\"background: linear-gradient\"] .text-white { color: #ffffff !important; }

        /* Donut/pie grafik içi yüzde metni */
        .light-mode .absolute.inset-0.flex.items-center.justify-center { color: #1e293b !important; }

        /* opacity-80 class ile kararan renkler */
        .light-mode opacity-80 { opacity: 1 !important; }

        /* Tablo satırı hover'ı */
        .light-mode .admin-table tbody tr:hover { background: rgba(37,99,235,.04) !important; }

        /* Divider içi çizgiler */
        .light-mode [style*=\"--tw-divide-color\"] > * { border-color: rgba(0,0,0,.06) !important; }

        /* Text-slate-600 daha okunaklı */
        .light-mode .text-slate-600 { color: #475569 !important; }


        /* Flash messages */
        .flash-success {
            padding: 14px 18px; border-radius: 14px; font-size: 13px; font-weight: 500;
            background: rgba(16,185,129,.08); border: 1px solid rgba(16,185,129,.2); color: #059669;
        }
        .flash-danger {
            padding: 14px 18px; border-radius: 14px; font-size: 13px; font-weight: 500;
            background: rgba(239,68,68,.08); border: 1px solid rgba(239,68,68,.2); color: #dc2626;
        }
        .flash-warning {
            padding: 14px 18px; border-radius: 14px; font-size: 13px; font-weight: 500;
            background: rgba(245,158,11,.08); border: 1px solid rgba(245,158,11,.2); color: #d97706;
        }

        /* Profile dropdown */
        .profile-dropdown { border-radius: 12px; }
        .profile-dropdown-item { transition: background .15s ease, color .15s ease; cursor: pointer; }
        .profile-dropdown-item:hover { background: var(--admin-hover-bg); color: var(--admin-text-primary) !important; }

        /* Tab border light mode */
        .light-mode .flex.border-b[style*="border-color:var(--admin-border)"],
        .light-mode .flex.border-b { border-color: rgba(0,0,0,.08) !important; }
        /* Tab buton aktif underline */
        .light-mode button[class*="border-b-2"][class*="border-indigo"] { border-color: #4338ca !important; color: #4338ca !important; }
        /* Section heading kontrast */
        .light-mode h1, .light-mode h2, .light-mode h3, .light-mode h4 { color: var(--admin-text-primary); }

        /* Chip/pill bg'ler */
        .light-mode .bg-indigo-500\/10   { background: rgba(79,70,229,.08) !important; }
        .light-mode .bg-emerald-500\/10  { background: rgba(16,185,129,.08) !important; }
        .light-mode .bg-amber-500\/10    { background: rgba(245,158,11,.08) !important; }
        .light-mode .bg-red-500\/10      { background: rgba(239,68,68,.08) !important; }
        .light-mode .bg-blue-500\/10     { background: rgba(37,99,235,.08) !important; }
        .light-mode .bg-purple-500\/10   { background: rgba(139,92,246,.08) !important; }
        .light-mode .bg-rose-500\/10     { background: rgba(244,63,94,.08) !important; }
        .light-mode .bg-cyan-500\/10     { background: rgba(6,182,212,.08) !important; }
        .light-mode .bg-indigo-500\/20   { background: rgba(79,70,229,.12) !important; }
        .light-mode .bg-emerald-500\/20  { background: rgba(16,185,129,.12) !important; }
        .light-mode .bg-amber-500\/20    { background: rgba(245,158,11,.12) !important; }
        .light-mode .bg-red-500\/20      { background: rgba(239,68,68,.12) !important; }

        /* Select çekimi ok rengi light modda görünür olsun */
        .light-mode .admin-select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%234338ca' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E") !important;
        }

        @media (max-width: 768px) { .stat-card { padding: 14px; } }
    </style>
</head>
<body class="antialiased" style="background: var(--admin-bg); color: var(--admin-text); transition: background .3s ease, color .3s ease;">

    <div x-data="{ sidebarOpen: false, shortcutOpen: false }" @keydown.window.ctrl.k.prevent="shortcutOpen = !shortcutOpen" @keydown.window.escape="shortcutOpen = false" class="min-h-screen flex flex-col">

        {{-- ===== COMMAND PALETTE (Ctrl+K) ===== --}}
        <template x-if="shortcutOpen">
            <div class="fixed inset-0 z-[100] flex items-start justify-center pt-[15vh]" @click.self="shortcutOpen = false">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>
                <div class="relative w-full max-w-lg mx-4 rounded-2xl overflow-hidden" style="background: var(--admin-card); border: 1px solid var(--admin-border);">
                    <div class="flex items-center gap-3 px-5 py-4 border-b" style="border-color: var(--admin-border);">
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" placeholder="Sayfaya git... (ör: kullanıcı, guard, ödeme)" class="flex-1 bg-transparent text-white text-sm outline-none placeholder-gray-500" autofocus>
                        <kbd class="text-[10px] text-gray-500 bg-white/5 px-2 py-0.5 rounded border border-white/10">ESC</kbd>
                    </div>
                    <div class="p-2 max-h-[320px] overflow-y-auto">
                        <p class="px-3 py-1.5 text-[10px] font-semibold text-gray-500 uppercase tracking-wider">Hızlı Navigasyon</p>
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-indigo-500/10 flex items-center justify-center text-indigo-400">📊</span> Dashboard
                        </a>
                        <a href="{{ route('admin.users') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-400">👥</span> Kullanıcılar
                        </a>
                        <a href="{{ route('admin.approvals.senders') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-amber-500/10 flex items-center justify-center text-amber-400">✅</span> Gönderici Onayları
                        </a>
                        <a href="{{ route('admin.approvals.payments') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-400">💰</span> Ödeme Onayları
                        </a>
                        <a href="{{ route('admin.guard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-purple-500/10 flex items-center justify-center text-purple-400">🤖</span> AI GuardSystem
                        </a>
                        <a href="{{ route('admin.guard.scan') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-rose-500/10 flex items-center justify-center text-rose-400">🔍</span> Mesaj Tarama
                        </a>
                        <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-white/5 text-sm text-gray-300 hover:text-white transition-colors">
                            <span class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400">📈</span> Raporlar
                        </a>
                    </div>
                    <div class="px-4 py-2.5 border-t text-[11px] text-gray-500 flex items-center gap-4" style="border-color: var(--admin-border);">
                        <span><kbd class="bg-white/5 px-1.5 py-0.5 rounded border border-white/10">↑↓</kbd> Gezin</span>
                        <span><kbd class="bg-white/5 px-1.5 py-0.5 rounded border border-white/10">Enter</kbd> Seç</span>
                        <span><kbd class="bg-white/5 px-1.5 py-0.5 rounded border border-white/10">Ctrl+K</kbd> Aç/Kapat</span>
                    </div>
                </div>
            </div>
        </template>

        {{-- ===== TOP HEADER ===== --}}
        <header class="sticky top-0 z-50" style="background: #2563eb; overflow: visible;">
            <div class="flex items-center h-[56px] px-4" style="overflow: visible;">
                {{-- Mobile toggle --}}
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden mr-3 w-9 h-9 rounded-xl flex items-center justify-center hover:bg-white/5 transition-colors text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Logo --}}
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-xl flex items-center justify-center" style="background: rgba(255,255,255,.15);">
                        <svg class="w-4.5 h-4.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-[15px] font-bold text-white tracking-tight">TopluSMS</span>
                        <span class="badge-gradient ml-1.5">ADMIN</span>
                    </div>
                </div>

                <div class="flex-1"></div>

                {{-- Shortcuts --}}
                <div class="hidden md:flex items-center gap-2 mr-24">
                    <button @click="shortcutOpen = true" class="flex items-center gap-2 w-72 px-4 py-1.5 rounded-lg text-[12px] text-white/70 hover:text-white hover:bg-white/10 transition-all" style="border: 1px solid rgba(255,255,255,.2);">
                        <svg class="w-3.5 h-3.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span class="flex-1 text-left">Hızlı Ara</span>
                        <div class="flex items-center gap-1 ml-auto">
                            <kbd class="text-[11px] font-medium bg-white/15 text-white/90 px-2 py-0.5 rounded-md" style="border: 1px solid rgba(255,255,255,.25); box-shadow: 0 1px 2px rgba(0,0,0,.15);">Ctrl</kbd>
                            <span class="text-[10px] text-white/40">+</span>
                            <kbd class="text-[11px] font-medium bg-white/15 text-white/90 px-2 py-0.5 rounded-md" style="border: 1px solid rgba(255,255,255,.25); box-shadow: 0 1px 2px rgba(0,0,0,.15);">K</kbd>
                        </div>
                    </button>
                </div>

                {{-- App icon --}}
                <a href="{{ route('admin.approvals.senders') }}" class="mx-2 relative w-9 h-9 rounded-xl flex items-center justify-center hover:bg-white/10 text-white/80 hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                </a>

                {{-- ===== User Avatar + Dropdown ===== --}}
                <div class="relative ml-2" x-data="{ open: false }" @click.away="open = false">
                    <button @click="open = !open" class="w-10 h-10 rounded-full overflow-hidden cursor-pointer transition-all duration-200 hover:scale-105 focus:outline-none" style="border: 2.5px solid rgba(255,255,255,.35);" :style="open ? 'border-color: rgba(255,255,255,.7); box-shadow: 0 0 0 3px rgba(255,255,255,.15)' : ''">
                        <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-1"
                         x-transition:enter-end="opacity-100 translate-y-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0"
                         x-transition:leave-end="opacity-0 translate-y-1"
                         x-cloak
                         class="absolute right-0 w-56 rounded-xl overflow-hidden z-[999]"
                         style="top: calc(100% + 10px); background: var(--admin-card); border: 1px solid var(--admin-border); box-shadow: 0 10px 40px rgba(0,0,0,.3), 0 2px 10px rgba(0,0,0,.15);">

                        {{-- User info --}}
                        <div class="flex items-center gap-3 px-4 py-3.5" style="border-bottom: 1px solid var(--admin-border);">
                            <div class="shrink-0" style="width: 36px; height: 36px; border-radius: 50%; overflow: hidden; border: 2px solid var(--admin-border);">
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" style="width: 36px; height: 36px; object-fit: cover; display: block;">
                            </div>
                            <div class="min-w-0">
                                <p class="text-[13px] font-semibold truncate" style="color: var(--admin-text-primary);">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-[11px] truncate mt-0.5" style="color: var(--admin-text-secondary);">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                        </div>

                        <div class="py-1.5">
                            {{-- Müşteri Paneli --}}
                            <a href="{{ route('panel.dashboard') }}" class="profile-dropdown-item flex items-center gap-3 px-4 py-2.5 text-[13px]" style="color: var(--admin-text);">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(59,130,246,.1);">
                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </span>
                                Müşteri Paneli
                            </a>

                            {{-- Light / Dark Mode --}}
                            <button @click="$store.theme.toggle()" class="profile-dropdown-item w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-left" style="color: var(--admin-text);">
                                <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(245,158,11,.1);">
                                    <svg x-show="$store.theme.dark" class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                    <svg x-show="!$store.theme.dark" x-cloak class="w-3.5 h-3.5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                                </span>
                                <span x-text="$store.theme.dark ? 'Light Mode' : 'Dark Mode'"></span>
                            </button>
                        </div>

                        {{-- Çıkış --}}
                        <div style="border-top: 1px solid var(--admin-border);" class="py-1.5">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="profile-dropdown-item w-full flex items-center gap-3 px-4 py-2.5 text-[13px] text-left" style="color: #ef4444;">
                                    <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(239,68,68,.08);">
                                        <svg class="w-3.5 h-3.5" style="color: #ef4444;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                    </span>
                                    Çıkış Yap
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex flex-1 min-h-0">

            {{-- Mobile Overlay --}}
            <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="sidebarOpen = false" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden" x-cloak></div>

            {{-- ===== SIDEBAR ===== --}}
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-[57px] left-0 z-50 w-[280px] transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:sticky lg:top-[57px] lg:inset-auto lg:z-auto lg:h-[calc(100vh-57px)] lg:shrink-0">
                <div class="flex flex-col h-full overflow-hidden" style="background: var(--admin-sidebar-bg); border-right: 1px solid var(--admin-sidebar-border); transition: background .3s ease;">

                @php
                    $openMenu = match(true) {
                        request()->routeIs('admin.sms*') => 'sms',
                        request()->routeIs('admin.whatsapp*') => 'whatsapp',
                        request()->routeIs('admin.approvals*') => 'onay',
                        request()->routeIs('admin.guard*') => 'guard',
                        default => '',
                    };
                @endphp
                <nav class="flex-1 py-2 space-y-0" x-data="{ openMenu: '{{ $openMenu }}' }">

                    {{-- Dashboard --}}
                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                        Dashboard
                    </a>

                    {{-- Kullanıcılar --}}
                    <a href="{{ route('admin.users') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Kullanıcılar
                    </a>

                    {{-- SMS --}}
                    <div>
                        <button @click="openMenu = openMenu === 'sms' ? '' : 'sms'" class="nav-item w-full justify-between {{ request()->routeIs('admin.sms*') ? 'active' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                SMS Yönetimi
                            </span>
                            <svg :class="openMenu === 'sms' ? 'rotate-90' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'sms'" x-collapse class="sub-nav">
                            <a href="{{ route('admin.sms') }}" wire:navigate class="{{ request()->routeIs('admin.sms') && !request()->routeIs('admin.sms.campaigns') ? 'active' : '' }}">Mesajlar</a>
                            <a href="{{ route('admin.sms.campaigns') }}" wire:navigate class="{{ request()->routeIs('admin.sms.campaigns') ? 'active' : '' }}">Kampanyalar</a>
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div>
                        <button @click="openMenu = openMenu === 'whatsapp' ? '' : 'whatsapp'" class="nav-item w-full justify-between {{ request()->routeIs('admin.whatsapp*') ? 'active' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-green-400 shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                WhatsApp
                            </span>
                            <svg :class="openMenu === 'whatsapp' ? 'rotate-90' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'whatsapp'" x-collapse class="sub-nav">
                            <a href="{{ route('admin.whatsapp') }}" wire:navigate class="{{ request()->routeIs('admin.whatsapp') && !request()->routeIs('admin.whatsapp.sessions') ? 'active' : '' }}">Mesajlar</a>
                            <a href="{{ route('admin.whatsapp.sessions') }}" wire:navigate class="{{ request()->routeIs('admin.whatsapp.sessions') ? 'active' : '' }}">Oturumlar</a>
                        </div>
                    </div>

                    {{-- Onay İşlemleri --}}
                    <div>
                        <button @click="openMenu = openMenu === 'onay' ? '' : 'onay'" class="nav-item w-full justify-between {{ request()->routeIs('admin.approvals.senders') || request()->routeIs('admin.approvals.documents') ? 'active' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-amber-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Sms Başlıkları
                            </span>
                            <svg :class="openMenu === 'onay' ? 'rotate-90' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'onay'" x-collapse class="sub-nav">
                            @php $ps = \App\Models\SenderName::where('status','pending')->count(); @endphp
                            <a href="{{ route('admin.approvals.senders') }}" wire:navigate class="{{ request()->routeIs('admin.approvals.senders') ? 'active' : '' }}">
                                Gönderici Adları @if($ps > 0)<span class="badge-gradient">{{ $ps }}</span>@endif
                            </a>
                            @php $pd = \App\Models\Document::where('status','pending')->count(); @endphp
                            <a href="{{ route('admin.approvals.documents') }}" wire:navigate class="{{ request()->routeIs('admin.approvals.documents') ? 'active' : '' }}">
                                Evraklar @if($pd > 0)<span class="badge-gradient">{{ $pd }}</span>@endif
                            </a>
                            @php $pq = \App\Models\SmsApprovalQueue::where('status','pending')->count(); @endphp
                            <a href="{{ route('admin.sms.queue') }}" wire:navigate class="{{ request()->routeIs('admin.sms.queue') ? 'active' : '' }}">
                                SMS Onay Kuyruğu @if($pq > 0)<span class="badge-gradient">{{ $pq }}</span>@endif
                            </a>
                        </div>
                    </div>

                    {{-- AI Guard --}}
                    <div>
                        <button @click="openMenu = openMenu === 'guard' ? '' : 'guard'" class="nav-item w-full justify-between {{ request()->routeIs('admin.guard*') ? 'active' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-purple-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                <span class="text-blue-300 font-semibold">AI Guard</span>
                            </span>
                            <svg :class="openMenu === 'guard' ? 'rotate-90' : ''" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="openMenu === 'guard'" x-collapse class="sub-nav">
                            <a href="{{ route('admin.guard') }}" wire:navigate class="{{ request()->routeIs('admin.guard') && request()->path() === 'admin/guard' ? 'active' : '' }}">Kontrol Merkezi</a>
                            <a href="{{ route('admin.guard.logs') }}" wire:navigate class="{{ request()->routeIs('admin.guard.logs') ? 'active' : '' }}">Aksiyon Logları</a>
                            <a href="{{ route('admin.guard.risks') }}" wire:navigate class="{{ request()->routeIs('admin.guard.risks') ? 'active' : '' }}">Risk Skorları</a>
                            <a href="{{ route('admin.guard.filters') }}" wire:navigate class="{{ request()->routeIs('admin.guard.filters') ? 'active' : '' }}">Mesaj Filtreleri</a>
                            <a href="{{ route('admin.guard.suspended') }}" wire:navigate class="{{ request()->routeIs('admin.guard.suspended') ? 'active' : '' }}">Askıya Alınanlar</a>
                            <a href="{{ route('admin.guard.scan') }}" wire:navigate class="{{ request()->routeIs('admin.guard.scan') ? 'active' : '' }}">Mesaj Tarama</a>
                            <a href="{{ route('admin.guard.settings') }}" wire:navigate class="{{ request()->routeIs('admin.guard.settings') ? 'active' : '' }}">Ayarlar</a>
                        </div>
                    </div>

                    {{-- Bildirimler --}}
                    <a href="{{ route('admin.notifications') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.notifications') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-yellow-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        Bildirimler
                    </a>

                    {{-- Kara Liste --}}
                    <a href="{{ route('admin.blacklist') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.blacklist') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-red-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                        Kara Liste
                    </a>

                    {{-- Raporlar --}}
                    <a href="{{ route('admin.reports') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-cyan-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        Raporlar
                    </a>

                    {{-- API Ayarları --}}
                    <a href="{{ route('admin.settings.api') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.settings.api') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-blue-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        API Ayarları
                    </a>

                    {{-- Sanal POS Siparişleri --}}
                    <a href="{{ route('admin.virtualpos.orders') }}" wire:navigate class="nav-item {{ request()->routeIs('admin.virtualpos.orders') ? 'active' : '' }}">
                        <svg class="w-4 h-4 text-emerald-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                        Sanal POS Siparışleri
                    </a>

                    {{-- Banka Hesapları (dropdown) --}}
                    <div x-data="{ open: {{ request()->routeIs('admin.bank.*') || request()->routeIs('admin.approvals.payments') ? 'true' : 'false' }} }">
                        <button @click="open = !open"
                                class="nav-item w-full justify-between {{ request()->routeIs('admin.bank.*') || request()->routeIs('admin.approvals.payments') ? 'active' : '' }}">
                            <span class="flex items-center gap-3">
                                <svg class="w-4 h-4 shrink-0" style="color: #c4b5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                Banka Hesapları
                            </span>
                            <svg :class="open ? 'rotate-90' : ''" class="w-3 h-3 transition-transform shrink-0" style="color: rgba(255,255,255,.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <div x-show="open" x-transition class="mt-0.5" style="background: rgba(0,0,0,.2);">
                            <a href="{{ route('admin.bank.accounts') }}" wire:navigate
                               class="flex items-center gap-2.5 px-4 pl-12 py-2.5 text-[12px] font-medium transition-all hover:bg-white/5 rounded-lg mx-1"
                               style="color: {{ request()->routeIs('admin.bank.accounts') ? '#ffffff' : 'rgba(255,255,255,.55)' }};">
                                <svg class="w-3.5 h-3.5 shrink-0" style="color: #c4b5fd;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                Hesap Yönetimi
                            </a>
                            <a href="{{ route('admin.approvals.payments') }}" wire:navigate
                               class="flex items-center gap-2.5 px-4 pl-12 py-2.5 text-[12px] font-medium transition-all hover:bg-white/5 rounded-lg mx-1"
                               style="color: {{ request()->routeIs('admin.approvals.payments') ? '#ffffff' : 'rgba(255,255,255,.55)' }};">
                                <svg class="w-3.5 h-3.5 shrink-0" style="color: #fcd34d;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ödeme Onayları
                                @php $pendingCount = \App\Models\PaymentNotification::where('status','pending')->count(); @endphp
                                @if($pendingCount > 0)
                                    <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded-full" style="background: rgba(245,158,11,.35); color: #fcd34d;">{{ $pendingCount }}</span>
                                @endif
                            </a>
                        </div>
                    </div>

                    {{-- Kullanıcı Paneline Git --}}
                    <div style="margin: 6px 10px 0; border-top: 1px solid var(--admin-border); padding-top: 6px;">
                        <a href="{{ route('panel.dashboard') }}" class="nav-item font-semibold" style="color: #f59e0b;">
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background: rgba(245,158,11,.15);">
                                <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            </span>
                            Kullanıcı Paneli
                            <span class="ml-auto text-[9px] font-bold px-1.5 py-0.5 rounded" style="background: rgba(245,158,11,.2); color: #fbbf24;">PANEL</span>
                        </a>
                    </div>

                </nav>

                {{-- Sidebar footer --}}
                <div class="p-3" style="border-top: 1px solid var(--admin-border);">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-item w-full hover:!text-red-400">
                            <span class="w-7 h-7 rounded-lg flex items-center justify-center" style="background: rgba(239,68,68,.06);"><svg class="w-4 h-4 text-red-400/60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg></span>
                            Çıkış Yap
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ===== MAIN CONTENT ===== --}}
            <div class="flex-1 flex flex-col min-w-0 lg:h-[calc(100vh-57px)] lg:overflow-y-auto">
                <main class="flex-1 p-3 lg:p-4">
                    {{ $slot }}
                </main>

                {{-- Mobile bottom nav --}}
                <nav class="lg:hidden sticky bottom-0 flex items-center justify-around py-2 px-1" style="background: var(--admin-surface); border-top: 1px solid var(--admin-border); backdrop-filter: blur(20px);">
                    <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'text-indigo-400' : 'text-gray-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6z"/></svg>
                        <span class="text-[10px]">Ana Sayfa</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('admin.users*') ? 'text-blue-400' : 'text-gray-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1z"/></svg>
                        <span class="text-[10px]">Kullanıcı</span>
                    </a>
                    <a href="{{ route('admin.approvals.payments') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl relative {{ request()->routeIs('admin.approvals*') ? 'text-amber-400' : 'text-gray-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span class="text-[10px]">Sms Başlıkları</span>
                    </a>
                    <a href="{{ route('admin.guard') }}" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl {{ request()->routeIs('admin.guard*') ? 'text-purple-400' : 'text-gray-500' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <span class="text-[10px]">Guard</span>
                    </a>
                    <button @click="sidebarOpen = true" class="flex flex-col items-center gap-0.5 py-1 px-3 rounded-xl text-gray-500">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        <span class="text-[10px]">Menü</span>
                    </button>
                </nav>
            </div>
        </div>
    </div>

</body>
</html>
