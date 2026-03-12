<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { usePage } from '@inertiajs/vue3';

    import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/outline';
    import IconPlay from 'img/icons/play.svg?component';
    import 'swiper/css';
    import 'swiper/css/navigation';
    import 'swiper/css/pagination';
    import { Autoplay, Navigation, Pagination } from 'swiper/modules';
    import { Swiper, SwiperSlide } from 'swiper/vue';
    import { route } from 'ziggy-js';

    import HeroSliderCard from '@/Components/Cards/HeroSliderCard.vue';
    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import AppRating from '@/Components/UI/AppRating.vue';
    import Modal from '@/Components/UI/Modal.vue';
    import { ProductWithCategory, Review, SharedData } from '@/types';

    const modules = [Pagination, Navigation, Autoplay];

    interface Props {
        heroPlants: ProductWithCategory[];
        comment: Review | null;
    }

    const { heroPlants = [], comment = null } = defineProps<Props>();

    const page = usePage<SharedData>();

    const videoUrl = computed(() => page.props.settings?.live_demo_url ?? '');
    const isVideoModalOpen = ref(false);

    const openVideo = () => {
        if (videoUrl.value) {
            isVideoModalOpen.value = true;
        } else {
            console.warn('Video link is missed!');
        }
    };
</script>

<template>
    <section class="hero" id="hero" aria-label="Hero section">
        <div class="hero__container container gap-12">
            <div class="hero__info" aria-label="Hero information">
                <div class="hero__info-main" aria-label="Hero main information">
                    <h2 class="hero__title">
                        {{ $page.props.settings?.section_hero_title }}
                    </h2>

                    <p class="hero__descr">
                        {{ $page.props.settings?.hero_main_text }}
                    </p>

                    <div class="hero__buttons" aria-label="Hero buttons">
                        <AppExploreButton
                            :href="route('shop')"
                            class="button--rect"
                            aria-label="To see more plants"
                        >
                            Explore
                        </AppExploreButton>

                        <div
                            class="hero__video-prevue"
                            aria-label="Hero video field"
                            title="Live Demonstration"
                            v-if="videoUrl"
                        >
                            <button
                                class="button--circ play-btn"
                                aria-label="Play video"
                                @click="openVideo"
                            >
                                <IconPlay
                                    class="hero__video--img"
                                    aria-label="Play icon"
                                ></IconPlay>
                            </button>

                            <Modal :show="isVideoModalOpen" @close="isVideoModalOpen = false">
                                <div class="aspect-video">
                                    <iframe
                                        :src="videoUrl"
                                        class="h-full w-full"
                                        allowfullscreen
                                        allow="autoplay; encrypted-media"
                                    ></iframe>
                                </div>
                            </Modal>

                            <span class="hero__video-descr" aria-label="Video description"
                                >Live Demonstration</span
                            >
                        </div>
                    </div>
                </div>

                <figure
                    class="comment hero__comment reveal-item"
                    style="transition-delay: 500ms"
                    aria-label="Main comment"
                    v-if="comment"
                >
                    <div class="comment__author author" aria-label="Comment author">
                        <div class="author__photo" aria-label="Author`s photo">
                            <AppImage
                                :src="comment.user?.avatar_url ? comment.user.avatar_url : ''"
                                alt="Author photo"
                            />
                        </div>

                        <div class="author__info" aria-label="Author`s info">
                            <p class="author__name" aria-label="Author`s name">
                                {{ comment.user?.name }}
                            </p>

                            <div class="authors__assessment" aria-label="Author`s assessment">
                                <AppRating :rating="comment.rating" />
                            </div>
                        </div>
                    </div>

                    <div class="comment__message" aria-label="Author`s message">
                        <p>
                            {{ comment.body }}
                        </p>
                    </div>
                </figure>
            </div>

            <div class="slider-container group relative w-full flex-shrink-0 lg:w-[40%]">
                <swiper
                    :slides-per-view="1"
                    :space-between="20"
                    :modules="modules"
                    :loop="true"
                    :pagination="{
                        clickable: true,
                        dynamicBullets: true,
                    }"
                    :navigation="{
                        prevEl: '.prev-btn',
                        nextEl: '.next-btn',
                    }"
                    :autoplay="{
                        delay: 6000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true,
                    }"
                    :speed="2000"
                    :grab-cursor="true"
                    class="rounded-3xl"
                >
                    <swiper-slide v-for="plant in heroPlants" :key="plant.id" class="!flex !h-auto">
                        <HeroSliderCard :plant="plant" class="xl:aspect-[128 / 157] w-full" />
                    </swiper-slide>
                </swiper>

                <button
                    class="prev-btn absolute left-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white backdrop-blur-md transition-all hover:bg-white/20 active:scale-90 disabled:opacity-0 group-hover:left-4"
                >
                    <ChevronLeftIcon class="h-6 w-6" />
                </button>

                <button
                    class="next-btn absolute right-4 top-1/2 z-10 -translate-y-1/2 rounded-full bg-white/10 p-2 text-white backdrop-blur-md transition-all hover:bg-white/20 active:scale-90 disabled:opacity-0 group-hover:right-4"
                >
                    <ChevronRightIcon class="h-6 w-6" />
                </button>
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .hero {
        @media (max-width: b.$mediaMobile) {
            padding: 0.8rem 0 0;
        }
    }

    .card {
        gap: 3rem;
    }

    .hero__container {
        @include b.flex(space-between, flex-start);
        gap: 2rem;

        @media (max-width: 1300px) {
            flex-direction: column;
            align-items: center;
        }
    }

    .hero__info {
        @include b.flex(flex-start, flex-start, column);
        gap: 5rem;
        max-width: calc(1rem * (892px / b.$basicFontSize));
        padding-top: 3.8rem;

        @media (max-width: 1115px) {
            padding-top: 0;
            gap: 3.25rem;
        }
    }

    .hero__info-main {
        @media (max-width: 1115px) {
            @include b.flex(flex-start, flex-start, column);
            gap: 0.8rem;
        }
    }

    .hero__title {
        font-size: 4.9rem;
        font-weight: 600;
        line-height: 1;
        margin-bottom: 1.5rem;

        @media (max-width: b.$mediaMobile) {
            font-size: 2.4rem;
        }
    }

    .hero__descr {
        font-size: 0.96rem;
        font-weight: 500;
        margin-bottom: 1.5rem;
        line-height: 1.22;

        @media (max-width: b.$mediaMobile) {
            font-size: 0.7rem;
        }
    }

    .hero__buttons {
        @include b.flex(flex-start, center);
        gap: 1.46rem;
    }

    .hero__video-prevue {
        white-space: nowrap;

        button {
            vertical-align: middle;
            margin-right: 0.66rem;
        }
    }

    .hero__video--img {
        padding-left: 0.4em;
        width: 1.16rem;
        height: 1.08rem;
        fill: b.$lightBgColor;

        @media (max-width: b.$mediaMobile) {
            padding-left: 0.2em;
        }
    }

    .hero__video-descr {
        display: inline-block;
        vertical-align: middle;
        font-size: 0.83rem;
        font-weight: 400;
        max-width: 4.83rem;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }

    .hero__slider-mini {
        flex-shrink: 0;
        align-self: center;
    }

    .hero__comment {
        aspect-ratio: 41 / 24;

        @media (max-width: 1115px) {
            display: none;
        }
    }

    :deep(.swiper-button-next),
    :deep(.swiper-button-prev) {
        display: none;
    }

    :deep(.swiper) {
        --swiper-pagination-color: #10b981 !important;

        --swiper-pagination-bullet-inactive-color: #b4b4bb !important;

        --swiper-pagination-bullet-inactive-opacity: 0.8;

        --swiper-pagination-bullet-horizontal-gap: 6px;
    }

    :deep(.swiper-pagination) {
        bottom: 1.5rem !important;
    }

    :deep(.swiper-pagination-bullet-active) {
        width: 20px;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    :deep(.swiper) {
        height: 100%;
    }

    :deep(.swiper-wrapper) {
        display: flex !important;
        align-items: stretch !important;
    }

    :deep(.swiper-slide) {
        height: auto !important;
    }

    .reveal-item {
        opacity: 0;
        transform: translate3d(0, 25px, 0);
        transition:
            opacity 0.6s ease-out,
            transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);
        -webkit-font-smoothing: antialiased;
        backface-visibility: hidden;
        will-change: transform, opacity;
        outline: 1px solid transparent;
        background-clip: padding-box;
        -webkit-perspective: 1000px;
    }

    .reveal-visible .reveal-item {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
</style>
