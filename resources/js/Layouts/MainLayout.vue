<script setup>
import Header from "@/Components/Header.vue";
</script>

<template>
    <div class="app-wrapper">
        <Header />
        <main class="content">
            <slot />
        </main>
    </div>
</template>
