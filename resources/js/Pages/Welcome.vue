<script setup>
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Top from "@/Components/Top.vue";
import Review from "@/Components/Review.vue";
import Best from "@/Components/Best.vue";
import { onMounted } from "vue";

defineOptions({
    layout: MainLayout,
});

const props = defineProps({
    canLogin: {
        type: Boolean,
    },
    products: {
        type: Array,
        default: () => [],
    },
    topPlants: {
        type: Array,
        default: () => [],
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    storeName: {
        type: String,
        required: true,
    },
    status: {
        type: String,
        required: true,
    },
    comments: {
        type: Array,
        default: () => [],
    },
    features: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <Head title="Main" />

    <Top :topPlants="topPlants" />
    <Review v-if="comments.length" :comments="comments" />
    <Best :features="features" />
</template>
