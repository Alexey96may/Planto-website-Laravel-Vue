<script setup>
import IconStar from "img/icons/star.svg?component";
import IconStarHalf from "img/icons/star-half.svg?component";
import { Link } from "@inertiajs/vue3";
import ReviewCard from "./ReviewCard.vue";

defineProps({
    comments: Array,
});
</script>

<template>
    <section class="review" id="review" aria-label="Review section">
        <div class="container review__container">
            <h2
                class="title review__title"
                v-if="$page.props.settings.section_reviews_link"
            >
                <Link
                    :href="$page.props.settings.section_reviews_link"
                    :aria-label="$page.props.settings.section_reviews_title"
                >
                    {{ $page.props.settings.section_reviews_title }}
                </Link>
            </h2>
            <h2 class="title review__title" v-else>
                {{ $page.props.settings.section_reviews_title }}
            </h2>

            <div class="review__cards" aria-label="Cards field with Review">
                <ReviewCard
                    v-for="item in comments"
                    :key="item.id"
                    :comment="item"
                />
            </div>
        </div>
    </section>
</template>
