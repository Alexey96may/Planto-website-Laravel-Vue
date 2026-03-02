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

    .top__comment {
        max-width: unset;
        width: calc(1rem * (512px / b.$basicFontSize));
        min-height: calc(1rem * (480px / b.$basicFontSize));
        background: url('../../images/comment_bg.png') center / 100% 100% no-repeat;
        padding: 3.75rem 2.1rem 4rem 2.4rem;

        @media (max-width: b.$mediaMobile) {
            padding: 3rem 1.5rem 3.5rem 2rem;
            min-height: calc(1rem * (340px / b.$basicFontSize));
        }

        .author {
            gap: calc(1rem * (38px / b.$basicFontSize));
        }

        .author__photo {
            max-width: calc(1rem * (88px / b.$basicFontSize));
            height: calc(1rem * (88px / b.$basicFontSize));
        }

        .author__name {
            font-size: calc(1rem * (38px / b.$basicFontSize));
            color: b.$lightTextColor;
            font-weight: 600;

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (26px / b.$basicFontSize));
            }
        }

        .comment__message {
            font-size: calc(1rem * (24px / b.$basicFontSize));
            line-height: calc(29px / b.$basicFontSize);

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (18px / b.$basicFontSize));
                line-height: 1.4;
            }
        }

        .authors__assessment {
            gap: calc(1rem * (11px / b.$basicFontSize));

            @media (max-width: b.$mediaMobile) {
                gap: calc(1rem * (10px / b.$basicFontSize));
            }
        }

        .authors__star {
            width: calc(1rem * (19px / b.$basicFontSize));

            @media (max-width: b.$mediaMobile) {
                width: calc(1rem * (14px / b.$basicFontSize));
            }
        }

        .comment__author {
            margin-bottom: calc(1rem * (61px / b.$basicFontSize));

            @media (max-width: b.$mediaMobile) {
                margin-bottom: calc(1rem * (36px / b.$basicFontSize));
            }
        }
    }
</style>
