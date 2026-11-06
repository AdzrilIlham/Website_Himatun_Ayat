<div id="kampanye-page" class="relative overflow-x-hidden" x-data="{ copiedBank: null }">

    <!-- Scroll target helper -->
    <div id="formulir-donatur" class="scroll-mt-28"></div>

    {{-- Section 1: Hero, Formulir Donatur & Doa Harapan --}}
    @include('user.kampanye-donasi.partials._hero-formulir')

    {{-- Section 2: Hasil Donasi Yang Anda Berikan (Paket Kebaikan) --}}
    @include('user.kampanye-donasi.partials._paket-kebaikan')

    {{-- Section 3: Call to Action Banner Media Sosial --}}
    @include('user.kampanye-donasi.partials._cta-sosmed')

    {{-- Modal Konfirmasi Donasi & Pembayaran --}}
    @include('user.kampanye-donasi.partials._modal-payment')

    {{-- Modal Sukses Donasi --}}
    @include('user.kampanye-donasi.partials._modal-success')

    {{-- Scripts --}}
    @include('user.kampanye-donasi.partials._scripts')

</div>
