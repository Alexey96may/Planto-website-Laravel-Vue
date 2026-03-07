<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { usePage } from '@inertiajs/vue3';

    import { gsap } from 'gsap';
    import 'swiper/css';
    import 'swiper/css/effect-fade';
    import 'swiper/css/pagination';
    import { Autoplay, EffectFade, Pagination } from 'swiper/modules';
    import { Swiper, SwiperSlide } from 'swiper/vue';

    import PlantBestCard from '@/Components/Cards/BestCard.vue';
    import { Feature, SharedData } from '@/types';

    const modules = [EffectFade, Pagination, Autoplay];

    const page = usePage<SharedData>();

    const sectionTitle = computed(() => {
        return page.props.settings?.section_o2_title || '';
    });

    const sectionLink = computed(() => {
        return page.props.settings?.section_o2_link || '';
    });

    const { features = [] } = defineProps<{ features: Feature[] }>();

    const onSlideChangeTransitionStart = (swiper: any) => {
        const allSlides = swiper.el.querySelectorAll('.swiper-slide');
        const activeSlide = allSlides[swiper.activeIndex];

        allSlides.forEach((slide: HTMLElement, index: number) => {
            if (index !== swiper.activeIndex) {
                const pieces = slide.querySelectorAll(
                    '.best__card-image, .best__card-title, .best__card-description, p',
                );

                gsap.to(pieces, {
                    opacity: 0,
                    filter: 'blur(40px)',
                    x: () => gsap.utils.random(-200, 200),
                    y: () => gsap.utils.random(-150, 150),
                    rotation: () => gsap.utils.random(-45, 45),
                    scale: 1.5,
                    duration: 0.8,
                    ease: 'power2.inOut',
                    stagger: { amount: 0.2, from: 'random' },
                });
            }
        });

        const newPieces = activeSlide.querySelectorAll(
            '.best__card-image, .best__card-title, .best__card-description, p',
        );

        if (newPieces.length) {
            gsap.fromTo(
                newPieces,
                {
                    opacity: 0,
                    filter: 'blur(50px)',
                    x: () => gsap.utils.random(-150, 150),
                    y: () => gsap.utils.random(-100, 100),
                    rotation: () => gsap.utils.random(-30, 30),
                    scale: 0.5,
                },
                {
                    opacity: 1,
                    filter: 'blur(0px)',
                    x: 0,
                    y: 0,
                    rotation: 0,
                    scale: 1,
                    duration: 1.5,
                    ease: 'expo.out',
                    stagger: { amount: 0.3 },
                    clearProps: 'all',
                },
            );
        }
    };

    const swiperInstance = ref<any>(null);
    const currentIndex = ref(0);

    const onSwiper = (swiper: any) => {
        swiperInstance.value = swiper;
    };
</script>

<template>
    <section class="best" id="best" aria-label="Review section" v-if="features.length">
        <div class="best__container container">
            <h2 class="title best__title">
                <component
                    :is="sectionLink ? 'Link' : 'span'"
                    :href="sectionLink"
                    :aria-label="sectionLink ? sectionTitle : undefined"
                >
                    {{ sectionTitle }}
                </component>
            </h2>

            <div class="slider review__slider" aria-label="Slider">
                <swiper
                    :modules="modules"
                    :slides-per-view="1"
                    :space-between="30"
                    :loop="true"
                    :speed="2000"
                    effect="fade"
                    :fade-effect="{ crossFade: true }"
                    @slideChangeTransitionStart="onSlideChangeTransitionStart"
                    @swiper="onSwiper"
                    @slideChange="(s) => (currentIndex = s.realIndex)"
                    :autoplay="{
                        delay: 5000,
                        disableOnInteraction: false,
                    }"
                    :pagination="{
                        el: '.slider__bottom-buttons',
                        clickable: true,
                        bulletClass: 'bottom-button',
                        bulletActiveClass: 'bottom-button--active',
                        renderBullet: function (index, className) {
                            return `<button class='${className}' aria-label='Go to slide ${index + 1}'></button>`;
                        },
                    }"
                    class="best-swiper"
                >
                    <swiper-slide
                        v-for="(feature, index) in features"
                        :key="feature.id"
                        class="!flex !h-auto"
                    >
                        <PlantBestCard
                            :feature="feature"
                            class="h-full w-full"
                            :index="index"
                            :total="features.length"
                            @next="swiperInstance?.slideNext()"
                            @prev="swiperInstance?.slidePrev()"
                        />
                    </swiper-slide>
                </swiper>

                <div class="slider__bottom-buttons" aria-label="Slider bottom buttons"></div>
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .best {
        padding-top: calc(1rem * (219px / b.$basicFontSize));
        overflow: hidden;

        @media (max-width: b.$mediaMobile) {
            padding-top: calc(1rem * (130px / b.$basicFontSize));
        }
    }

    .best__title {
        margin-bottom: calc(1rem * (182px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            margin-bottom: calc(1rem * (162px / b.$basicFontSize));
        }
    }

    .best-swiper {
        width: 100%;
        padding-top: 4rem !important;
        overflow: visible !important;
        min-height: auto;

        @media (width >= 1024px) {
            min-height: 500px;
        }
    }

    :deep(.swiper-wrapper) {
        display: flex !important;
        align-items: stretch !important;
    }

    :deep(.swiper-slide) {
        height: auto !important;
        display: flex !important;
        pointer-events: none;
        opacity: 0 !important;
        transition-property: opacity !important;

        &.swiper-slide-active {
            pointer-events: auto;
            opacity: 1 !important;
        }
    }

    .slider__bottom-buttons {
        display: flex;
        justify-content: center;
        gap: 0.8rem;
        margin-top: 4rem;
        margin-bottom: 4rem;

        :deep(.bottom-button) {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background-color: #d1d1d1;
            border: none;
            padding: 0;
            cursor: pointer;
            transition: all 0.3s ease;

            &.bottom-button--active {
                background-color: #10b981;
                transform: scale(1.3);
            }
        }
    }

    :deep(.card__info),
    :deep(.card__img-wrapper) {
        will-change: filter, opacity, transform;
    }

    :deep(.card__info *) {
        transition: none !important;
    }

    :deep(.card__img-wrapper),
    :deep(.card__title),
    :deep(.card__descr-wrapper),
    :deep(.card__button),
    :deep(.card__number) {
        will-change: transform, opacity, filter;
        backface-visibility: hidden;
    }
</style>
