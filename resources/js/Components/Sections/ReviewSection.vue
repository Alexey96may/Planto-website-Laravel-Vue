<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import ReviewCard from '@/Components/Cards/ReviewCard.vue';
    import { Review, SharedData } from '@/types';

    const page = usePage<SharedData>();

    const { comments = [] } = defineProps<{
        comments?: Review[];
    }>();

    const reviewsTitle = computed<string>(() => {
        return page.props.settings?.section_reviews_title ?? 'Reviews';
    });
    const allReviewsUrl = computed<string | null>(() => {
        return page.props.settings?.section_reviews_link ?? null;
    });
</script>

<template>
    <section class="review" id="review" aria-label="Review section">
        <div class="review__container container">
            <h2 class="title review__title" v-if="allReviewsUrl">
                <Link :href="allReviewsUrl" :aria-label="reviewsTitle">
                    {{ reviewsTitle }}
                </Link>
            </h2>
            <h2 class="title review__title" v-else>
                {{ reviewsTitle }}
            </h2>

            <div class="review__cards" aria-label="Cards field with Review">
                <ReviewCard v-for="item in comments" :key="item.id" :comment="item" />
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .review {
        padding-top: calc(1rem * (219px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            padding-top: calc(1rem * (130px / b.$basicFontSize));
        }
    }

    .review__title {
        margin-bottom: calc(1rem * (182px / b.$basicFontSize));
        @media (max-width: b.$mediaMobile) {
            margin-bottom: calc(1rem * (122px / b.$basicFontSize));
        }
    }

    .review__cards {
        @include b.flex(center, center, row, wrap);
        column-gap: calc(1rem * (33px / b.$basicFontSize));
        row-gap: calc(1rem * (33px / b.$basicFontSize));
    }
</style>
