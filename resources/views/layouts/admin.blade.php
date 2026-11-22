@php
    $moduleBreadcrumb = 'Dashboard';
    if (request()->routeIs('admin.anak-asuh.*') || request()->is('admin/anak-asuh*')) {
        $moduleBreadcrumb = 'Data Anak Asuh';
    } elseif (request()->routeIs('admin.kelola-donasi.*') || request()->is('admin/kelola-donasi*')) {
        $moduleBreadcrumb = 'Kelola Donasi';
    } elseif (request()->routeIs('admin.kelola-kampanye.*') || request()->is('admin/kelola-kampanye*')) {
        $moduleBreadcrumb = 'Kelola Kampanye';
    } elseif (request()->routeIs('admin.berita.*') || request()->is('admin/cms-berita*')) {
        $moduleBreadcrumb = 'CMS Berita';
    } elseif (request()->routeIs('admin.laporan-ekspor.*') || request()->is('admin/laporan-ekspor*') || request()->is('admin/laporan*')) {
        $moduleBreadcrumb = 'Laporan & Ekspor';
    } elseif (request()->routeIs('admin.users.*') || request()->is('admin/kelola-admin*')) {
        $moduleBreadcrumb = 'Kelola Admin';
    } elseif (request()->routeIs('admin.security.*') || request()->is('admin/keamanan-akun*')) {
        $moduleBreadcrumb = 'Keamanan Akun';
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', ($title ?? ($moduleBreadcrumb . ' - Admin Panel Himmatun Ayat')))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-screen overflow-hidden bg-background font-sans antialiased text-foreground">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar Admin: Preline Offcanvas di mobile/tablet, static di desktop -->
        <aside id="admin-sidebar"
               class="hs-overlay [--auto-close:lg] hs-overlay-open:translate-x-0 -translate-x-full transition-all duration-300 transform fixed top-0 inset-s-0 bottom-0 z-80 w-64 max-w-64 border-e border-sidebar-line bg-sidebar flex flex-col shrink-0 justify-between h-screen overflow-y-auto lg:static lg:translate-x-0 lg:transform-none shadow-lg lg:shadow-none hidden lg:flex"
               tabindex="-1"
               role="dialog"
               aria-labelledby="admin-sidebar-title">
            <div>
                <!-- Brand / Header -->
                <div class="p-5 sm:p-6 border-b border-border-line-1 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/logo-himmatun-ayat.avif') }}" 
                             alt="Logo Himmatun Ayat" 
                             class="w-10 h-10 object-contain shrink-0">
                        <div>
                            <h1 id="admin-sidebar-title" class="text-base font-bold text-foreground leading-tight">Admin Panel</h1>
                            <p class="text-xs text-muted-foreground-1 font-medium">Himmatun Ayat Bandung</p>
                        </div>
                    </div>
                    <!-- Tombol Tutup Sidebar di Layar Mobile -->
                    <button type="button" 
                            data-hs-overlay="#admin-sidebar"
                            class="p-1.5 rounded-lg text-muted-foreground hover:text-muted-foreground-2 hover:bg-muted lg:hidden"
                            aria-label="Tutup menu">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="px-4 pt-5 pb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-muted-foreground px-3">MANAJEMEN YAYASAN</span>
                </div>
                <nav class="px-4 pb-4 space-y-1.5 text-sm font-medium">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.dashboard*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            <rect x="3" y="14" width="7" height="7" rx="1.5" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Data Anak Asuh -->
                    <a href="{{ route('admin.anak-asuh.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.anak-asuh.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Data Anak Asuh</span>
                    </a>

                    <!-- Kelola Donasi -->
                    <a href="{{ route('admin.kelola-donasi.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.kelola-donasi.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span>Kelola Donasi</span>
                    </a>

                    <!-- Kelola Kampanye -->
                    <a href="{{ route('admin.kelola-kampanye.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.kelola-kampanye.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.01 5.395m-1.01-5.395l-1.015-.09m1.015.09A23.87 23.87 0 0121 9.75m-1.825 8.625a23.74 23.74 0 01-.38 1.125m.38-1.125l-1.015.09m1.015-.09a23.91 23.91 0 001.01-5.395m-1.01 5.395A23.87 23.87 0 0021 14.25" />
                        </svg>
                        <span>Kelola Kampanye</span>
                    </a>

                    <!-- CMS Berita -->
                    <a href="{{ route('admin.berita.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.berita.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                        <span>CMS Berita</span>
                    </a>

                    <!-- Pengajuan Kegiatan -->
                    <a href="{{ route('admin.pengajuan-kegiatan.index') }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->is('admin/pengajuan-kegiatan*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 9v7.5" />
                            </svg>
                            <span>Pengajuan Kegiatan</span>
                        </div>
                        @php
                            $pendingKegiatanCount = \Illuminate\Support\Facades\Schema::hasTable('pengajuan_kegiatans') 
                                ? \App\Models\PengajuanKegiatan::where('status', 'menunggu')->count() 
                                : 0;
                        @endphp
                        @if($pendingKegiatanCount > 0)
                            <span class="inline-flex items-center justify-center px-2 py-0.5 text-2xs font-bold rounded-full {{ request()->is('admin/pengajuan-kegiatan*') ? 'bg-white text-brand-900' : 'bg-brand-100 text-brand-800' }}">
                                {{ $pendingKegiatanCount }}
                            </span>
                        @endif
                    </a>

                    <!-- Laporan & Ekspor -->
                    <a href="{{ route('admin.laporan-ekspor.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.laporan-ekspor.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span>Laporan & Ekspor</span>
                    </a>

                    <!-- Kelola Admin -->
                    <a href="{{ route('admin.users.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.users.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                        </svg>
                        <span>Kelola Admin</span>
                    </a>

                    <!-- Keamanan Akun -->
                    <a href="{{ route('admin.security.index') }}" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->routeIs('admin.security.*') ? 'bg-brand-700 text-white shadow-xs' : 'text-muted-foreground-2 hover:bg-muted hover:text-foreground' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                        <span>Keamanan Akun</span>
                    </a>
                </nav>
            </div>

            <!-- User Footer (Admin Yayasan / Admin) -->
            <div class="p-4 border-t border-border-line-1 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-brand-700 flex items-center justify-center font-bold text-xs text-white shrink-0">
                        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
                    </div>
                    <div class="text-xs truncate">
                        <p class="font-bold text-foreground leading-tight truncate">{{ auth()->user()->name ?? 'Admin Yayasan' }}</p>
                        <p class="text-muted-foreground font-medium truncate">{{ auth()->user()->email ?? 'Administrator' }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit"
                            data-confirm="Apakah Anda yakin ingin keluar dari sesi Admin Panel?"
                            data-confirm-title="Konfirmasi Keluar"
                            data-confirm-type="warning"
                            data-confirm-button="Ya, Keluar"
                            title="Keluar"
                            class="p-1.5 text-danger hover:text-danger/80 hover:bg-danger-soft rounded-lg transition cursor-pointer">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Workspace (Hanya main yang scroll secara mandiri) -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
            @php
                $notifications = $notifications ?? app(\App\Services\LaporanService::class)->getNotifications(6);
            @endphp
            <!-- Global Topbar (Mobile, Tablet & Desktop) -->
            <header class="h-16 border-b border-border bg-card px-4 sm:px-6 lg:px-8 flex items-center justify-between shrink-0 z-30"
                    x-data="{ 
                        unreadCount: {{ (int) ($notifications['unread_count'] ?? 0) }},
                        markAllRead() {
                            this.unreadCount = 0;
                        }
                    }">
                <!-- Left: Hamburger (Mobile) + Breadcrumb / Title (Desktop) -->
                <div class="flex items-center gap-3">
                    <button type="button" 
                            data-hs-overlay="#admin-sidebar"
                            aria-controls="admin-sidebar"
                            class="p-2 -ml-2 rounded-xl text-muted-foreground-2 hover:text-foreground hover:bg-muted transition lg:hidden"
                            aria-label="Buka menu">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div class="flex items-center gap-2 lg:hidden">
                        <img src="{{ asset('images/logo-himmatun-ayat.avif') }}" 
                             alt="Logo Himmatun Ayat" 
                             class="w-8 h-8 object-contain shrink-0">
                        <span class="font-bold text-sm text-foreground">@yield('page-title', $breadcrumb ?? $moduleBreadcrumb)</span>
                    </div>

                    <!-- Desktop Breadcrumb Context -->
                    <div class="hidden lg:flex items-center gap-2 text-xs font-medium text-muted-foreground">
                        <span class="text-brand-800 font-semibold">Yayasan Himmatun Ayat</span>
                        <svg class="w-3.5 h-3.5 text-muted-foreground/60" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                        <div class="inline-flex items-center gap-2 text-foreground font-bold">
                            @yield('breadcrumb', $breadcrumb ?? $moduleBreadcrumb)
                        </div>
                    </div>
                </div>

                <!-- Right Action Bar: Notification Bell + Quick Actions + Profile -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    <!-- Notification Bell Dropdown (Preline UI + Alpine.js) -->
                    <div class="hs-dropdown relative inline-flex [--placement:bottom-right]">
                        <button id="hs-header-notification-dropdown" 
                                type="button" 
                                class="hs-dropdown-toggle relative p-2 rounded-xl text-muted-foreground-2 hover:text-foreground hover:bg-muted focus:outline-hidden transition cursor-pointer"
                                aria-haspopup="menu" 
                                aria-expanded="false" 
                                aria-controls="hs-header-notification-menu"
                                aria-label="Buka notifikasi">
                            <svg class="w-5 h-5 sm:w-5.5 sm:h-5.5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <!-- Badge Unread Counter (Alpine x-show) -->
                            <span x-show="unreadCount > 0" 
                                  x-text="unreadCount > 99 ? '99+' : unreadCount"
                                  class="absolute top-1 right-1 flex items-center justify-center min-w-4.5 h-4.5 px-1 text-[10px] font-extrabold text-white bg-danger rounded-full ring-2 ring-card shadow-xs animate-pulse">
                                {{ $notifications['unread_count'] ?? 0 }}
                            </span>
                        </button>

                        <!-- Notification Dropdown Panel -->
                        <div id="hs-header-notification-menu" 
                             class="hs-dropdown-menu hs-dropdown-open:opacity-100 hidden mt-2 w-80 sm:w-96 max-w-[90vw] rounded-2xl border border-border bg-card p-0 opacity-0 shadow-xl transition-[opacity,margin] duration z-50 overflow-hidden" 
                             role="menu" 
                             aria-labelledby="hs-header-notification-dropdown">
                            
                            <!-- Header Dropdown -->
                            <div class="p-3.5 sm:p-4 bg-muted/60 border-b border-border flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-sm text-foreground">Pusat Notifikasi</h3>
                                    <span x-show="unreadCount > 0" 
                                          class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                        <span x-text="unreadCount"></span> Baru
                                    </span>
                                </div>
                                <button type="button" 
                                        @click="markAllRead()"
                                        x-show="unreadCount > 0"
                                        class="text-2xs text-brand-700 hover:text-brand-900 font-semibold transition cursor-pointer">
                                    Tandai Dibaca
                                </button>
                            </div>

                            <!-- List of Notifications -->
                            <div class="max-h-80 overflow-y-auto divide-y divide-border-line-1">
                                @forelse($notifications['items'] ?? [] as $notif)
                                    <a href="{{ $notif['url'] ?? '#' }}" 
                                       class="p-3 sm:p-3.5 flex items-start gap-3 hover:bg-muted/70 transition block text-left">
                                        <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 mt-0.5
                                            @if(($notif['badge_color'] ?? '') === 'amber') bg-amber-50 text-amber-600 border border-amber-200
                                            @elseif(($notif['badge_color'] ?? '') === 'emerald') bg-emerald-50 text-emerald-600 border border-emerald-200
                                            @elseif(($notif['badge_color'] ?? '') === 'blue') bg-blue-50 text-blue-600 border border-blue-200
                                            @else bg-purple-50 text-purple-600 border border-purple-200 @endif">
                                            @if(($notif['icon'] ?? '') === 'wallet')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3" />
                                                </svg>
                                            @elseif(($notif['icon'] ?? '') === 'heart')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                                                </svg>
                                            @elseif(($notif['icon'] ?? '') === 'user')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                                                </svg>
                                            @endif
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1.5">
                                                <p class="text-xs font-bold text-foreground truncate">{{ $notif['title'] }}</p>
                                                <span class="text-[10px] text-muted-foreground shrink-0 font-medium">{{ $notif['time_ago'] }}</span>
                                            </div>
                                            <p class="text-xs text-muted-foreground-1 mt-0.5 line-clamp-2 leading-relaxed">{{ $notif['desc'] }}</p>
                                        </div>
                                    </a>
                                @empty
                                    <div class="p-6 text-center text-xs text-muted-foreground">
                                        Tidak ada notifikasi baru saat ini.
                                    </div>
                                @endforelse
                            </div>

                            <!-- Footer Dropdown -->
                            <div class="p-2.5 bg-muted/40 border-t border-border text-center">
                                <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold text-brand-700 hover:text-brand-900 transition">
                                    Lihat Semua di Dashboard &rarr;
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="h-6 w-px bg-border"></div>

                    <!-- User Profile Quick Badge -->
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-full bg-brand-700 flex items-center justify-center font-bold text-xs text-white shadow-2xs">
                            AD
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-xs font-bold text-foreground leading-tight">Admin Yayasan</p>
                            <p class="text-[10px] text-muted-foreground font-medium">Petugas Operasional</p>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto overflow-x-hidden min-w-0 max-w-full px-4 sm:px-6 lg:px-8 py-5 pb-16">
                {{ $slot ?? '' }}
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        (function () {
            function positionSlidingPill(group, animate) {
                const bg = group.querySelector('[data-sliding-pill-bg]');
                const activeItem = group.querySelector('[data-pill-active="true"]');
                if (!bg || !activeItem) {
                    if (bg) bg.style.opacity = '0';
                    return;
                }

                if (!animate) {
                    bg.style.transition = 'none';
                } else {
                    bg.style.transition = '';
                }

                const left = Math.round(activeItem.offsetLeft);
                const width = Math.round(activeItem.offsetWidth);
                const top = Math.round(activeItem.offsetTop);
                const height = Math.round(activeItem.offsetHeight);

                bg.style.opacity = '1';
                bg.style.width = width + 'px';
                bg.style.height = height + 'px';
                bg.style.transform = 'translate3d(' + left + 'px, ' + top + 'px, 0)';

                if (!animate) {
                    bg.offsetHeight;
                    bg.style.transition = '';
                }
            }

            function initSlidingPills(root, animate) {
                (root || document).querySelectorAll('[data-sliding-pills]').forEach(function (group) {
                    positionSlidingPill(group, !!animate);
                });
            }

            window.initAdminSlidingPills = initSlidingPills;

            window.addEventListener('resize', function () {
                initSlidingPills(document, false);
            });

            document.addEventListener('DOMContentLoaded', function () {
                initSlidingPills(document, false);
            });

            let activeAbortController = null;

            async function loadAdminFilteredUrl(scopeEl, targetUrl, pushHistory) {
                const resultsEl = scopeEl.querySelector('[data-admin-filter-results]');
                if (!resultsEl) {
                    window.location.href = targetUrl;
                    return;
                }

                if (activeAbortController) {
                    activeAbortController.abort();
                }
                activeAbortController = new AbortController();

                resultsEl.style.transition = 'opacity 150ms ease-out';
                resultsEl.style.opacity = '0.6';

                try {
                    const response = await fetch(targetUrl, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        },
                        credentials: 'same-origin',
                        signal: activeAbortController.signal
                    });

                    if (!response.ok) {
                        window.location.href = targetUrl;
                        return;
                    }

                    const html = await response.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const scopeName = scopeEl.getAttribute('data-admin-filter-scope');
                    const newScope = doc.querySelector('[data-admin-filter-scope="' + scopeName + '"]');

                    if (!newScope) {
                        window.location.href = targetUrl;
                        return;
                    }

                    const newResults = newScope.querySelector('[data-admin-filter-results]');
                    if (newResults) {
                        resultsEl.innerHTML = newResults.innerHTML;
                    }

                    // Sinkronkan juga hidden input pada form & href link filter lain agar multi-filter (search + sort + status) tetap konsisten
                    scopeEl.querySelectorAll('[data-admin-filter-sync]').forEach(function (targetNode) {
                        const syncKey = targetNode.getAttribute('data-admin-filter-sync');
                        const freshNode = newScope.querySelector('[data-admin-filter-sync="' + syncKey + '"]');
                        if (freshNode) {
                            if (freshNode.hasAttribute('data-sliding-pills')) {
                                // Sinkronkan href & active state tombol tanpa menghancurkan sliding pill indicator
                                const oldLinks = targetNode.querySelectorAll('[data-admin-filter-link]');
                                const newLinks = freshNode.querySelectorAll('[data-admin-filter-link]');
                                if (oldLinks.length === newLinks.length) {
                                    oldLinks.forEach(function (oldLink, idx) {
                                        const freshLink = newLinks[idx];
                                        oldLink.setAttribute('href', freshLink.getAttribute('href'));
                                        oldLink.setAttribute('class', freshLink.getAttribute('class'));
                                        oldLink.setAttribute('data-pill-active', freshLink.getAttribute('data-pill-active') || 'false');
                                        oldLink.innerHTML = freshLink.innerHTML;
                                    });
                                    positionSlidingPill(targetNode, true);
                                    return;
                                }
                            }
                            const activeEl = document.activeElement;
                            if (activeEl && targetNode.contains(activeEl) && activeEl.matches('input:not([type="hidden"]), textarea')) {
                                targetNode.querySelectorAll('input[type="hidden"]').forEach(function (hiddenInput) {
                                    hiddenInput.remove();
                                });
                                freshNode.querySelectorAll('input[type="hidden"]').forEach(function (freshHidden) {
                                    targetNode.prepend(freshHidden.cloneNode(true));
                                });
                                return;
                            }
                            targetNode.innerHTML = freshNode.innerHTML;
                        }
                    });

                    if (pushHistory) {
                        window.history.pushState({ adminFilterScope: scopeName, url: targetUrl }, '', targetUrl);
                    }

                    resultsEl.style.opacity = '1';

                    if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
                        window.HSStaticMethods.autoInit();
                    }
                } catch (err) {
                    if (err.name !== 'AbortError') {
                        window.location.href = targetUrl;
                    }
                }
            }

            // Delegated click handler untuk pill filter, reset link, dan pagination tanpa reload
            document.addEventListener('click', function (e) {
                const link = e.target.closest('[data-admin-filter-link], [data-admin-filter-results] nav a');
                if (!link) return;
                if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

                const scopeEl = link.closest('[data-admin-filter-scope]');
                if (!scopeEl) return;

                const href = link.getAttribute('href');
                if (!href || href === '#') return;

                e.preventDefault();

                const dropdownEl = link.closest('.hs-dropdown');
                if (dropdownEl && window.HSDropdown && typeof window.HSDropdown.close === 'function') {
                    try {
                        window.HSDropdown.close(dropdownEl);
                    } catch (err) {}
                }

                // Jika link berada dalam sliding pills, geser background pill langsung
                const pillGroup = link.closest('[data-sliding-pills]');
                if (pillGroup) {
                    pillGroup.querySelectorAll('[data-pill-active]').forEach(function (btn) {
                        btn.setAttribute('data-pill-active', 'false');
                        btn.classList.remove('text-white');
                        btn.classList.add('text-muted-foreground-2', 'hover:text-brand-600', 'hover:bg-brand-50/70');
                    });
                    link.setAttribute('data-pill-active', 'true');
                    link.classList.remove('text-muted-foreground-2', 'hover:text-foreground', 'hover:text-brand-600', 'hover:bg-brand-50/70');
                    link.classList.add('text-white');
                    positionSlidingPill(pillGroup, true);
                }

                loadAdminFilteredUrl(scopeEl, href, true);
            });

            // Delegated submit & input/change handler untuk form filter & pencarian tanpa reload
            let searchDebounceTimer = null;
            document.addEventListener('submit', function (e) {
                const form = e.target.closest('form[data-admin-filter-form]');
                if (!form) return;
                const scopeEl = form.closest('[data-admin-filter-scope]');
                if (!scopeEl) return;

                e.preventDefault();

                const dropdownEl = form.closest('.hs-dropdown');
                if (dropdownEl && window.HSDropdown && typeof window.HSDropdown.close === 'function') {
                    try {
                        window.HSDropdown.close(dropdownEl);
                    } catch (err) {}
                }

                const action = form.getAttribute('action') || window.location.pathname;
                const params = new URLSearchParams(new FormData(form));
                const url = action + (params.toString() ? '?' + params.toString() : '');
                loadAdminFilteredUrl(scopeEl, url, true);
            });

            document.addEventListener('input', function (e) {
                const input = e.target.closest('form[data-admin-filter-form] input[name="search"]');
                if (!input) return;
                const form = input.closest('form[data-admin-filter-form]');
                const scopeEl = form.closest('[data-admin-filter-scope]');
                if (!scopeEl) return;

                clearTimeout(searchDebounceTimer);
                searchDebounceTimer = setTimeout(function () {
                    const action = form.getAttribute('action') || window.location.pathname;
                    const params = new URLSearchParams(new FormData(form));
                    const url = action + (params.toString() ? '?' + params.toString() : '');
                    loadAdminFilteredUrl(scopeEl, url, true);
                }, 350);
            });

            document.addEventListener('change', function (e) {
                const selectOrDate = e.target.closest('form[data-admin-filter-form] select, form[data-admin-filter-form] input[type="date"]');
                if (!selectOrDate) return;
                const form = selectOrDate.closest('form[data-admin-filter-form]');
                if (form.hasAttribute('data-manual-submit')) return;
                const scopeEl = form.closest('[data-admin-filter-scope]');
                if (!scopeEl) return;

                const action = form.getAttribute('action') || window.location.pathname;
                const params = new URLSearchParams(new FormData(form));
                const url = action + (params.toString() ? '?' + params.toString() : '');
                loadAdminFilteredUrl(scopeEl, url, true);
            });

            window.addEventListener('popstate', function () {
                const scopeEl = document.querySelector('[data-admin-filter-scope]');
                if (scopeEl) {
                    loadAdminFilteredUrl(scopeEl, window.location.href, false);
                }
            });
        })();
    </script>

    <!-- Global Preline Confirmation Modal -->
    <x-confirm-modal />
</body>
</html>
