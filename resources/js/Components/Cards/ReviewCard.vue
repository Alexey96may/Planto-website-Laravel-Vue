<script setup>
    import { computed } from 'vue';

    import AppImage from '@/Components/UI/AppImage.vue';
    import AppRating from '@/Components/UI/AppRating.vue';

    const props = defineProps({
        comment: Object,
    });

    const starNumber = computed(() => {
        const val = Math.floor(Number(props.comment.rating) || 0);
        return Math.max(0, val);
    });

    const isHalfStar = computed(() => {
        const val = Number(props.comment.rating) || 0;
        return val % 1 !== 0;
    });
</script>

<template>
    <figure class="comment top__comment" aria-label="Review">
        <div class="comment__author author" aria-label="Comment author">
            <div class="author__photo" aria-label="Author`s photo">
                <AppImage :src="comment.user.avatar_url || defaultAvatarLink" alt="Author photo" />
            </div>

            <div class="author__info" aria-label="Author`s info">
                <p class="author__name" aria-label="Author`s name">
                    {{ comment.user.name }}
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
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .top__comment {
        max-width: unset;
        width: calc(1rem * (512px / b.$basicFontSize));
        min-height: calc(1rem * (480px / b.$basicFontSize));
        background: url('../../../images/comment_bg.png') center / 100% 100% no-repeat;
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

        .author__info {
            gap: 0.5rem;
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
