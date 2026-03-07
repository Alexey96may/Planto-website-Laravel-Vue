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

    const stars = computed(() => getStarStats(Number(rating) || 0, max));
</script>

<template>
    <div class="rating-display flex items-center" :aria-label="`Rating: ${rating} out of ${max}`">
        <IconStar v-for="n in stars.full" :key="'full-' + n" class="rating-star hover:scale-110" />

        <IconStarHalf v-if="stars.half" class="rating-star hover:scale-110" />

        <IconStarEmpty
            v-for="n in stars.empty"
            :key="'empty-' + n"
            class="rating-star hover:scale-110"
        />
    </div>
</template>

<style scoped>
    .rating-display {
        display: flex;
        gap: 0.25rem;
    }

    .rating-star {
        width: 0.75rem;
        transition: transform 0.25s;
        height: 0.75rem;
    }
</style>
