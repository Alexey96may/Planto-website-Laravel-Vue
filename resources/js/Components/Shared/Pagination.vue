<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import { PaginationLink } from '@/types';

    const { links = [] } = defineProps<{
        links?: PaginationLink[];
    }>();
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
                'bg-emerald-600 text-white shadow-md': link.active,
                'text-gray-300 hover:bg-gray-100/80 hover:text-gray-700': !link.active && link.url,
                'text-gray-500 cursor-default': !link.url,
            }"
            preserve-scroll
        />
    </div>
</template>
