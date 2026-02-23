<script setup>
import { usePage } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import Hero from "@/Components/Hero.vue";
import Trendy from "@/Components/Trendy.vue";
import Toast from "@/Components/Toast.vue";
import Footer from "@/Components/Footer.vue";

defineProps({ full: { type: Boolean, default: true } });

const page = usePage();
</script>

<template>
    <Toast />
    <div class="app-wrapper">
        <div class="main-bg" v-if="full">
            <Header :cartCount="page.props.cart_count" />
            <Hero />
            <Trendy
                v-if="page.props.trendyPlants?.length"
                :trendyPlants="page.props.trendyPlants"
            />
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
