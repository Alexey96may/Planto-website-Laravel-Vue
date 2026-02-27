<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';

    import Best from '@/Components/Best.vue';
    import Hero from '@/Components/Hero.vue';
    import Review from '@/Components/Review.vue';
    import Top from '@/Components/Top.vue';
    import Trendy from '@/Components/Trendy.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Comment, Feature, Product } from '@/types';

    defineSlots<{
        default(props: {}): any;
        hero(props: {}): any;
        trendy(props: {}): any;
    }>();

    interface Props {
        topPlants: Product[];
        trendyPlants: Product[];
        heroPlants: Product[];
        status: string;
        comments: Comment[];
        features: Feature[];
    }

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

    <MainLayout :full="true">
        <template #hero>
            <Hero :heroPlants="heroPlants" />
        </template>
        <template #trendy>
            <Trendy v-if="trendyPlants.length" :trendyPlants="trendyPlants" />
        </template>

        <Top :topPlants="topPlants" />
        <Review v-if="comments.length" :comments="comments" />
        <Best :features="features" />
    </MainLayout>
</template>
