<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import { Review, SharedData } from '@/types';

    import ReviewCard from './ReviewCard.vue';

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
        <div class="container review__container">
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
