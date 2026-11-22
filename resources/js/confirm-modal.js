/**
 * Preline Confirmation Modal System
 * Menyediakan antarmuka konfirmasi visual berbasis Preline UI untuk menggantikan
 * alert/confirm bawaan browser (native JS confirm) di seluruh halaman Admin & User.
 */

class ConfirmModalManager {
    constructor() {
        this.modalId = 'global-confirm-modal';
        this.pendingResolver = null;
        this.isConfirmed = false;
        this.init();
    }

    init() {
        if (typeof document === 'undefined') return;

        // Pasang event delegation untuk semua elemen dengan atribut [data-confirm]
        document.addEventListener('click', (e) => this.handleTriggerClick(e), true);

        // Pasang event listener untuk submit form dengan atribut [data-confirm]
        document.addEventListener('submit', (e) => this.handleFormSubmit(e), true);

        // Pasang listener global untuk tombol konfirmasi dan batal di modal (delegasi penuh)
        document.addEventListener('click', (e) => {
            const confirmBtn = e.target.closest(`#${this.modalId}-confirm-btn, [data-confirm-submit]`);
            if (confirmBtn) {
                e.preventDefault();
                e.stopPropagation();
                this.isConfirmed = true;
                this.close();
                if (this.pendingResolver) {
                    const resolver = this.pendingResolver;
                    this.pendingResolver = null;
                    resolver(true);
                }
                return;
            }

            const cancelBtn = e.target.closest(`#${this.modalId}-cancel-btn, [data-confirm-cancel]`);
            if (cancelBtn) {
                this.isConfirmed = false;
                if (this.pendingResolver) {
                    const resolver = this.pendingResolver;
                    this.pendingResolver = null;
                    resolver(false);
                }
            }
        }, true);

        // Pasang listener saat overlay modal ditutup oleh Preline (via backdrop, ESC, dsb)
        document.addEventListener('close.hs.overlay', (e) => {
            const target = e.target;
            if (target && (target.id === this.modalId || target.matches?.(`#${this.modalId}`))) {
                setTimeout(() => {
                    if (!this.isConfirmed && this.pendingResolver) {
                        const resolver = this.pendingResolver;
                        this.pendingResolver = null;
                        resolver(false);
                    }
                    this.isConfirmed = false;
                }, 50);
            }
        });

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.bindModalEvents();
            });
        } else {
            this.bindModalEvents();
        }
    }

    bindModalEvents() {
        const modal = document.getElementById(this.modalId);
        if (!modal) return;

        // Pastikan Preline Overlay instance siap
        if (window.HSOverlay && typeof window.HSOverlay.autoInit === 'function') {
            window.HSOverlay.autoInit();
        }
    }

    /**
     * Tampilkan modal konfirmasi secara programatik (berbasis Promise).
     *
     * @param {Object} options
     * @param {string} [options.title] - Judul modal
     * @param {string} [options.message] - Pesan teks konfirmasi
     * @param {('danger'|'success'|'warning'|'info')} [options.type='danger'] - Tipe styling konfirmasi
     * @param {string} [options.confirmText] - Label tombol konfirmasi
     * @param {string} [options.cancelText='Batal'] - Label tombol batal
     * @param {string} [options.subtext] - Catatan tambahan kecil
     * @param {Function} [options.onConfirm] - Callback saat dikonfirmasi
     * @param {Function} [options.onCancel] - Callback saat dibatalkan
     * @returns {Promise<boolean>}
     */
    confirm(options = {}) {
        return new Promise((resolve) => {
            const modal = document.getElementById(this.modalId);
            if (!modal) {
                // Fallback aman jika modal markup belum ada di halaman
                const fallbackConfirm = window.confirm(options.message || 'Apakah Anda yakin?');
                if (fallbackConfirm && typeof options.onConfirm === 'function') {
                    options.onConfirm();
                } else if (!fallbackConfirm && typeof options.onCancel === 'function') {
                    options.onCancel();
                }
                return resolve(fallbackConfirm);
            }

            const type = options.type || 'danger';
            const title = options.title || this.getDefaultTitle(type);
            const message = options.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
            const confirmText = options.confirmText || this.getDefaultConfirmText(type);
            const cancelText = options.cancelText || 'Batal';
            const subtext = options.subtext || '';

            // Perbarui elemen-elemen di dalam modal
            const titleEl = document.getElementById(`${this.modalId}-title`);
            const messageEl = document.getElementById(`${this.modalId}-message`);
            const subtextEl = document.getElementById(`${this.modalId}-subtext`);
            const confirmTextEl = document.getElementById(`${this.modalId}-confirm-text`);
            const cancelTextEl = document.getElementById(`${this.modalId}-cancel-text`);
            const badgeEl = document.getElementById(`${this.modalId}-badge`);
            const confirmBtnEl = document.getElementById(`${this.modalId}-confirm-btn`);

            if (titleEl) titleEl.textContent = title;
            if (messageEl) messageEl.textContent = message;

            if (subtextEl) {
                if (subtext) {
                    subtextEl.textContent = subtext;
                    subtextEl.classList.remove('hidden');
                } else {
                    subtextEl.textContent = '';
                    subtextEl.classList.add('hidden');
                }
            }

            if (confirmTextEl) confirmTextEl.textContent = confirmText;
            if (cancelTextEl) cancelTextEl.textContent = cancelText;

            // Perbarui tampilan ikon berdasarkan tipe
            ['danger', 'success', 'warning', 'info'].forEach((t) => {
                const icon = document.getElementById(`${this.modalId}-icon-${t}`);
                if (icon) {
                    if (t === type) {
                        icon.classList.remove('hidden');
                    } else {
                        icon.classList.add('hidden');
                    }
                }
            });

            // Perbarui styling badge & tombol konfirmasi
            const badgeStyles = {
                danger: 'bg-red-50 text-red-600 border-red-200/80 ring-red-500/10 dark:bg-red-950/40 dark:text-red-400 dark:border-red-900/40',
                success: 'bg-brand-50 text-brand-700 border-brand-200/80 ring-brand-500/10 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-900/40',
                warning: 'bg-amber-50 text-amber-600 border-amber-200/80 ring-amber-500/10 dark:bg-amber-950/40 dark:text-amber-400 dark:border-amber-900/40',
                info: 'bg-sky-50 text-sky-600 border-sky-200/80 ring-sky-500/10 dark:bg-sky-950/40 dark:text-sky-400 dark:border-sky-900/40',
            };

            const btnStyles = {
                danger: 'bg-danger hover:bg-red-700 active:bg-red-800 text-white focus:ring-danger/30 shadow-xs',
                success: 'bg-brand-700 hover:bg-brand-800 active:bg-brand-900 text-white focus:ring-brand-700/30 shadow-xs',
                warning: 'bg-amber-600 hover:bg-amber-700 active:bg-amber-800 text-white focus:ring-amber-600/30 shadow-xs',
                info: 'bg-brand-700 hover:bg-brand-800 active:bg-brand-900 text-white focus:ring-brand-700/30 shadow-xs',
            };

            const cancelStyles = {
                danger: 'border border-border bg-layer hover:bg-muted text-foreground',
                success: 'border border-red-200 dark:border-red-900/60 bg-red-50/70 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:border-red-300',
                warning: 'border border-red-200 dark:border-red-900/60 bg-red-50/70 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:border-red-300',
                info: 'border border-red-200 dark:border-red-900/60 bg-red-50/70 hover:bg-red-100 dark:bg-red-950/30 text-red-600 dark:text-red-400 hover:border-red-300',
            };

            if (badgeEl) {
                // Bersihkan kelas lama dan pasang kelas baru
                Object.values(badgeStyles).forEach((cls) => {
                    cls.split(' ').forEach((c) => badgeEl.classList.remove(c));
                });
                const newBadgeClass = badgeStyles[type] || badgeStyles.danger;
                newBadgeClass.split(' ').forEach((c) => badgeEl.classList.add(c));
            }

            if (confirmBtnEl) {
                Object.values(btnStyles).forEach((cls) => {
                    cls.split(' ').forEach((c) => confirmBtnEl.classList.remove(c));
                });
                const newBtnClass = btnStyles[type] || btnStyles.danger;
                newBtnClass.split(' ').forEach((c) => confirmBtnEl.classList.add(c));
            }

            if (cancelTextEl && cancelTextEl.parentElement) {
                const cancelBtn = cancelTextEl.parentElement;
                Object.values(cancelStyles).forEach((cls) => {
                    cls.split(' ').forEach((c) => cancelBtn.classList.remove(c));
                });
                const newCancelClass = cancelStyles[type] || cancelStyles.danger;
                newCancelClass.split(' ').forEach((c) => cancelBtn.classList.add(c));
            }

            this.isConfirmed = false;
            this.pendingResolver = (confirmed) => {
                if (confirmed) {
                    if (typeof options.onConfirm === 'function') options.onConfirm();
                } else {
                    if (typeof options.onCancel === 'function') options.onCancel();
                }
                resolve(confirmed);
            };

            this.open();
        });
    }

    open() {
        const modal = document.getElementById(this.modalId);
        if (!modal) return;

        if (window.HSOverlay && typeof window.HSOverlay.open === 'function') {
            try {
                if (window.HSOverlay.autoInit && !window.HSOverlay.getInstance(modal)) {
                    window.HSOverlay.autoInit();
                }
                window.HSOverlay.open(modal);
            } catch (err) {
                const trigger = document.getElementById(`${this.modalId}-trigger`);
                if (trigger) {
                    trigger.click();
                } else {
                    modal.classList.remove('hidden');
                }
            }
        } else {
            const trigger = document.getElementById(`${this.modalId}-trigger`);
            if (trigger) {
                trigger.click();
            } else {
                modal.classList.remove('hidden');
            }
        }
    }

    close() {
        const modal = document.getElementById(this.modalId);
        if (!modal) return;

        if (window.HSOverlay && typeof window.HSOverlay.close === 'function') {
            try {
                window.HSOverlay.close(modal);
            } catch (err) {
                const cancelBtn = document.getElementById(`${this.modalId}-cancel-btn`);
                if (cancelBtn) {
                    cancelBtn.click();
                } else {
                    modal.classList.add('hidden');
                }
            }
        } else {
            const cancelBtn = document.getElementById(`${this.modalId}-cancel-btn`);
            if (cancelBtn) {
                cancelBtn.click();
            } else {
                modal.classList.add('hidden');
            }
        }
    }

    getDefaultTitle(type) {
        switch (type) {
            case 'danger':
                return 'Konfirmasi Hapus Data';
            case 'success':
                return 'Konfirmasi Tindakan';
            case 'warning':
                return 'Peringatan Tindakan';
            case 'info':
                return 'Informasi & Konfirmasi';
            default:
                return 'Konfirmasi Tindakan';
        }
    }

    getDefaultConfirmText(type) {
        switch (type) {
            case 'danger':
                return 'Ya, Hapus';
            case 'success':
                return 'Ya, Lanjutkan';
            case 'warning':
                return 'Ya, Lanjutkan';
            case 'info':
                return 'Ya, Simpan';
            default:
                return 'Ya, Lanjutkan';
        }
    }

    handleTriggerClick(e) {
        const trigger = e.target.closest('[data-confirm]');
        if (!trigger) return;

        // Jika pemicu sudah melewati konfirmasi, biarkan event native berjalan
        if (trigger.dataset.confirmedCycle === 'true') {
            trigger.dataset.confirmedCycle = '';
            return;
        }

        const form = trigger.closest('form');
        // Jika tombol submit berada di dalam form dan form belum valid menurut validasi browser,
        // tampilkan peringatan validasi bawaan dan jangan tampilkan konfirmasi dulu.
        if (form && (trigger.tagName === 'BUTTON' || trigger.tagName === 'INPUT')) {
            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                form.reportValidity();
                return;
            }
        }

        e.preventDefault();
        e.stopPropagation();

        const message = trigger.getAttribute('data-confirm');
        const title = trigger.getAttribute('data-confirm-title') || undefined;
        const type = trigger.getAttribute('data-confirm-type') || 'danger';
        const confirmText = trigger.getAttribute('data-confirm-button') || undefined;
        const cancelText = trigger.getAttribute('data-confirm-cancel') || 'Batal';
        const subtext = trigger.getAttribute('data-confirm-subtext') || undefined;

        this.confirm({
            title,
            message,
            type,
            confirmText,
            cancelText,
            subtext,
            onConfirm: () => {
                trigger.dataset.confirmedCycle = 'true';

                if (form) {
                    form.dataset.confirmedCycle = 'true';
                    if (trigger.tagName === 'BUTTON' || trigger.tagName === 'INPUT') {
                        try {
                            if (typeof form.requestSubmit === 'function') {
                                form.requestSubmit(trigger);
                            } else {
                                HTMLFormElement.prototype.submit.call(form);
                            }
                        } catch (err) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    } else if (trigger.tagName === 'FORM') {
                        try {
                            if (typeof form.requestSubmit === 'function') {
                                form.requestSubmit();
                            } else {
                                HTMLFormElement.prototype.submit.call(form);
                            }
                        } catch (err) {
                            HTMLFormElement.prototype.submit.call(form);
                        }
                    } else {
                        HTMLFormElement.prototype.submit.call(form);
                    }
                } else if (trigger.tagName === 'A' && trigger.href) {
                    window.location.href = trigger.href;
                } else {
                    trigger.click();
                }
            },
        });
    }

    handleFormSubmit(e) {
        const form = e.target;
        if (!form || !form.hasAttribute('data-confirm')) return;

        if (form.dataset.confirmedCycle === 'true') {
            form.dataset.confirmedCycle = '';
            return;
        }

        if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
            form.reportValidity();
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        const message = form.getAttribute('data-confirm');
        const title = form.getAttribute('data-confirm-title') || undefined;
        const type = form.getAttribute('data-confirm-type') || 'danger';
        const confirmText = form.getAttribute('data-confirm-button') || undefined;
        const cancelText = form.getAttribute('data-confirm-cancel') || 'Batal';
        const subtext = form.getAttribute('data-confirm-subtext') || undefined;

        this.confirm({
            title,
            message,
            type,
            confirmText,
            cancelText,
            subtext,
            onConfirm: () => {
                form.dataset.confirmedCycle = 'true';
                try {
                    if (typeof form.requestSubmit === 'function') {
                        form.requestSubmit();
                    } else {
                        HTMLFormElement.prototype.submit.call(form);
                    }
                } catch (err) {
                    HTMLFormElement.prototype.submit.call(form);
                }
            },
        });
    }
}

// Inisialisasi singleton manager konfirmasi
const confirmModalManager = new ConfirmModalManager();

// Expose fungsi ke window untuk akses global yang mudah
window.confirmModal = confirmModalManager;
window.confirmAction = (options) => confirmModalManager.confirm(options);

export default confirmModalManager;
