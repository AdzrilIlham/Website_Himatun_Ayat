import Swiper from 'swiper';
import { Autoplay, EffectCards } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/effect-cards';

const HERO_SWIPER_SELECTOR = '.hero-image-swiper';

const heroSwiperConfig = {
    modules: [Autoplay, EffectCards],

    direction: 'vertical',
    spaceBetween: 8,
    slidesPerGroupAuto: true,
    speed: 700,
    centeredSlides: true,
    centerInsufficientSlides: true,
    roundLengths: true,
    grabCursor: true,
    loop: true,

    effect: 'cards',
    cardsEffect: {
        // 8deg/8% membuat sudut kartu belakang naik sampai menembus navbar setelah
        // semua slide menumpuk, jadi rotasi/offset diturunkan agar tetap di bawah navbar.
        perSlideRotate: 5,
        perSlideOffset: 4,
        // Shadow dari Swiper dimatikan agar tidak menambah shadow baru pada frame hero.
        slideShadows: false,
    },

    autoplay: {
        delay: 2500,
        disableOnInteraction: false,
    },
};

function initHeroSwipers() {
    document.querySelectorAll(HERO_SWIPER_SELECTOR).forEach((el) => {
        // Cegah instance ganda saat init dijalankan lebih dari sekali.
        if (el.swiper) return;

        new Swiper(el, heroSwiperConfig);
    });
}

function destroyHeroSwipers() {
    document.querySelectorAll(HERO_SWIPER_SELECTOR).forEach((el) => {
        el.swiper?.destroy(true, true);
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initHeroSwipers, { once: true });
} else {
    initHeroSwipers();
}

// Jaga-jaga bila nanti halaman dirender ulang lewat Livewire navigation.
document.addEventListener('livewire:navigating', destroyHeroSwipers);
document.addEventListener('livewire:navigated', initHeroSwipers);
