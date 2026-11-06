import HSStaticMethods, { HSSelect } from 'preline';
import Alpine from 'alpinejs';
import './hero-swiper';
import './page-animations';

window.HSStaticMethods = HSStaticMethods;
window.HSSelect = HSSelect;

// Fokus ke toggle Preline Select (elemen <select> aslinya tersembunyi),
// dipakai validasi form Alpine agar fokus tetap mengarah ke kontrol yang terlihat.
window.focusPrelineSelect = (target) => {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el) return;

    const entry = window.HSSelect?.getInstance(el, true);
    if (entry?.element?.toggle) {
        entry.element.toggle.focus();
    } else {
        el.focus();
    }
};

// Preline Select menyimpan state-nya sendiri, sehingga perubahan nilai <select>
// secara programatik (mis. dari Alpine saat membuka form edit) tidak otomatis
// memperbarui tampilan toggle. Helper ini menyamakan tampilan dengan nilai native.
window.syncPrelineSelect = (target) => {
    const el = typeof target === 'string' ? document.querySelector(target) : target;
    if (!el) return;

    const entry = window.HSSelect?.getInstance(el, true);
    if (!entry?.element) return;

    const value = el.multiple
        ? Array.from(el.selectedOptions).map((option) => option.value)
        : el.value;

    entry.element.setValue(value);

    // setValue() Preline memanggil clearSelections() yang melepas atribut
    // selected dari semua <option>, sehingga nilai native harus ditetapkan ulang.
    if (el.multiple) {
        Array.from(el.options).forEach((option) => {
            option.selected = value.includes(option.value);
        });
    } else {
        el.value = value;
    }
};

// Preline v4 tidak lagi auto-init saat di-import, jadi jalankan sekali setelah DOM siap.
// Panggil window.HSStaticMethods.autoInit() lagi setelah menambah markup Preline
// secara dinamis (mis. hasil fetch / partial yang di-inject).
const initPreline = () => HSStaticMethods.autoInit();

// Jalankan Alpine dari bundle ini hanya jika halaman BUKAN halaman Livewire.
// Halaman Livewire sudah membawa Alpine sendiri di bawah tendanya agar tidak terjadi tabrakan instance ganda.
const startAlpineIfNoLivewire = () => {
    if (!document.querySelector('[wire\\:id], [wire\\:snapshot]') && !window.Livewire && !window.Alpine) {
        window.Alpine = Alpine;
        Alpine.start();
    }
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        initPreline();
        startAlpineIfNoLivewire();
    });
} else {
    initPreline();
    startAlpineIfNoLivewire();
}
