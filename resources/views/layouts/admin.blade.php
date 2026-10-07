<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Admin Panel - Himmatun Ayat Bandung' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-screen overflow-hidden bg-[#F8FAFC] font-sans antialiased text-slate-800">
    <div class="flex h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
        <!-- Backdrop Overlay untuk Mobile/Tablet -->
        <div x-show="sidebarOpen" 
             x-cloak 
             @click="sidebarOpen = false" 
             class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-2xs transition-opacity lg:hidden"
             style="display: none;"></div>

        <!-- Sidebar Admin (Sesuai Desain UI: Fixed di Desktop, Off-Canvas Drawer di Mobile/Tablet) -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
               class="fixed inset-y-0 left-0 z-50 w-64 border-r border-slate-200 bg-white flex flex-col shrink-0 justify-between h-screen overflow-y-auto transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 shadow-lg lg:shadow-none">
            <div>
                <!-- Brand / Header -->
                <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 shadow-xs text-emerald-800 font-bold text-sm">
                            <svg class="w-6 h-6 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-base font-bold text-slate-900 leading-tight">Admin Panel</h1>
                            <p class="text-xs text-slate-500 font-medium">Himmatun Ayat Bandung</p>
                        </div>
                    </div>
                    <!-- Tombol Tutup Sidebar di Layar Mobile -->
                    <button type="button" 
                            @click="sidebarOpen = false" 
                            class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 lg:hidden">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Navigation Links -->
                <div class="px-4 pt-5 pb-2">
                    <span class="text-[11px] font-semibold uppercase tracking-wider text-slate-400 px-3">MANAJEMEN YAYASAN</span>
                </div>
                <nav class="px-4 pb-4 space-y-1.5 text-sm font-medium">
                    <!-- Dashboard -->
                    <a href="/admin/dashboard" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition font-semibold {{ request()->is('admin/dashboard*') ? 'bg-[#2D6A4F] text-white shadow-xs' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="3" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="3" width="7" height="7" rx="1.5" />
                            <rect x="14" y="14" width="7" height="7" rx="1.5" />
                            <rect x="3" y="14" width="7" height="7" rx="1.5" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <!-- Data Anak Asuh -->
                    <a href="/admin/anak-asuh" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->is('admin/anak-asuh*') ? 'bg-[#2D6A4F] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span>Data Anak Asuh</span>
                    </a>

                    <!-- Kelola Donasi -->
                    <a href="/admin/kelola-donasi" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->is('admin/kelola-donasi*') ? 'bg-[#2D6A4F] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                        <span>Kelola Donasi</span>
                    </a>

                    <!-- Kelola Kampanye -->
                    <a href="/admin/kelola-kampanye" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->is('admin/kelola-kampanye*') ? 'bg-[#2D6A4F] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.01 5.395m-1.01-5.395l-1.015-.09m1.015.09A23.87 23.87 0 0121 9.75m-1.825 8.625a23.74 23.74 0 01-.38 1.125m.38-1.125l-1.015.09m1.015-.09a23.91 23.91 0 001.01-5.395m-1.01 5.395A23.87 23.87 0 0021 14.25" />
                        </svg>
                        <span>Kelola Kampanye</span>
                    </a>

                    <!-- CMS Berita -->
                    <a href="/admin/cms-berita" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->is('admin/cms-berita*') ? 'bg-[#2D6A4F] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z" />
                        </svg>
                        <span>CMS Berita</span>
                    </a>

                    <!-- Laporan & Ekspor -->
                    <a href="/admin/laporan-ekspor" 
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl transition {{ request()->is('admin/laporan-ekspor*') ? 'bg-[#2D6A4F] text-white' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                        <span>Laporan & Ekspor</span>
                    </a>
                </nav>
            </div>

            <!-- User footer -->
            <div class="p-4 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-9 h-9 rounded-full bg-[#2D6A4F] flex items-center justify-center font-bold text-xs text-white shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div class="text-xs truncate">
                        <p class="font-bold text-slate-800 leading-tight">Admin Yayasan</p>
                        <p class="text-slate-400 font-medium">Admin</p>
                    </div>
                </div>
                <button type="button" title="Keluar" class="p-1.5 text-red-500 hover:text-red-700 hover:bg-red-50 rounded-lg transition">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Main Workspace (Hanya main yang scroll secara mandiri) -->
        <div class="flex-1 flex flex-col h-screen overflow-hidden min-w-0">
            <!-- Topbar Mobile & Tablet (Tombol Hamburger Menu) -->
            <header class="lg:hidden h-16 border-b border-slate-200 bg-white px-4 sm:px-6 flex items-center justify-between shrink-0 z-30">
                <button type="button" 
                        @click="sidebarOpen = true" 
                        class="p-2 -ml-2 rounded-xl text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition"
                        title="Buka Menu">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center shrink-0 text-emerald-800 font-bold text-xs">
                        HA
                    </div>
                    <span class="font-bold text-sm text-slate-900">Admin Panel</span>
                </div>

                <div class="w-8 h-8 rounded-full bg-[#2D6A4F] flex items-center justify-center font-bold text-xs text-white">
                    AD
                </div>
            </header>

            <!-- Scrollable Content Area -->
            <main class="flex-1 overflow-y-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-5 pb-16">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
