@extends('layouts.admin')

@section('content')
<div class="space-y-4 pb-10 w-full">
    <!-- Flash Notifications -->
    @if(session('success'))
        <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 text-base leading-none">&times;</button>
        </div>
    @endif

    <!-- Top Header: Title, Count, & Action Buttons -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-[#1B4332]">Kelola Donasi</h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">10 donatur terdaftar</p>
        </div>
        <div class="flex items-center gap-2">
            <!-- Tombol ↓ Export -->
            <a href="{{ route('admin.kelola-donasi.export') }}"
               class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg bg-[#2D6A4F] text-white text-xs font-semibold hover:bg-[#1B4332] transition shadow-xs">
                <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M7.5 12l4.5 4.5m0 0l4.5-4.5M12 3v13.5" />
                </svg>
                <span>Export</span>
            </a>
        </div>
    </div>

    <!-- 4 Top Stat Cards (Persis Foto 1) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3.5">
        <!-- Card 1: TOTAL PENERIMAAN ZIS -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">TOTAL PENERIMAAN ZIS</span>
                    <div class="w-8 h-8 rounded-lg bg-[#E2F4E9] text-[#24533C] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2.5" />
                            <path d="M6 12h.01M18 12h.01" />
                        </svg>
                    </div>
                </div>
                <div class="mt-1.5">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. 312.450.000
                    </span>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Akumulasi Infaq, Zakat, &amp; Wakaf Masuk</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <span class="inline-flex items-center gap-1 text-[11px] font-semibold text-emerald-800">
                    <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.318 4.318L20.25 7.5m0 0h-5.25m5.25 0v5.25" />
                    </svg>
                    <span>+14.2% dibanding bulan lalu</span>
                </span>
            </div>
        </div>

        <!-- Card 2: PENYALURAN PROGRAM -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">PENYALURAN PROGRAM</span>
                    <div class="w-8 h-8 rounded-lg bg-[#FEF9C3] text-[#A16207] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-5.25 6.557c0 1.455.517 2.822 1.407 3.896" />
                        </svg>
                    </div>
                </div>
                <div class="mt-1.5">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. 220.450.000
                    </span>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Akumulasi Infaq, Zakat, &amp; Wakaf Masuk</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <div class="w-full bg-slate-100 rounded-full h-1 overflow-hidden">
                    <div class="bg-[#1B4332] h-1 rounded-full" style="width: 91%"></div>
                </div>
                <div class="flex items-center justify-between mt-2 text-[10px]">
                    <span class="font-bold text-slate-800">Rasio Penyaluran</span>
                    <span class="font-bold text-[#A16207]">91% Efektivitas</span>
                </div>
            </div>
        </div>

        <!-- Card 3: OPERASIONAL HAK MILIK -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">OPERASIONAL HAK MILIK</span>
                    <div class="w-8 h-8 rounded-lg bg-[#F1F5F9] text-slate-600 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.97zm-13.5 0C4.24 5.113 3.24 5.287 2.25 5.49m3-.52L2.63 15.696c-.122.499.106 1.028.589 1.202a5.989 5.989 0 002.031.352 5.988 5.988 0 002.031-.352c.483-.174.711-.703.59-1.202L5.25 4.97z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-1.5">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. 22.450.000
                    </span>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Rasio 7.3% dari dana terkumpul</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-amber-800">
                    <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Di bawah batas maks. BAZNAS (12.5%)</span>
                </span>
            </div>
        </div>

        <!-- Card 4: SALDO KAS & CADANGAN -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/70 shadow-2xs flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-500">SALDO KAS &amp; CADANGAN</span>
                    <div class="w-8 h-8 rounded-lg bg-[#E2F4E9] text-[#24533C] flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333A48.357 48.357 0 0012 9.75c-2.551 0-5.056.2-7.5.583V21" />
                        </svg>
                    </div>
                </div>
                <div class="mt-1.5">
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight text-slate-900">
                        Rp. 154.450.000
                    </span>
                    <p class="text-[11px] text-slate-500 font-medium mt-0.5">Rekening giro amanah BRI Syariah</p>
                </div>
            </div>
            <div class="mt-3 pt-2">
                <span class="inline-flex items-center gap-1.5 text-[11px] font-medium text-emerald-800">
                    <svg class="w-3.5 h-3.5 text-emerald-700 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                    <span>Cadangan darurat &amp; asrama binaan</span>
                </span>
            </div>
        </div>
    </div>

    <!-- Alokasi Penyaluran Program per Klaster Asnaf Card (Persis Foto 1) -->
    <div class="bg-[#D4E3D9] rounded-2xl p-4 sm:p-5 border border-emerald-900/5 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5">
            <div>
                <h2 class="text-sm font-bold text-slate-900">Alokasi Penyaluran Program per Klaster Asnaf</h2>
                <p class="text-xs text-slate-600 mt-0.5">Distribusi realisasi pemanfaatan dana sedekah dan zakat secara terperinci.</p>
            </div>
            <div class="text-xs text-slate-700 sm:text-right shrink-0">
                Total Realisasi: <span class="font-bold text-slate-900">Rp 284.150.000</span>
            </div>
        </div>

        <!-- Multi-segment Colored Progress Bar (Flush/menyatu mulus tanpa jeda renggang) -->
        <div class="mt-3.5 w-full h-3 rounded-full overflow-hidden flex bg-slate-300/40">
            <div style="width: 48%;" class="bg-[#24533C] h-full" title="Pendidikan: 48%"></div>
            <div style="width: 27%;" class="bg-[#74A892] h-full" title="Nutrisi: 27%"></div>
            <div style="width: 16%;" class="bg-[#E5B84B] h-full" title="Kesehatan: 16%"></div>
            <div style="width: 9%;" class="bg-[#94A3B8] h-full" title="Asrama: 9%"></div>
        </div>

        <!-- 4 Legend Breakdown Items -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3.5 mt-3.5 pt-0.5">
            <!-- 1. Pendidikan -->
            <div class="flex items-start gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#24533C] shrink-0 mt-1"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">Pendidikan &amp; Beasiswa</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp 136.392.000 (48%)</p>
                </div>
            </div>

            <!-- 2. Nutrisi -->
            <div class="flex items-start gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#74A892] shrink-0 mt-1"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">Nutrisi &amp; Permakanan</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp 76.720.500 (27%)</p>
                </div>
            </div>

            <!-- 3. Kesehatan -->
            <div class="flex items-start gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#E5B84B] shrink-0 mt-1"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">Layanan Kesehatan</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp 45.464.000 (16%)</p>
                </div>
            </div>

            <!-- 4. Asrama -->
            <div class="flex items-start gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-[#94A3B8] shrink-0 mt-1"></span>
                <div class="text-xs">
                    <p class="font-bold text-slate-800 leading-tight">Fasilitas Asrama</p>
                    <p class="text-slate-600 text-[11px] mt-0.5 font-medium">Rp 25.573.500 (9%)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Toolbar: Search Bar & Segmented Sorting Pills -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-1">
        <!-- Search Input -->
        <form method="GET" action="{{ route('admin.kelola-donasi.index') }}" class="w-full sm:w-80">
            <input type="hidden" name="sort" value="{{ $sort }}">
            <div class="relative">
                <input type="text" 
                       name="search" 
                       value="{{ $search }}" 
                       placeholder="Cari nama / email..." 
                       class="w-full rounded-2xl border border-slate-300 bg-white px-4 py-1.5 text-xs text-slate-800 placeholder:text-slate-400 focus:border-[#24533C] focus:outline-hidden transition shadow-2xs">
            </div>
        </form>

        <!-- Segmented Sorting Pills -->
        <div class="flex items-center gap-2 self-start sm:self-auto text-xs">
            <span class="text-slate-400 font-medium">Urutkan:</span>
            <div class="inline-flex rounded-lg bg-slate-100 p-0.5 border border-slate-200/60">
                <!-- Total Donasi -->
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'total_donasi']) }}"
                   class="px-3 py-1 rounded-md text-xs font-semibold transition {{ $sort === 'total_donasi' || !$sort ? 'bg-[#24533C] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Total Donasi
                </a>

                <!-- Frekuensi -->
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'frekuensi']) }}"
                   class="px-3 py-1 rounded-md text-xs font-medium transition {{ $sort === 'frekuensi' ? 'bg-[#24533C] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Frekuensi
                </a>

                <!-- Nama -->
                <a href="{{ route('admin.kelola-donasi.index', ['search' => $search, 'sort' => 'nama']) }}"
                   class="px-3 py-1 rounded-md text-xs font-medium transition {{ $sort === 'nama' ? 'bg-[#24533C] text-white shadow-2xs' : 'text-slate-600 hover:text-slate-900' }}">
                    Nama
                </a>
            </div>
        </div>
    </div>

    <!-- Table Container (Rounded Gray Pill Header) -->
    <div class="space-y-2">
        <!-- Rounded Pill Table Header -->
        <div class="bg-[#DCE5DF] text-slate-700 font-bold uppercase text-[11px] tracking-wider rounded-2xl px-6 py-2.5 flex items-center shadow-2xs">
            <div class="w-[26%] text-left">DONATUR</div>
            <div class="w-[28%] text-left">KONTAK</div>
            <div class="w-[18%] text-left">KAMPANYE</div>
            <div class="w-[18%] text-left">TOTAL DONASI</div>
            <div class="w-[10%] text-center">AKSI</div>
        </div>

        <!-- Table Rows Container -->
        <div class="bg-white rounded-2xl border border-slate-200/70 shadow-2xs divide-y divide-slate-100 overflow-hidden">
            @forelse($donasiList as $donasi)
                <div class="px-6 py-3.5 flex items-center hover:bg-slate-50/60 transition">
                    <!-- DONATUR -->
                    <div class="w-[26%] flex items-center gap-3 pr-2 min-w-0">
                        <div class="w-8 h-8 rounded-full bg-[#1F2937] text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-2xs">
                            HW
                        </div>
                        <span class="font-bold text-slate-900 text-sm truncate">{{ $donasi->nama_donatur }}</span>
                    </div>

                    <!-- KONTAK -->
                    <div class="w-[28%] text-xs min-w-0 pr-2">
                        <p class="text-slate-700 font-medium truncate">{{ $donasi->email ?: 'hendra.w@gmail.com' }}</p>
                        <p class="text-slate-400 text-[11px] mt-0.5 truncate">{{ $donasi->no_whatsapp ?: '+62 816-5678-9012' }}</p>
                    </div>

                    <!-- KAMPANYE (Pill Hijau Pastel) -->
                    <div class="w-[18%] pr-2">
                        <span class="inline-block px-3 py-0.5 rounded-full text-xs font-semibold bg-[#D0E2D7] text-[#1B4332] truncate">
                            {{ $donasi->kampanye ? $donasi->kampanye->judul : 'Renovasi' }}
                        </span>
                    </div>

                    <!-- TOTAL DONASI -->
                    <div class="w-[18%]">
                        <span class="font-bold text-sm sm:text-base text-slate-900">
                            Rp {{ number_format($donasi->nominal, 0, ',', '.') }}
                        </span>
                    </div>

                    <!-- AKSI (Terima & Hapus Pills) -->
                    <div class="w-[10%] flex items-center justify-center gap-1.5 shrink-0">
                        <!-- Terima -->
                        <form method="POST" action="{{ route('admin.kelola-donasi.terima', $donasi->id) }}">
                            @csrf
                            <button type="submit" 
                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-[#D0E2D7] text-[#1B4332] hover:bg-emerald-200 transition shadow-2xs">
                                Terima
                            </button>
                        </form>

                        <!-- Hapus -->
                        <form method="POST" action="{{ route('admin.kelola-donasi.destroy', $donasi->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Hapus donasi ini?')"
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

    <!-- Card Footer: Menampilkan Counter & Pagination (Persis Foto 1 di dalam wadah card putih) -->
    <div class="bg-white rounded-2xl border border-slate-200/70 p-3.5 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-slate-500 shadow-2xs">
        <div class="font-medium text-center sm:text-left text-slate-600">
            Menampilkan {{ $donasiList->firstItem() ?? 1 }}-{{ $donasiList->lastItem() ?? 10 }} dari {{ $donasiList->total() }} Data Donasi
        </div>

        <nav class="flex items-center justify-center sm:justify-end gap-1">
            {{-- Prev Link --}}
            @if ($donasiList->onFirstPage())
                <span class="w-7 h-7 rounded-md flex items-center justify-center text-slate-400 cursor-not-allowed">
                    &lt;
                </span>
            @else
                <a href="{{ $donasiList->previousPageUrl() }}" 
                   class="w-7 h-7 rounded-md flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                    &lt;
                </a>
            @endif

            {{-- 1, 2, 3 Pages (Persis Foto 1) --}}
            @for ($p = 1; $p <= min(3, $donasiList->lastPage()); $p++)
                @if ($p == $donasiList->currentPage())
                    <span class="w-7 h-7 rounded-md bg-[#24533C] text-white flex items-center justify-center font-bold text-xs shadow-2xs">
                        {{ $p }}
                    </span>
                @else
                    <a href="{{ $donasiList->url($p) }}" 
                       class="w-7 h-7 rounded-md text-slate-700 hover:bg-slate-100 flex items-center justify-center font-medium text-xs transition">
                        {{ $p }}
                    </a>
                @endif
            @endfor

            {{-- Next Link --}}
            @if ($donasiList->hasMorePages())
                <a href="{{ $donasiList->nextPageUrl() }}" 
                   class="w-7 h-7 rounded-md flex items-center justify-center text-slate-600 hover:text-slate-900 transition">
                    &gt;
                </a>
            @else
                <span class="w-7 h-7 rounded-md flex items-center justify-center text-slate-400 cursor-not-allowed">
                    &gt;
                </span>
            @endif
        </nav>
    </div>
</div>
@endsection
