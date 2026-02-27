<script setup lang="ts">
    import { VNode } from 'vue';

    import { Head } from '@inertiajs/vue3';

    import Best from '@/Components/Best.vue';
    import Hero from '@/Components/Hero.vue';
    import Review from '@/Components/Review.vue';
    import Top from '@/Components/Top.vue';
    import Trendy from '@/Components/Trendy.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Comment, Feature, Product } from '@/types';

    interface Props {
        topPlants: Product[];
        trendyPlants: Product[];
        heroPlants: Product[];
        status: string;
        comments: Comment[];
        features: Feature[];
    }

    defineOptions({
        layout: (h: any, page: VNode) =>
            h(
                MainLayout,
                {
                    full: true,
                },
                () => page,
            ),
    });

    const props = withDefaults(defineProps<Props>(), {
        topPlants: () => [],
        trendyPlants: () => [],
        heroPlants: () => [],
        comments: () => [],
        features: () => [],
    });
</script>

<template>
    <Head title="Main" />

    <template #hero>
        <Hero :heroPlants="heroPlants" />
    </template>
    <template #trendy>
        <Trendy v-if="trendyPlants.length" :trendyPlants="trendyPlants" />
    </template>

    <Top :topPlants="topPlants" />
    <Review v-if="comments.length" :comments="comments" />
    <Best :features="features" />
</template>
