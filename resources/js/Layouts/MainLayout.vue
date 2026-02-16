<script setup>
import Header from "@/Components/Header.vue";
import Hero from "@/Components/Hero.vue";
import Trendy from "@/Components/Trendy.vue";
import Footer from "@/Components/Footer.vue";
</script>

<template>
    <div class="app-wrapper">
        <div class="main-bg">
            <Header />
            <Hero />
            <Trendy />
        </div>

        <main class="content">
            <slot />
        </main>

        <Footer />
    </div>
</template>
