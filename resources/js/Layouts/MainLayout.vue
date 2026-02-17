<script setup>
import { usePage } from "@inertiajs/vue3";
import Header from "@/Components/Header.vue";
import Hero from "@/Components/Hero.vue";
import Trendy from "@/Components/Trendy.vue";
import Footer from "@/Components/Footer.vue";

const page = usePage();
</script>

<template>
    <div class="app-wrapper">
        <div class="main-bg">
            <Header :cartCount="page.props.cart_count" />
            <Hero />
            <Trendy />
        </div>

        <main class="content">
            <slot />
        </main>

        <Footer />
    </div>
</template>
