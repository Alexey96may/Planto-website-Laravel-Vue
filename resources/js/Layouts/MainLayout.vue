<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';

    import Footer from '@/Components/Sections/Footer.vue';
    import Header from '@/Components/Sections/Header.vue';
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
    <Toast />
    <div class="app-wrapper">
        <div class="main-bg" v-if="full">
            <Header :cartCount="page.props.cart_count" />
            <slot name="hero" />
            <slot name="trendy" />
        </div>
        <Header v-else :cartCount="page.props.cart_count" />

        <main class="content">
            <slot />
        </main>

        <Footer />
        <AppToast />
    </div>
</template>
