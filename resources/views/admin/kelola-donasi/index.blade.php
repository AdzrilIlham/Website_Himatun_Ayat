@extends('layouts.admin')

@section('content')
<div class="space-y-6 pb-12 w-full max-w-full">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-3.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-lg leading-none">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="p-3.5 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-xs sm:text-sm">
            <p class="font-bold mb-1">Terjadi kesalahan validasi:</p>
            <ul class="list-disc pl-5 space-y-0.5">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Top Header & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-[#1B4332]">Kelola Donasi</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5 font-medium">{{ $totalDonaturTerkini }} donatur terdaftar</p>
        </div>
        <div class="flex items-center gap-2.5">
            <!-- Tombol + Tambah Donatur (Preline Modal Trigger) -->
            <button type="button" 
                    data-hs-overlay="#modal-tambah-donatur"
                    class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-[#2D6A4F] text-white text-xs sm:text-sm font-semibold hover:bg-[#1B4332] shadow-xs transition">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>+ Tambah Donatur</span>
            </button>

            <!-- Tombol ↓ Export -->
            <a href="{{ route('admin.kelola-donasi.export') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl border border-slate-300 bg-white text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition shadow-2xs">
                <svg class="w-4 h-4 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M7.5 12l4.5 4.5m0 0l4.5-4.5M12 3v13.5" />
                </svg>
                <span>Export</span>
            </a>
        </div>
    </div>

    <!-- 4 Top Stat Cards (Sesuai Desain Tangkapan Layar) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Card 1: TOTAL PENERIMAAN ZIS -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">TOTAL PENERIMAAN ZIS</span>
                    <div class="w-9 h-9 rounded-xl bg-[#D8F3DC] text-[#1B4332] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2.5" />
                            <path d="M6 12h.01M18 12h.01" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. {{ number_format($metrics['total_zis'], 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-slate-500 font-medium mt-1">Akumulasi Infaq, Zakat, & Wakaf Masuk</p>
                </div>
            </div>
            <div class="mt-4 pt-2 border-t border-slate-50">
                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800">
                    <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.318 4.318L20.25 7.5m0 0h-5.25m5.25 0v5.25" />
                    </svg>
                    <span>+{{ $metrics['persen_bulan_lalu'] }}% dibanding bulan lalu</span>
                </span>
            </div>
        </div>

        <!-- Card 2: PENYALURAN PROGRAM -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">PENYALURAN PROGRAM</span>
                    <div class="w-9 h-9 rounded-xl bg-[#FEF08A] text-[#854D0E] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.455.517 2.822 1.407 3.896" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. {{ number_format($metrics['penyaluran_program'], 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-slate-500 font-medium mt-1">Akumulasi Infaq, Zakat, & Wakaf Masuk</p>
                </div>
            </div>
            <div class="mt-4 pt-2">
                <!-- Thin Progress Bar -->
                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                    <div class="bg-[#1B4332] h-1.5 rounded-full" style="width: {{ $metrics['rasio_efektivitas'] }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-2 text-[11px]">
                    <span class="font-bold text-slate-800">Rasio Penyaluran</span>
                    <span class="font-bold text-[#854D0E]">{{ $metrics['rasio_efektivitas'] }}% Efektivitas</span>
                </div>
            </div>
        </div>

        <!-- Card 3: OPERASIONAL HAK MILIK -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">OPERASIONAL HAK MILIK</span>
                    <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.97zm-13.5 0C4.24 5.113 3.24 5.287 2.25 5.49m3-.52L2.63 15.696c-.122.499.106 1.028.589 1.202a5.989 5.989 0 002.031.352 5.988 5.988 0 002.031-.352c.483-.174.711-.703.59-1.202L5.25 4.97z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. {{ number_format($metrics['operasional_hak_milik'], 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-slate-500 font-medium mt-1">Rasio {{ $metrics['rasio_operasional'] }}% dari dana terkumpul</p>
                </div>
            </div>
            <div class="mt-4 pt-2 border-t border-slate-50">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-amber-800">
                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Di bawah batas maks. BAZNAS (12.5%)</span>
                </span>
            </div>
        </div>

        <!-- Card 4: SALDO KAS & CADANGAN -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">SALDO KAS & CADANGAN</span>
                    <div class="w-9 h-9 rounded-xl bg-[#D8F3DC] text-[#1B4332] flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21" />
                        </svg>
                    </div>
                </div>
                <div class="mt-2">
                    <span class="text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. {{ number_format($metrics['saldo_kas'], 0, ',', '.') }}
                    </span>
                    <p class="text-xs text-slate-500 font-medium mt-1">Rekening giro amanah BRI Syariah</p>
                </div>
            </div>
            <div class="mt-4 pt-2 border-t border-slate-50">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-800">
                    <svg class="w-3.5 h-3.5 text-emerald-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span>Cadangan darurat & asrama binaan</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Alokasi Penyaluran Program per Klaster Asnaf Card -->
    <div class="bg-[#DCE7E1] rounded-2xl p-5 sm:p-6 border border-emerald-900/10 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-bold text-slate-900">Alokasi Penyaluran Program per Klaster Asnaf</h2>
                <p class="text-xs text-slate-600 mt-0.5">Distribusi realisasi pemanfaatan dana sedekah dan zakat secara terperinci.</p>
            </div>
            <div class="text-xs font-semibold text-slate-700 sm:text-right shrink-0">
                Total Realisasi: <span class="font-bold text-slate-900">Rp {{ number_format($asnaf['total_realisasi'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Multi-segment Colored Progress Bar -->
        <div class="mt-4 w-full h-3 rounded-full overflow-hidden flex gap-0.5 bg-slate-200/60 shadow-inner">
            <div style="width: {{ $asnaf['pendidikan']['persen'] }}%;" class="bg-[#1B4332] h-full" title="Pendidikan: {{ $asnaf['pendidikan']['persen'] }}%"></div>
            <div style="width: {{ $asnaf['nutrisi']['persen'] }}%;" class="bg-[#74A892] h-full" title="Nutrisi: {{ $asnaf['nutrisi']['persen'] }}%"></div>
            <div style="width: {{ $asnaf['kesehatan']['persen'] }}%;" class="bg-[#E5B84B] h-full" title="Kesehatan: {{ $asnaf['kesehatan']['persen'] }}%"></div>
            <div style="width: {{ $asnaf['asrama']['persen'] }}%;" class="bg-[#94A3B8] h-full" title="Asrama: {{ $asnaf['asrama']['persen'] }}%"></div>
        </div>

        <!-- 4 Legend Breakdown Items -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-4 pt-1">
            <!-- 1. Pendidikan -->
            <div class="flex items-start gap-2.5">
                <span class="w-3 h-3 rounded-full bg-[#1B4332] shrink-0 mt-0.5"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">{{ $asnaf['pendidikan']['nama'] }}</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp {{ number_format($asnaf['pendidikan']['nominal'], 0, ',', '.') }} ({{ $asnaf['pendidikan']['persen'] }}%)</p>
                </div>
            </div>

            <!-- 2. Nutrisi -->
            <div class="flex items-start gap-2.5">
                <span class="w-3 h-3 rounded-full bg-[#74A892] shrink-0 mt-0.5"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">{{ $asnaf['nutrisi']['nama'] }}</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp {{ number_format($asnaf['nutrisi']['nominal'], 0, ',', '.') }} ({{ $asnaf['nutrisi']['persen'] }}%)</p>
                </div>
            </div>

            <!-- 3. Kesehatan -->
            <div class="flex items-start gap-2.5">
                <span class="w-3 h-3 rounded-full bg-[#E5B84B] shrink-0 mt-0.5"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">{{ $asnaf['kesehatan']['nama'] }}</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp {{ number_format($asnaf['kesehatan']['nominal'], 0, ',', '.') }} ({{ $asnaf['kesehatan']['persen'] }}%)</p>
                </div>
            </div>

            <!-- 4. Asrama -->
            <div class="flex items-start gap-2.5">
                <span class="w-3 h-3 rounded-full bg-[#94A3B8] shrink-0 mt-0.5"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">{{ $asnaf['asrama']['nama'] }}</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp {{ number_format($asnaf['asrama']['nominal'], 0, ',', '.') }} ({{ $asnaf['asrama']['persen'] }}%)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Search Bar & Sorting Pills -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2">
        <!-- Search Input Form -->
        <form method="GET" action="{{ route('admin.kelola-donasi.index') }}" class="w-full sm:max-w-md">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama / email..." 
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-2 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 focus:border-[#2D6A4F] focus:outline-hidden transition shadow-2xs">
            </div>
        </form>

        <!-- Segmented Sorting Pills -->
        <div class="flex items-center gap-2 self-start sm:self-auto text-xs">
            <span class="text-slate-500 font-medium mr-1">Urutkan:</span>
            <div class="inline-flex rounded-full bg-slate-100 p-0.5 border border-slate-200/80">
                <!-- Total Donasi -->
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'total_donasi']) }}"
                   class="px-3.5 py-1 rounded-full font-semibold transition {{ $sort === 'total_donasi' || !$sort ? 'bg-[#1B4332] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Total Donasi
                </a>

                <!-- Frekuensi -->
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'frekuensi']) }}"
                   class="px-3.5 py-1 rounded-full font-semibold transition {{ $sort === 'frekuensi' ? 'bg-[#1B4332] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Frekuensi
                </a>

                <!-- Nama -->
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'nama']) }}"
                   class="px-3.5 py-1 rounded-full font-semibold transition {{ $sort === 'nama' ? 'bg-[#1B4332] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Nama
                </a>
            </div>
        </div>
    </div>

    <!-- Table Container (Sesuai Desain Tangkapan Layar: Rounded Gray Pill Header) -->
    <div class="space-y-2">
        <!-- Rounded Pill Table Header -->
        <div class="bg-[#DDE5E0] text-slate-700 font-bold uppercase text-[11px] tracking-wider rounded-2xl px-6 py-3.5 flex items-center shadow-2xs">
            <div class="w-[28%] text-left">DONATUR</div>
            <div class="w-[28%] text-left">KONTAK</div>
            <div class="w-[18%] text-left">KAMPANYE</div>
            <div class="w-[16%] text-left">TOTAL DONASI</div>
            <div class="w-[10%] text-center">AKSI</div>
        </div>

        <!-- Table Body Rows -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs divide-y divide-slate-100 overflow-hidden">
            @forelse($donasiList as $donasi)
                @php
                    // Ambil inisial nama donatur
                    $words = explode(' ', trim($donasi->nama_donatur));
                    $initials = '';
                    if (count($words) >= 2) {
                        $initials = strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                    } else {
                        $initials = strtoupper(substr($words[0], 0, 2));
                    }
                @endphp
                <div class="px-6 py-3.5 flex items-center hover:bg-slate-50/70 transition">
                    <!-- DONATUR -->
                    <div class="w-[28%] flex items-center gap-3 pr-2 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-[#1E293B] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                            {{ $initials ?: 'HA' }}
                        </div>
                        <span class="font-bold text-slate-900 text-sm truncate">{{ $donasi->nama_donatur }}</span>
                    </div>

                    <!-- KONTAK -->
                    <div class="w-[28%] text-xs min-w-0 pr-2">
                        <p class="text-slate-700 font-medium truncate">{{ $donasi->email ?: 'donatur@himmatunayat.org' }}</p>
                        <p class="text-slate-500 mt-0.5 truncate">{{ $donasi->no_whatsapp ?: '+62 816-5678-9012' }}</p>
                    </div>

                    <!-- KAMPANYE -->
                    <div class="w-[18%] pr-2">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold bg-[#D0E2D7] text-[#1B4332] truncate max-w-full">
                            {{ $donasi->kampanye ? $donasi->kampanye->judul : 'Renovasi' }}
                        </span>
                    </div>

                    <!-- TOTAL DONASI -->
                    <div class="w-[16%]">
                        <span class="font-extrabold text-sm sm:text-base text-slate-900">
                            Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- AKSI (Terima & Hapus) -->
                    <div class="w-[10%] flex items-center justify-center gap-1.5 shrink-0">
                        <!-- Tombol Terima -->
                        @if($donasi->status !== 'verified')
                            <form method="POST" action="{{ route('admin.kelola-donasi.terima', $donasi->id) }}">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Verifikasi dan terima donasi Rp{{ number_format($donasi->nominal, 0, ',', '.') }} dari {{ $donasi->nama_donatur }}?')"
                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-[#D0E2D7] text-[#1B4332] hover:bg-emerald-200 transition shadow-2xs">
                                    Terima
                                </button>
                            </form>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-[#D0E2D7] text-[#1B4332] shadow-2xs cursor-default">
                                Terima
                            </span>
                        @endif

                        <!-- Tombol Hapus -->
                        <form method="POST" action="{{ route('admin.kelola-donasi.destroy', $donasi->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Yakin ingin menghapus donasi dari {{ $donasi->nama_donatur }}?')"
                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-[#FCE7E7] text-[#DC2626] hover:bg-rose-200 transition shadow-2xs">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400 text-sm">
                    Tidak ada data donasi yang sesuai dengan pencarian.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Table Footer: Counter & Pagination -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500 pt-2 px-2">
        <div class="font-medium text-center sm:text-left">
            Menampilkan {{ $donasiList->firstItem() ?? 0 }}-{{ $donasiList->lastItem() ?? 0 }} dari {{ $donasiList->total() }} Data Donasi
        </div>

        @if ($donasiList->hasPages())
            <nav class="flex items-center justify-center sm:justify-end gap-1.5">
                {{-- Previous Link --}}
                @if ($donasiList->onFirstPage())
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </span>
                @else
                    <a href="{{ $donasiList->previousPageUrl() }}" 
                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 bg-white border border-slate-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($donasiList->getUrlRange(1, $donasiList->lastPage()) as $page => $url)
                    @if ($page == $donasiList->currentPage())
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-xs text-white bg-[#1B4332] shadow-xs">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="w-8 h-8 rounded-lg flex items-center justify-center font-semibold text-xs text-slate-700 bg-white hover:bg-slate-100 border border-slate-200 transition">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next Link --}}
                @if ($donasiList->hasMorePages())
                    <a href="{{ $donasiList->nextPageUrl() }}" 
                       class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-600 hover:text-slate-900 bg-white border border-slate-200 transition">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                @else
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-300 bg-white border border-slate-200 cursor-not-allowed">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                @endif
            </nav>
        @endif
    </div>

    <!-- Modal Preline: + Tambah Donatur -->
    <div id="modal-tambah-donatur" 
         class="hs-overlay hidden fixed inset-0 z-60 overflow-x-hidden overflow-y-auto pointer-events-none"
         role="dialog" 
         tabindex="-1" 
         aria-labelledby="modal-tambah-donatur-label">
        <div class="hs-overlay-open:opacity-100 hs-overlay-open:duration-500 opacity-0 transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
            <div class="w-full flex flex-col bg-white border border-slate-200 shadow-xl rounded-2xl pointer-events-auto overflow-hidden">
                <div class="flex justify-between items-center py-3.5 px-5 border-b border-slate-100 bg-slate-50/70">
                    <h3 id="modal-tambah-donatur-label" class="font-bold text-slate-900 text-sm">
                        Tambah Data Donatur
                    </h3>
                    <button type="button" 
                            class="inline-flex justify-center items-center size-8 rounded-full text-slate-500 hover:bg-slate-200 transition focus:outline-hidden"
                            data-hs-overlay="#modal-tambah-donatur">
                        <span class="sr-only">Tutup</span>
                        <svg class="shrink-0 size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form Tambah Donatur -->
                <form action="{{ route('admin.kelola-donasi.store') }}" method="POST">
                    @csrf
                    <div class="p-5 space-y-4 text-xs sm:text-sm">
                        <!-- Nama Donatur -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Nama Donatur <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_donatur" required placeholder="Contoh: Hendra Wijaya" 
                                   class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-slate-800 placeholder:text-slate-400 focus:border-[#2D6A4F] focus:outline-hidden">
                        </div>

                        <!-- Email & WhatsApp Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Email Donatur</label>
                                <input type="email" name="email" placeholder="hendra.w@gmail.com" 
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-slate-800 placeholder:text-slate-400 focus:border-[#2D6A4F] focus:outline-hidden">
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nomor WhatsApp</label>
                                <input type="text" name="no_whatsapp" placeholder="+62 816-5678-9012" 
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-slate-800 placeholder:text-slate-400 focus:border-[#2D6A4F] focus:outline-hidden">
                            </div>
                        </div>

                        <!-- Program Kampanye & Nominal -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Program Kampanye</label>
                                <select name="kampanye_id" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-800 focus:border-[#2D6A4F] focus:outline-hidden">
                                    <option value="">Umum / Infaq Bebas</option>
                                    @foreach($kampanyeList as $k)
                                        <option value="{{ $k->id }}">{{ $k->judul }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Nominal Donasi (Rp) <span class="text-rose-500">*</span></label>
                                <input type="number" name="nominal" required min="10000" step="1000" placeholder="3200000" 
                                       class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-slate-800 placeholder:text-slate-400 focus:border-[#2D6A4F] focus:outline-hidden">
                            </div>
                        </div>

                        <!-- Metode & Status -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Metode Pembayaran</label>
                                <select name="metode_pembayaran" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-800 focus:border-[#2D6A4F] focus:outline-hidden">
                                    <option value="Transfer Bank (BSI)">Transfer Bank (BSI)</option>
                                    <option value="Transfer Bank (BCA)">Transfer Bank (BCA)</option>
                                    <option value="Transfer Bank (Mandiri)">Transfer Bank (Mandiri)</option>
                                    <option value="QRIS Manual">QRIS Manual</option>
                                </select>
                            </div>
                            <div>
                                <label class="block font-semibold text-slate-700 mb-1">Status Verifikasi</label>
                                <select name="status" class="w-full rounded-xl border border-slate-300 px-3 py-2 text-slate-800 focus:border-[#2D6A4F] focus:outline-hidden">
                                    <option value="verified">Langsung Terverifikasi (Terima)</option>
                                    <option value="pending">Menunggu Verifikasi (Pending)</option>
                                </select>
                            </div>
                        </div>

                        <!-- Pesan Doa / Harapan -->
                        <div>
                            <label class="block font-semibold text-slate-700 mb-1">Pesan Doa / Harapan</label>
                            <textarea name="pesan_doa" rows="2" placeholder="Semoga berkah dan bermanfaat untuk santri..." 
                                      class="w-full rounded-xl border border-slate-300 px-3.5 py-2 text-slate-800 placeholder:text-slate-400 focus:border-[#2D6A4F] focus:outline-hidden"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end items-center gap-x-2 py-3 px-5 border-t border-slate-100 bg-slate-50/50">
                        <button type="button" 
                                class="py-2 px-3.5 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl border border-slate-300 bg-white text-slate-700 shadow-2xs hover:bg-slate-50 transition"
                                data-hs-overlay="#modal-tambah-donatur">
                            Batal
                        </button>
                        <button type="submit" 
                                class="py-2 px-4 inline-flex items-center gap-x-2 text-xs font-semibold rounded-xl bg-[#2D6A4F] text-white hover:bg-[#1B4332] shadow-xs transition">
                            Simpan Donatur
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
