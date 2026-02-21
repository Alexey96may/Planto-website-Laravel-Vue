<script setup>
import { Link } from "@inertiajs/vue3";
import IconArrowRight from "img/icons/arrow-right.svg?component";

defineProps({
    feature: {
        type: Object,
        default: () => {},
    },
});
</script>

<template>
    <figure class="card slider__card" aria-label="Slider card">
        <div class="card__img-wrapper" aria-label="Slider card image">
            <img :src="feature?.image_url" :alt="feature?.title" />
        </div>

        <div class="card__info" aria-label="Card information">
            <h3 class="card__title" aria-label="Slider card title">
                <Link
                    :href="feature?.link"
                    class="card__tag-title"
                    :aria-label="'To ' + feature?.title"
                    >{{ feature?.title }}</Link
                >
            </h3>

            <div
                class="card__descr-wrapper"
                aria-label="Card description area"
                v-if="feature?.description"
                v-html="feature?.description"
            ></div>

            <div class="card__buying" aria-label="Plant buying info">
                <button class="button--rect card__button" aria-label="Explore">
                    <Link :href="feature?.link" aria-label="To see more plants">
                        Explore
                    </Link>
                </button>

                <div class="card__number" aria-label="Plant card number">
                    <button class="button slider-mini__left" aria-label="Left">
                        <IconArrowRight
                            class="slider-mini__left-img"
                            aria-label="Arrow icon"
                        ></IconArrowRight>
                    </button>

                    <span class="card__number-info">
                        <span class="card__number-prev">01</span>/<span
                            class="card__number-next"
                            >04</span
                        >
                    </span>

                    <button
                        class="button slider-mini__right"
                        aria-label="Right"
                    >
                        <IconArrowRight
                            class="slider-mini__right-img"
                            aria-label="Arrow icon"
                        ></IconArrowRight>
                    </button>
                </div>
            </div>
        </div>
    </figure>
</template>
