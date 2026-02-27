<script setup>
    import { usePage } from '@inertiajs/vue3';

    import Footer from '@/Components/Footer.vue';
    import Header from '@/Components/Header.vue';
    import Toast from '@/Components/Toast.vue';

    defineProps({ full: { type: Boolean, default: false } });

    const page = usePage();
</script>

<template>
    <Toast />
    <div class="app-wrapper">
        <div class="main-bg" v-if="full">
            <Header :cartCount="page.props.cart_count" />
            <slot name="hero" />
            <slot name="trendy" />
        </div>
        <div v-else>
            <Header :cartCount="page.props.cart_count" />
        </div>

        <main class="content">
            <slot />
        </main>

        <Footer />
    </div>
</template>
