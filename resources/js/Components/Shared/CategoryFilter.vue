<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import MainLayout from '@/Layouts/MainLayout.vue';
    import { Category } from '@/types';

    type Props = {
        categories?: Category[];
        currentCategory?: string | null;
    };

    defineOptions({
        layout: MainLayout,
    });

    const { categories = [] as Category[], currentCategory = null } = defineProps<Props>();

    const emit = defineEmits<{
        (e: 'change-category', currentCategory: string | null): void;
    }>();
</script>

<template>
    <div class="shop-sidebar">
        <h2 class="text-xs uppercase tracking-widest text-zinc-500 font-bold mb-6">Categories</h2>

        <nav class="flex flex-col gap-y-1">
            <Link
                :href="route('shop')"
                :preserve-scroll="true"
                @click="emit('change-category', null)"
                :class="[
                    'group flex items-center px-4 py-3 rounded-xl transition-all duration-300 border border-transparent',
                    !currentCategory
                        ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20 font-semibold'
                        : 'text-zinc-400 hover:bg-emerald-500/10 hover:text-emerald-500 hover:border-emerald-500/20',
                ]"
            >
                <span class="text-sm">All Plants</span>
                <div
                    v-if="!currentCategory"
                    class="ml-auto w-1.5 h-1.5 rounded-full bg-white animate-pulse"
                ></div>
            </Link>

            <Link
                v-for="category in categories"
                :key="category.id"
                :href="route('shop', { category: category.slug })"
                :preserve-scroll="true"
                @click="emit('change-category', category.slug)"
                :class="[
                    'group flex items-center px-4 py-3 rounded-xl transition-all duration-300 border border-transparent',
                    currentCategory === category.slug
                        ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/20 font-semibold'
                        : 'text-zinc-400 hover:bg-emerald-500/10 hover:text-emerald-500 hover:border-emerald-500/20',
                ]"
            >
                <span class="text-sm">{{ category.title }}</span>

                <div
                    v-if="currentCategory === category.slug"
                    class="ml-auto w-1.5 h-1.5 rounded-full bg-white"
                ></div>
            </Link>
        </nav>
    </div>
</template>
