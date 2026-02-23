<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    plant: {
        type: Object,
        default: () => {},
    },
});
</script>

<template>
    <figure class="card slider-mini__card" aria-label="Slider card">
        <div class="card__img-wrapper" aria-label="Slider card image">
            <img :src="plant?.image" :alt="plant.title" />
        </div>

        <span
            class="card__tag card__tag-link"
            aria-label="Slider card tag"
            v-if="plant.category?.title"
            >{{ plant.category?.title }}</span
        >
        <h3 class="card__title card__tag-title" aria-label="Slider card title">
            {{ plant.title }}
            <a
                href="#"
                class="card__tag-title"
                aria-label="To Calathea plant"
            ></a>
        </h3>

        <button class="button--rect card__button" aria-label="Buy now">
            <Link
                :href="route('shop.show', plant.id)"
                :aria-label="plant.title"
            ></Link>
            Buy Now
        </button>
    </figure>
</template>
