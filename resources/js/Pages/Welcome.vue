<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Head, router, usePage } from '@inertiajs/vue3';

    import Best from '@/Components/Sections/BestSection.vue';
    import Hero from '@/Components/Sections/HeroSection.vue';
    import Review from '@/Components/Sections/ReviewSection.vue';
    import Top from '@/Components/Sections/TopSection.vue';
    import Trendy from '@/Components/Sections/TrendySection.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useScrollReveal } from '@/composables/useScrollReveal';
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
    const cartIds = computed(() => page.props.cart_ids || []);

    const processingId = ref<number | null>(null);

    const handleAddToCart = (product: Product) => {
        router.post(
            route('cart.add'),
            { product_id: product.id, quantity: 1 },
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

    useScrollReveal({
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px',
    });
</script>

<template>
    <Head title="Main" />

    <MainLayout :full="true">
        <template #hero>
            <Hero class="reveal" :heroPlants="heroPlants" :comment="comments[0] || null" />
        </template>
        <template #trendy>
            <Trendy
                class="reveal"
                v-if="trendyPlants.length"
                :trendyPlants="trendyPlants"
                :cart-ids="cartIds"
                :processing-id="processingId"
                @add-to-cart="handleAddToCart"
            />
        </template>

        <Top
            class="reveal"
            v-if="topPlants.length"
            :topPlants="topPlants"
            :cart-ids="cartIds"
            :processing-id="processingId"
            @add-to-cart="handleAddToCart"
        />
        <Review v-if="comments.length" :comments="comments" class="reveal" />
        <Best :features="features" class="reveal" />
    </MainLayout>
</template>

<style>
    .reveal {
        opacity: 0;
        transform: translateY(30px) scale(0.98);
        transition:
            opacity 0.8s cubic-bezier(0.21, 0.45, 0.32, 0.9),
            transform 0.8s cubic-bezier(0.21, 0.45, 0.32, 0.9);
        will-change: transform, opacity;
    }

    .reveal-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    @media (prefers-reduced-motion: reduce) {
        .reveal {
            transition: none !important;
            opacity: 1 !important;
            transform: none !important;
        }
    }
</style>
