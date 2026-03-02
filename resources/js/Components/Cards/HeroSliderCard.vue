<script setup lang="ts">
    import { computed } from 'vue';

    import { usePage } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import { ProductWithCategory, SharedData } from '@/types';

    interface Props {
        plant?: ProductWithCategory;
    }

    const { plant = {} as ProductWithCategory } = defineProps<Props>();

    const page = usePage<SharedData>();

    const isInCart = computed(() => {
        return page.props?.cart_ids.includes(plant.id);
    });
</script>

<template>
    <figure class="card slider-mini__card" aria-label="Slider card">
        <div class="card__img-wrapper" aria-label="Slider card image">
            <AppImage :src="plant.image_url" :alt="plant.title" />
        </div>

        <span
            class="card__tag card__tag-link"
            aria-label="Slider card tag"
            v-if="plant.category?.title"
            >{{ plant.category?.title }}</span
        >
        <h3 class="card__title card__tag-title" aria-label="Slider card title">
            {{ plant.title }}
            <a href="#" class="card__tag-title" aria-label="To Calathea plant"></a>
        </h3>

        <AppExploreButton
            :href="route('shop.show', plant.id)"
            class="button--rect card__button"
            :aria-label="plant.title"
        >
            Buy Now
        </AppExploreButton>
    </figure>
</template>
