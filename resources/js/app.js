import HSStaticMethods, { HSSelect } from 'preline';
import Alpine from 'alpinejs';

window.HSStaticMethods = HSStaticMethods;
window.HSSelect = HSSelect;
window.Alpine = Alpine;

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

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPreline);
} else {
    initPreline();
}

Alpine.start();
