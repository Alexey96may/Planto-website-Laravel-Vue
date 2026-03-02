<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Head, router, usePage } from '@inertiajs/vue3';

    import Best from '@/Components/Sections/BestSection.vue';
    import Hero from '@/Components/Sections/HeroSection.vue';
    import Review from '@/Components/Sections/ReviewSection.vue';
    import Top from '@/Components/Sections/TopSection.vue';
    import Trendy from '@/Components/Sections/TrendySection.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import {
        Feature,
        Product,
        ProductWithCategory,
        Review as ReviewInterface,
        SharedData,
    } from '@/types';

    interface Props {
        topPlants: Product[];
        trendyPlants: Product[];
        heroPlants: ProductWithCategory[];
        status: string;
        comments: ReviewInterface[];
        features: Feature[];
    }

    defineSlots<{
        default(props: {}): any;
        hero(props: {}): any;
        trendy(props: {}): any;
    }>();

    const props = withDefaults(defineProps<Props>(), {
        topPlants: () => [],
        trendyPlants: () => [],
        heroPlants: () => [],
        comments: () => [],
        features: () => [],
    });

    const page = usePage<SharedData>();
    const cartIds = computed(() => page.props.cartIds || []);

    const processingId = ref<number | null>(null);

    const handleAddToCart = (product: Product) => {
        router.post(
            route('cart.add'),
            { id: product.id, quantity: 1 },
            {
                preserveScroll: true,
                onBefore: () => {
                    processingId.value = product.id;
                },
                onFinish: () => {
                    processingId.value = null;
                },
            },
        );
    };
</script>

<template>
    <Head title="Main" />

    <MainLayout :full="true">
        <template #hero>
            <Hero :heroPlants="heroPlants" :comment="comments[0] || null" />
        </template>
        <template #trendy>
            <Trendy
                v-if="trendyPlants.length"
                :trendyPlants="trendyPlants"
                :cart-ids="cartIds"
                :processing-id="processingId"
                @add-to-cart="handleAddToCart"
            />
        </template>

        <Top
            v-if="topPlants.length"
            :topPlants="topPlants"
            :cart-ids="cartIds"
            :processing-id="processingId"
            @add-to-cart="handleAddToCart"
        />
        <Review v-if="comments.length" :comments="comments" />
        <Best :features="features" />
    </MainLayout>
</template>
