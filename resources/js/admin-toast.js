/**
 * Preline Default Toast Notification Manager
 * Mengelola toast berbasis komponen Preline UI v4 (hs-removing + data-hs-remove-element)
 * baik untuk flash message dari server maupun panggilan JS dinamis.
 */

const TOAST_ICONS = {
    success: {
        color: 'text-teal-500',
        path: 'M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z',
    },
    error: {
        color: 'text-red-500',
        path: 'M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z',
    },
    warning: {
        color: 'text-amber-500',
        path: 'M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z',
    },
    info: {
        color: 'text-blue-500',
        path: 'M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z',
    },
};

const SVG_NS = 'http://www.w3.org/2000/svg';

class AdminToastManager {
    constructor() {
        this.containerId = 'admin-toast-container';
        this.counter = 0;
        this.init();
    }

    init() {
        if (typeof document === 'undefined') return;

        const setup = () => this.initExistingToasts();

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setup);
        } else {
            setup();
        }
    }

    initExistingToasts() {
        const container = document.getElementById(this.containerId);
        if (!container) return;

        this.initRemoveElements();

        container.querySelectorAll('[data-admin-toast][data-auto-dismiss]').forEach((toastEl) => {
            if (toastEl.dataset.toastInitialized === 'true') return;
            toastEl.dataset.toastInitialized = 'true';

            const duration = parseInt(toastEl.getAttribute('data-auto-dismiss') || '4500', 10);
            this.attachAutoDismiss(toastEl, duration);
        });
    }

    initRemoveElements() {
        if (window.HSRemoveElement && typeof window.HSRemoveElement.autoInit === 'function') {
            window.HSRemoveElement.autoInit();
        } else if (window.HSStaticMethods && typeof window.HSStaticMethods.autoInit === 'function') {
            window.HSStaticMethods.autoInit(['remove-element']);
        }
    }

    /**
     * Tampilkan toast default Preline UI secara dinamis.
     *
     * @param {string} message
     * @param {'success'|'error'|'warning'|'info'} [type='success']
     * @param {number} [duration=4000]
     * @returns {HTMLElement|null}
     */
    show(message, type = 'success', duration = 4000) {
        const container = document.getElementById(this.containerId);
        if (!container) return null;

        const normalizedType = Object.prototype.hasOwnProperty.call(TOAST_ICONS, type) ? type : 'success';
        const iconConfig = TOAST_ICONS[normalizedType];
        const toastId = `hs-toast-dynamic-${++this.counter}`;

        const toastEl = this.buildToastElement(toastId, String(message ?? ''), iconConfig);
        container.appendChild(toastEl);

        this.initRemoveElements();
        this.attachAutoDismiss(toastEl, duration);

        return toastEl;
    }

    buildToastElement(toastId, message, iconConfig) {
        const wrapper = document.createElement('div');
        wrapper.id = toastId;
        wrapper.setAttribute('data-admin-toast', '');
        wrapper.className = 'hs-removing:translate-x-5 hs-removing:opacity-0 transition duration-300 max-w-xs w-full p-4 bg-layer border border-layer-line rounded-xl shadow-lg pointer-events-auto';
        wrapper.setAttribute('role', 'alert');
        wrapper.setAttribute('tabindex', '-1');
        wrapper.setAttribute('aria-labelledby', `${toastId}-label`);

        const row = document.createElement('div');
        row.className = 'flex';

        const iconWrap = document.createElement('div');
        iconWrap.className = 'shrink-0';
        iconWrap.appendChild(this.createSvg('16', '16', '0 0 16 16', `shrink-0 size-4 ${iconConfig.color} mt-0.5`, 'currentColor', [iconConfig.path], true));

        const textWrap = document.createElement('div');
        textWrap.className = 'ms-3 grow';

        const textEl = document.createElement('p');
        textEl.id = `${toastId}-label`;
        textEl.className = 'text-sm text-layer-foreground';
        textEl.textContent = message;
        textWrap.appendChild(textEl);

        const closeWrap = document.createElement('div');
        closeWrap.className = 'ms-3';

        const closeBtn = document.createElement('button');
        closeBtn.type = 'button';
        closeBtn.className = 'inline-flex shrink-0 justify-center items-center size-5 rounded-lg text-layer-foreground hover:bg-layer-hover opacity-50 hover:opacity-100 focus:outline-hidden focus:opacity-100 cursor-pointer';
        closeBtn.setAttribute('data-hs-remove-element', `#${toastId}`);
        closeBtn.setAttribute('aria-label', 'Tutup notifikasi');

        const srSpan = document.createElement('span');
        srSpan.className = 'sr-only';
        srSpan.textContent = 'Close';

        const closeSvg = this.createSvg('24', '24', '0 0 24 24', 'shrink-0 size-4', 'none', ['M18 6 6 18', 'm6 6 12 12'], false);
        closeSvg.setAttribute('stroke', 'currentColor');
        closeSvg.setAttribute('stroke-width', '2');
        closeSvg.setAttribute('stroke-linecap', 'round');
        closeSvg.setAttribute('stroke-linejoin', 'round');

        closeBtn.appendChild(srSpan);
        closeBtn.appendChild(closeSvg);
        closeWrap.appendChild(closeBtn);

        row.appendChild(iconWrap);
        row.appendChild(textWrap);
        row.appendChild(closeWrap);
        wrapper.appendChild(row);

        return wrapper;
    }

    createSvg(width, height, viewBox, className, fill, paths, isFilled) {
        const svg = document.createElementNS(SVG_NS, 'svg');
        svg.setAttribute('class', className);
        svg.setAttribute('width', width);
        svg.setAttribute('height', height);
        svg.setAttribute('viewBox', viewBox);
        svg.setAttribute('fill', fill);
        svg.setAttribute('aria-hidden', 'true');

        paths.forEach((d) => {
            const path = document.createElementNS(SVG_NS, 'path');
            path.setAttribute('d', d);
            if (!isFilled) {
                path.setAttribute('fill', 'none');
            }
            svg.appendChild(path);
        });

        return svg;
    }

    attachAutoDismiss(toastEl, duration) {
        if (!toastEl || !Number.isFinite(duration) || duration <= 0) return;

        let timerId = null;
        let remaining = duration;
        let startTime = Date.now();
        const closeBtn = toastEl.querySelector('[data-hs-remove-element]');

        const dismiss = () => {
            if (!toastEl.isConnected || toastEl.classList.contains('hs-removing')) return;

            if (closeBtn) {
                closeBtn.click();
                setTimeout(() => {
                    if (toastEl.isConnected) {
                        toastEl.remove();
                    }
                }, 500);
            } else {
                toastEl.remove();
            }
        };

        const schedule = (delay) => {
            clearTimeout(timerId);
            startTime = Date.now();
            timerId = setTimeout(dismiss, delay);
        };

        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                clearTimeout(timerId);
            });
        }

        toastEl.addEventListener('mouseenter', () => {
            clearTimeout(timerId);
            remaining = Math.max(0, remaining - (Date.now() - startTime));
        });

        toastEl.addEventListener('mouseleave', () => {
            if (toastEl.isConnected && !toastEl.classList.contains('hs-removing')) {
                schedule(Math.max(remaining, 500));
            }
        });

        schedule(duration);
    }
}

const adminToastManager = new AdminToastManager();

window.adminToast = (message, type = 'success', duration = 4000) => adminToastManager.show(message, type, duration);
window.showToast = window.adminToast;

export default adminToastManager;
