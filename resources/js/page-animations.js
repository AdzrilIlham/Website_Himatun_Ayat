/**
 * Motion layer untuk halaman publik Yayasan Himatun Ayat.
 *
 * Satu modul dipakai lintas halaman (landing page, berita & kegiatan, detail berita)
 * supaya:
 * - selector dibatasi pada root [data-page-animations] / [data-landing-animations]
 *   (tidak bocor ke halaman lain),
 * - lifecycle dikelola gsap.matchMedia() (viewport + prefers-reduced-motion),
 * - tidak ada inisialisasi ganda dan seluruh ScrollTrigger ikut dibersihkan saat revert.
 *
 * Penanda di markup:
 * - data-animate="fade-up|fade-in|fade-scale|slide-left|slide-right|line-grow" -> elemen tunggal
 * - data-animate-group="<tipe>"                 -> stagger untuk child langsung
 * - data-animate-item="<tipe>"                  -> override tipe pada child grup
 * - data-animate-dynamic="<tipe>"               -> anak yang dirender belakangan (Alpine x-for)
 * - data-animate-distance / -duration / -stagger / -delay / -start / -ease
 * - data-animate-hero + data-hero-heading|description|cta|media -> timeline hero
 *
 * Tidak ada CSS awal opacity: 0. Elemen hanya disembunyikan oleh JS, sehingga konten
 * tetap terbaca penuh bila JavaScript gagal dimuat.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const ROOT_SELECTOR = '[data-page-animations], [data-landing-animations]';
const GROUP_SELECTOR = '[data-animate-group]';
const ITEM_SELECTOR = '[data-animate]';
const HERO_SELECTOR = '[data-animate-hero]';
const DYNAMIC_SELECTOR = '[data-animate-dynamic]';

const DEFAULT_DISTANCE = 24;
const DEFAULT_DURATION = 0.75;
const DEFAULT_STAGGER = 0.1;
const DEFAULT_DYNAMIC_STAGGER = 0.07;
const DEFAULT_START = 'top 85%';
const DEFAULT_EASE = 'power3.out';
const MOBILE_DISTANCE_FACTOR = 0.8;
const DYNAMIC_REFRESH_DELAY = 120;

let mediaContext = null;
let initialized = false;

const isElement = (node) => node && node.nodeType === Node.ELEMENT_NODE;
const isRevealable = (node) => isElement(node) && node.tagName !== 'TEMPLATE';

/**
 * Target deep-link dari URL (mis. CTA landing page -> /berita-kegiatan#kalender-kegiatan).
 * Hash bisa berisi karakter yang tidak valid sebagai selector, jadi dibungkus try/catch.
 */
function getHashTarget() {
    const hash = window.location?.hash;

    if (!hash || hash.length < 2) return null;

    try {
        return document.querySelector(hash);
    } catch {
        return null;
    }
}

/**
 * Halaman dibuka langsung pada sebuah bagian (anchor) atau browser memulihkan posisi
 * scroll saat reload. Pada kondisi ini browser sudah melompat melewati elemen-elemen
 * di atas target, sehingga ScrollTrigger `once` untuk elemen tersebut tidak pernah
 * terpicu dan kontennya tertinggal dalam keadaan opacity: 0.
 *
 * Karena itu entrance animation dilewati untuk load seperti ini: konten langsung
 * terlihat utuh, lalu posisi scroll dirapikan ke target (di bawah header tetap).
 */
function isDeepLinkEntry(roots) {
    const target = getHashTarget();

    if (target && roots.some((root) => root.contains(target))) return true;

    if (Math.round(window.scrollY || 0) > 0) return true;

    // Back/forward: browser memulihkan posisi scroll setelah init, jadi konten di
    // atas viewport juga berisiko tertinggal tersembunyi.
    try {
        const type = performance.getEntriesByType?.('navigation')?.[0]?.type;

        if (type === 'back_forward') return true;
    } catch {
        /* Navigation Timing tidak tersedia: abaikan */
    }

    return false;
}

function setScrollInstant(top) {
    const html = document.documentElement;
    const previous = html.style.scrollBehavior;

    // CSS `scroll-behavior: smooth` pada <html> juga memengaruhi scrollTo({behavior:'auto'}),
    // jadi dinonaktifkan sesaat agar posisi akhir langsung tepat.
    html.style.scrollBehavior = 'auto';
    window.scrollTo(0, Math.max(0, top));
    html.style.scrollBehavior = previous;
}

let smoothScrollBackup = null;

/**
 * Lompatan anchor + `scroll-behavior: smooth` membuat browser terus menggulir setelah
 * `load`, sehingga koreksi offset bisa tertimpa lagi. Smooth scroll dinonaktifkan
 * sementara sampai posisi akhir deep-link diterapkan.
 */
function suppressSmoothScroll() {
    if (smoothScrollBackup !== null) return;

    smoothScrollBackup = document.documentElement.style.scrollBehavior;
    document.documentElement.style.scrollBehavior = 'auto';
}

function restoreSmoothScroll() {
    if (smoothScrollBackup === null) return;

    document.documentElement.style.scrollBehavior = smoothScrollBackup;
    smoothScrollBackup = null;
}

/**
 * Rapikan posisi ke anchor setelah layout benar-benar stabil (Alpine sudah merender
 * grid berita, gambar & font selesai dimuat), lalu beri offset setinggi header tetap.
 * Tidak dilakukan bila pengguna sudah mulai menggulir sendiri.
 */
function settleDeepLink() {
    const target = getHashTarget();

    if (!target) return;

    suppressSmoothScroll();

    let userMoved = false;
    let settled = false;

    const markMoved = () => {
        userMoved = true;
        restoreSmoothScroll();
    };

    ['wheel', 'touchstart', 'keydown'].forEach((eventName) => {
        window.addEventListener(eventName, markMoved, { passive: true, once: true });
    });

    const align = () => {
        if (settled || userMoved || !target.isConnected) return;

        settled = true;

        const header = document.querySelector('header');
        const offset = (header?.getBoundingClientRect().height || 0) + 8;

        setScrollInstant(target.getBoundingClientRect().top + window.scrollY - offset);
        restoreSmoothScroll();
    };

    // Tunggu posisi scroll berhenti berubah (mis. sisa animasi smooth dari browser),
    // baru terapkan offset header. Dibatasi ±2 detik agar tidak menggantung.
    const alignWhenStable = () => {
        let lastY = window.scrollY;
        let stableFrames = 0;
        let frames = 0;

        const tick = () => {
            if (settled || userMoved || !target.isConnected) return;

            frames += 1;

            if (frames > 140) {
                align();
                return;
            }

            if (Math.round(window.scrollY) === Math.round(lastY)) {
                stableFrames += 1;
            } else {
                stableFrames = 0;
                lastY = window.scrollY;
            }

            if (stableFrames >= 4) {
                align();
                return;
            }

            requestAnimationFrame(tick);
        };

        requestAnimationFrame(tick);
    };

    if (document.readyState === 'complete') {
        alignWhenStable();
    } else {
        window.addEventListener('load', alignWhenStable, { once: true });
    }

    document.fonts?.ready.then(alignWhenStable);
}


function numberFrom(el, key, fallback) {
    const raw = el?.dataset?.[key];

    if (raw === undefined || raw === '') return fallback;

    const parsed = Number.parseFloat(raw);

    return Number.isFinite(parsed) ? parsed : fallback;
}

function stringFrom(el, key, fallback) {
    return el?.dataset?.[key] || fallback;
}

function debounce(fn, wait) {
    let timer = 0;

    const run = () => {
        clearTimeout(timer);
        timer = setTimeout(fn, wait);
    };

    run.cancel = () => clearTimeout(timer);

    return run;
}

/**
 * Vars awal entrance berdasarkan tipe animasi. Semua properti adalah transform/opacity
 * agar tidak memicu reflow saat animasi berjalan.
 */
function entranceVars(type, { distance, duration, delay = 0 }) {
    switch (type) {
        case 'fade-in':
            return { autoAlpha: 0, duration, delay };
        case 'fade-scale':
            return {
                autoAlpha: 0,
                y: Math.round(distance * 0.65),
                scale: 0.97,
                duration,
                delay,
            };
        case 'slide-left':
            return { autoAlpha: 0, x: -distance, duration, delay };
        case 'slide-right':
            return { autoAlpha: 0, x: distance, duration, delay };
        case 'line-grow':
            return { scaleX: 0, transformOrigin: 'left center', duration, delay };
        case 'fade-up':
        default:
            return { autoAlpha: 0, y: distance, duration, delay };
    }
}

/**
 * Beberapa kartu memakai utility Tailwind `transition` (yang juga men-transition
 * transform/opacity). Selama GSAP menulis transform tiap frame, CSS transition
 * tersebut membuat animasi terasa lag. Transition dinonaktifkan sementara lewat
 * inline style lalu dilepas kembali setelah animasi selesai / context di-revert.
 */
function createTransitionLock() {
    const locked = new Set();

    return {
        lock(el) {
            if (!isElement(el) || locked.has(el)) return;

            el.style.transition = 'none';
            locked.add(el);
        },
        release(el) {
            if (!isElement(el) || !locked.has(el)) return;

            el.style.removeProperty('transition');
            locked.delete(el);
        },
        releaseAll() {
            locked.forEach((el) => el.style.removeProperty('transition'));
            locked.clear();
        },
    };
}

/**
 * Hero tampil di atas fold, jadi dijalankan sebagai timeline saat load (bukan
 * ScrollTrigger) agar urutan heading -> deskripsi -> CTA -> foto terasa menyatu.
 */
function initHero(hero, { isMobile, lock }) {
    const heading = hero.querySelector('[data-hero-heading]');
    const description = hero.querySelector('[data-hero-description]');
    const cta = hero.querySelector('[data-hero-cta]');
    const media = hero.querySelector('[data-hero-media]');
    const elements = [heading, description, cta, media].filter(Boolean);

    if (!elements.length) return;

    elements.forEach((el) => lock.lock(el));

    const timeline = gsap.timeline({
        delay: 0.08,
        defaults: { ease: 'power3.out' },
        onComplete: () => elements.forEach((el) => lock.release(el)),
    });

    if (heading) {
        timeline.from(
            heading,
            {
                autoAlpha: 0,
                y: isMobile ? 20 : 28,
                duration: isMobile ? 0.7 : 0.85,
                clearProps: 'transform',
            },
            0
        );
    }

    if (description) {
        timeline.from(
            description,
            {
                autoAlpha: 0,
                y: isMobile ? 16 : 22,
                duration: isMobile ? 0.6 : 0.75,
                clearProps: 'transform',
            },
            '-=0.55'
        );
    }

    if (cta) {
        timeline.from(
            cta,
            {
                autoAlpha: 0,
                y: isMobile ? 14 : 18,
                duration: 0.55,
                clearProps: 'transform',
            },
            '-=0.5'
        );
    }

    if (media) {
        timeline.from(
            media,
            {
                autoAlpha: 0,
                y: isMobile ? 18 : 24,
                scale: 0.98,
                duration: isMobile ? 0.8 : 1,
                // Transform dibersihkan agar tidak ada sisa scale pada ancestor Swiper.
                clearProps: 'transform',
            },
            '-=0.45'
        );
    }
}

/**
 * Grup: satu ScrollTrigger + satu timeline untuk child langsung, sehingga
 * entrance kartu berjalan berurutan (stagger) tanpa membuat banyak trigger.
 */
function initGroupReveals(root, { isMobile, lock }) {
    const distanceFactor = isMobile ? MOBILE_DISTANCE_FACTOR : 1;

    root.querySelectorAll(GROUP_SELECTOR).forEach((group) => {
        const items = Array.from(group.children).filter(isRevealable);

        if (!items.length) return;

        const type = stringFrom(group, 'animateGroup', 'fade-up');
        const stagger = numberFrom(group, 'animateStagger', DEFAULT_STAGGER);
        const duration = numberFrom(group, 'animateDuration', DEFAULT_DURATION);
        const fallbackDistance = numberFrom(group, 'animateDistance', DEFAULT_DISTANCE);
        const start = stringFrom(group, 'animateStart', DEFAULT_START);
        const ease = stringFrom(group, 'animateEase', DEFAULT_EASE);
        const delay = numberFrom(group, 'animateDelay', 0);

        items.forEach((item) => lock.lock(item));

        const timeline = gsap.timeline({
            delay,
            scrollTrigger: {
                trigger: group,
                start,
                once: true,
            },
            onComplete: () => items.forEach((item) => lock.release(item)),
        });

        items.forEach((item, index) => {
            const itemType = stringFrom(item, 'animateItem', type);
            const itemDistance =
                numberFrom(item, 'animateDistance', fallbackDistance) * distanceFactor;

            timeline.from(
                item,
                {
                    ...entranceVars(itemType, { distance: itemDistance, duration }),
                    ease,
                    // Transform dikembalikan ke CSS agar hover (mis. translate-y) tetap hidup.
                    clearProps: 'transform',
                },
                index * stagger
            );
        });
    });
}

/**
 * Elemen tunggal di luar grup (mis. container statistik, link, banner).
 */
function initStandaloneReveals(root, { isMobile, lock }) {
    const distanceFactor = isMobile ? MOBILE_DISTANCE_FACTOR : 1;

    root.querySelectorAll(ITEM_SELECTOR).forEach((el) => {
        // Child grup & item dinamis sudah ditangani handler lain.
        if (el.closest(GROUP_SELECTOR) || el.closest(DYNAMIC_SELECTOR)) return;

        const type = stringFrom(el, 'animate', 'fade-up');
        const duration = numberFrom(el, 'animateDuration', DEFAULT_DURATION);
        const distance = numberFrom(el, 'animateDistance', DEFAULT_DISTANCE) * distanceFactor;
        const delay = numberFrom(el, 'animateDelay', 0);
        const start = stringFrom(el, 'animateStart', DEFAULT_START);
        const ease = stringFrom(el, 'animateEase', DEFAULT_EASE);

        lock.lock(el);

        gsap.from(el, {
            ...entranceVars(type, { distance, duration }),
            delay,
            ease,
            clearProps: 'transform',
            scrollTrigger: {
                trigger: el,
                start,
                once: true,
            },
            onComplete: () => lock.release(el),
        });
    });
}

/**
 * Konten yang dirender setelah JavaScript berjalan (Alpine x-for pada grid berita).
 * MutationObserver membuat kartu baru tetap mendapat entrance animation, dan
 * trigger milik kartu yang dilepas Alpine ikut dibersihkan agar tidak menumpuk.
 */
function initDynamicReveals(root, { isMobile, lock }) {
    const containers = Array.from(root.querySelectorAll(DYNAMIC_SELECTOR));

    if (!containers.length) return () => {};

    const distanceFactor = isMobile ? MOBILE_DISTANCE_FACTOR : 1;
    const observers = [];
    const animated = new WeakSet();
    const scheduleRefresh = debounce(() => ScrollTrigger.refresh(), DYNAMIC_REFRESH_DELAY);

    const cleanupNode = (node) => {
        if (!isElement(node)) return;

        ScrollTrigger.getAll().forEach((trigger) => {
            if (trigger.trigger === node) trigger.kill();
        });

        gsap.killTweensOf(node);
        lock.release(node);
        animated.delete(node);
    };

    containers.forEach((container) => {
        const defaultType = stringFrom(container, 'animateDynamic', 'fade-up');
        const defaultDuration = numberFrom(container, 'animateDuration', DEFAULT_DURATION);
        const defaultDistance = numberFrom(container, 'animateDistance', DEFAULT_DISTANCE);
        const defaultStagger = numberFrom(
            container,
            'animateStagger',
            DEFAULT_DYNAMIC_STAGGER
        );
        const start = stringFrom(container, 'animateStart', DEFAULT_START);
        const ease = stringFrom(container, 'animateEase', DEFAULT_EASE);

        const reveal = (nodes) => {
            const fresh = Array.from(nodes).filter(
                (node) => isRevealable(node) && !animated.has(node)
            );

            if (!fresh.length) return;

            fresh.forEach((node) => {
                animated.add(node);
                lock.lock(node);
            });

            fresh.forEach((node, index) => {
                const type = stringFrom(node, 'animateItem', defaultType);
                const duration = numberFrom(node, 'animateDuration', defaultDuration);
                const distance =
                    numberFrom(node, 'animateDistance', defaultDistance) * distanceFactor;

                gsap.from(node, {
                    ...entranceVars(type, { distance, duration }),
                    delay: index * defaultStagger,
                    ease,
                    clearProps: 'transform',
                    scrollTrigger: {
                        trigger: node,
                        start,
                        once: true,
                    },
                    onComplete: () => lock.release(node),
                });
            });

            // Tinggi grid berubah setelah filter/pagination: posisi trigger lain
            // (kalender, CTA) perlu dihitung ulang.
            scheduleRefresh();
        };

        const observer = new MutationObserver((mutations) => {
            const added = [];

            mutations.forEach((mutation) => {
                mutation.removedNodes.forEach(cleanupNode);

                mutation.addedNodes.forEach((node) => {
                    if (isElement(node)) added.push(node);
                });
            });

            if (added.length) reveal(added);
        });

        observer.observe(container, { childList: true });
        observers.push(observer);

        // Jika container sudah berisi (mis. saat matchMedia dijalankan ulang).
        reveal(Array.from(container.children));
    });

    return () => {
        observers.forEach((observer) => observer.disconnect());
        scheduleRefresh.cancel();
    };
}

export function initPageAnimations() {
    if (initialized) return;

    const roots = Array.from(document.querySelectorAll(ROOT_SELECTOR));

    if (!roots.length) return;

    initialized = true;

    // Deep-link/restored-scroll: biarkan konten tampil utuh tanpa entrance animation,
    // lalu ratakan posisi ke anchor. Ini mencegah layar kosong karena ScrollTrigger
    // melewati elemen yang sudah berada di atas posisi scroll saat halaman dimuat.
    if (isDeepLinkEntry(roots)) {
        settleDeepLink();
        return;
    }

    // matchMedia membuat context internal: seluruh tween & ScrollTrigger otomatis
    // di-revert saat viewport berpindah breakpoint atau preferensi gerak berubah.
    mediaContext = gsap.matchMedia();

    mediaContext.add(
        {
            reduceMotion: '(prefers-reduced-motion: reduce)',
            motionAllowed: '(prefers-reduced-motion: no-preference)',
            isMobile: '(max-width: 767px)',
            isDesktop: '(min-width: 768px)',
        },
        (context) => {
            // Reduced motion: biarkan konten tampil apa adanya tanpa animasi entrance.
            if (context.conditions.reduceMotion) return;

            const lock = createTransitionLock();
            const options = { isMobile: !context.conditions.isDesktop, lock };
            const cleanups = [];

            roots.forEach((root) => {
                const hero = root.querySelector(HERO_SELECTOR);
                if (hero) initHero(hero, options);

                initGroupReveals(root, options);
                initStandaloneReveals(root, options);

                const cleanup = initDynamicReveals(root, options);
                if (typeof cleanup === 'function') cleanups.push(cleanup);
            });

            return () => {
                cleanups.forEach((cleanup) => cleanup());
                lock.releaseAll();
            };
        }
    );

    const refresh = () => ScrollTrigger.refresh();

    // Posisi trigger bergeser setelah gambar/font selesai dimuat.
    if (document.readyState === 'complete') {
        refresh();
    } else {
        window.addEventListener('load', refresh, { once: true });
    }

    document.fonts?.ready.then(refresh);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPageAnimations, { once: true });
} else {
    initPageAnimations();
}
