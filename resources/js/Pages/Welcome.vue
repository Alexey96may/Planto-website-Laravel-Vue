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
        type: String,
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
});

onMounted(() => {
    console.log(props.products);
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Main" />

    <Top />
    <Review />
    <Best />
</template>
