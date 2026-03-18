<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';

    import Footer from '@/Components/Sections/Footer.vue';
    import Header from '@/Components/Sections/Header.vue';
    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppToast from '@/Components/Shared/Toast.vue';
    import { SharedData } from '@/types';

    defineSlots<{
        default(props: {}): any;
        hero(props: {}): any;
        trendy(props: {}): any;
    }>();

    defineProps({ full: { type: Boolean, default: false } });

    const page = usePage<SharedData>();
</script>

<template>
    <SeoMeta />

    <Toast />
    <div class="app-wrapper">
        <div class="main-bg" v-if="full">
            <Header :cartCount="page.props.cart_count" />
            <slot name="hero" v-if="$slots.hero" />
            <slot name="trendy" v-if="$slots.trendy" />
        </div>
        <Header v-else :cartCount="page.props.cart_count" />

        <Transition name="fade" mode="out-in" appear>
            <main :key="page.url" class="content">
                <slot /></main
        ></Transition>

        <Footer />
        <AppToast />
    </div>
</template>

<style>
    .fade-enter-active,
    .fade-leave-active {
        transition: opacity 0.4s ease;
    }
    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
    }
</style>
