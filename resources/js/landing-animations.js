/**
 * Motion layer untuk landing page Yayasan Himatun Ayat.
 *
 * Semua entrance animation didaftarkan dari satu tempat supaya:
 * - selector dibatasi pada root [data-landing-animations] (tidak bocor ke halaman lain),
 * - lifecycle dikelola gsap.matchMedia() (viewport + prefers-reduced-motion),
 * - tidak ada inisialisasi ganda dan seluruh ScrollTrigger ikut dibersihkan saat revert.
 *
 * Penanda di markup:
 * - data-animate="fade-up|fade-in|fade-scale|slide-left|slide-right" -> elemen tunggal
 * - data-animate-group="<tipe>"                                      -> stagger untuk child langsung
 * - data-animate-item="<tipe>"                                       -> override tipe pada child grup
 * - data-animate-distance / -duration / -stagger / -delay / -start / -ease
 * - data-animate-hero + data-hero-heading|description|cta|media       -> timeline hero
 *
 * Tidak ada CSS awal opacity: 0. Elemen hanya disembunyikan oleh JS, sehingga konten
 * tetap terbaca penuh bila JavaScript gagal dimuat.
 */

import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

gsap.registerPlugin(ScrollTrigger);

const ROOT_SELECTOR = '[data-landing-animations]';
const GROUP_SELECTOR = '[data-animate-group]';
const ITEM_SELECTOR = '[data-animate]';
const HERO_SELECTOR = '[data-animate-hero]';

const DEFAULT_DISTANCE = 24;
const DEFAULT_DURATION = 0.75;
const DEFAULT_STAGGER = 0.1;
const DEFAULT_START = 'top 85%';
const DEFAULT_EASE = 'power3.out';
const MOBILE_DISTANCE_FACTOR = 0.8;

let mediaContext = null;
let initialized = false;

const isElement = (node) => node && node.nodeType === Node.ELEMENT_NODE;

function numberFrom(el, key, fallback) {
    const raw = el?.dataset?.[key];

    if (raw === undefined || raw === '') return fallback;

    const parsed = Number.parseFloat(raw);

    return Number.isFinite(parsed) ? parsed : fallback;
}

function stringFrom(el, key, fallback) {
    return el?.dataset?.[key] || fallback;
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
        const items = Array.from(group.children).filter(isElement);

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
        // Child grup sudah ditangani initGroupReveals().
        if (el.closest(GROUP_SELECTOR)) return;

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

export function initLandingAnimations() {
    if (initialized) return;

    const root = document.querySelector(ROOT_SELECTOR);

    if (!root) return;

    initialized = true;

    const lock = createTransitionLock();

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

            const options = { isMobile: !context.conditions.isDesktop, lock };

            const hero = root.querySelector(HERO_SELECTOR);
            if (hero) initHero(hero, options);

            initGroupReveals(root, options);
            initStandaloneReveals(root, options);

            return () => lock.releaseAll();
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
    document.addEventListener('DOMContentLoaded', initLandingAnimations, { once: true });
} else {
    initLandingAnimations();
}
