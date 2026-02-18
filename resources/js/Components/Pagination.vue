<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    links: Array,
});
</script>

<template>
    <div v-if="links.length > 3" class="flex justify-center mt-12 space-x-1">
        <Link
            v-for="(link, k) in links"
            :key="k"
            :href="link.url || '#'"
            v-html="link.label"
            class="px-4 py-2 rounded-lg text-sm transition-all"
            :class="{
                'bg-green-600 text-white shadow-md': link.active,
                'text-gray-500 hover:bg-gray-100': !link.active && link.url,
                'text-gray-300 cursor-not-allowed': !link.url,
            }"
            preserve-scroll
        />
    </div>
</template>
