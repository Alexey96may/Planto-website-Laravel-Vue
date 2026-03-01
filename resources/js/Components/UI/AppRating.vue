<script setup lang="ts">
    import { computed } from 'vue';

    import IconStarEmpty from 'img/icons/star-empty.svg?component';
    import IconStarHalf from 'img/icons/star-half.svg?component';
    import IconStar from 'img/icons/star.svg?component';

    import { getStarStats } from '@/utils/math';

    interface Props {
        rating: number | string | undefined | null;
        max?: number;
    }

    const { rating = 0, max = 5 } = defineProps<Props>();

    const stars = computed(() => getStarStats(rating, max));
</script>

<template>
    <div class="rating-display flex items-center" :aria-label="`Rating: ${rating} out of ${max}`">
        <IconStar v-for="n in stars.full" :key="'full-' + n" class="rating-star" />

        <IconStarHalf v-if="stars.half" class="rating-star" />

        <IconStarEmpty v-for="n in stars.empty" :key="'empty-' + n" class="rating-star" />
    </div>
</template>

<style scoped>
    .rating-display {
        display: flex;
        gap: 0.25rem;
    }

    .rating-star {
        width: 1.25rem;
        height: 1.25rem;
    }
</style>
