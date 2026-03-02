<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconArrowRight from 'img/icons/arrow-right.svg?component';
    import IconPlay from 'img/icons/play.svg?component';

    import HeroSliderCard from '@/Components/Cards/HeroSliderCard.vue';
    import Modal from '@/Components/Modal.vue';
    import AppRating from '@/Components/UI/AppRating.vue';
    import { ProductWithCategory, Review, SharedData } from '@/types';
    import { handleImageError } from '@/utils';

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
        <div class="container hero__container">
            <div class="hero__info" aria-label="Hero information">
                <div class="hero__info-main" aria-label="Hero main information">
                    <h2 class="hero__title">
                        {{ $page.props.settings?.section_hero_title }}
                    </h2>

                    <p class="hero__descr">
                        {{ $page.props.settings?.hero_main_text }}
                    </p>

                    <div class="hero__buttons" aria-label="Hero buttons">
                        <button
                            class="button--rect"
                            aria-label="Press to explore"
                            id="heroExploreButton"
                        >
                            <Link href="/shop" aria-label="To see more plants"> Explore </Link>
                        </button>

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
                                :disabled="!videoUrl"
                            >
                                <IconPlay
                                    class="hero__video--img"
                                    aria-label="Play icon"
                                ></IconPlay>
                            </button>

                            <Modal v-if="isVideoModalOpen" @close="isVideoModalOpen = false">
                                <div class="aspect-video">
                                    <iframe
                                        :src="videoUrl"
                                        class="w-full h-full"
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

                <figure class="comment hero__comment" aria-label="Main comment" v-if="comment">
                    <div class="comment__author author" aria-label="Comment author">
                        <div class="author__photo" aria-label="Author`s photo">
                            <img
                                :src="comment.user?.avatar_url ? comment.user.avatar_url : ''"
                                alt="Author photo"
                                @error="handleImageError"
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

            <div class="slider-mini hero__slider-mini" aria-label="Slider">
                <div class="slider-mini__cards" aria-label="Slider cards">
                    <HeroSliderCard v-for="plant in heroPlants" :key="plant.id" :plant="plant" />
                </div>

                <div class="slider-mini__dir-buttons" aria-label="Slider direction buttons">
                    <button class="button slider-mini__left" aria-label="Left">
                        <IconArrowRight
                            class="slider-mini__left-img"
                            aria-label="Arrow icon"
                        ></IconArrowRight>
                    </button>

                    <button class="button slider-mini__right" aria-label="Right">
                        <IconArrowRight
                            class="slider-mini__right-img"
                            aria-label="Arrow icon"
                        ></IconArrowRight>
                    </button>
                </div>

                <div class="slider-mini__bottom-buttons" aria-label="Slider bottom buttons">
                    <button
                        class="bottom-button bottom-button--active"
                        aria-label="To active slide"
                    ></button>
                    <button class="bottom-button" aria-label="To second slide"></button>
                    <button class="bottom-button" aria-label="To third slide"></button>
                </div>
            </div>
        </div>
    </section>
</template>
