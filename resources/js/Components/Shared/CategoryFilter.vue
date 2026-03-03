<script setup lang="ts">
    import { computed, ref } from 'vue';

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

    const isMobileMenuOpen = ref(false);

    const activeCategoryName = computed(() => {
        if (!currentCategory) return 'All Plants';
        const cat = categories.find((c) => c.slug === currentCategory);
        return cat ? cat.title : 'Categories';
    });

    const toggleMenu = () => {
        isMobileMenuOpen.value = !isMobileMenuOpen.value;
    };
</script>

<template>
    <div class="shop-sidebar w-full xl:w-64">
        <div class="mb-2 xl:mb-6">
            <h2 class="hidden xl:block text-xs uppercase tracking-widest text-zinc-500 font-bold">
                Categories
            </h2>

            <button
                @click="isMobileMenuOpen = !isMobileMenuOpen"
                class="flex xl:hidden items-center justify-between w-full px-5 py-4 bg-zinc-900/50 border border-zinc-800 rounded-2xl text-zinc-200 hover:border-emerald-500/30 transition-colors"
            >
                <div class="flex flex-col items-start">
                    <span class="text-[10px] uppercase tracking-tighter text-zinc-500"
                        >Selected Category</span
                    >
                    <span class="text-sm font-bold uppercase tracking-wider text-emerald-500">
                        {{ activeCategoryName }}
                    </span>
                </div>
                <svg
                    class="w-5 h-5 transition-transform duration-500 ease-in-out"
                    :class="{ 'rotate-180 text-emerald-500': isMobileMenuOpen }"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M19 9l-7 7-7-7"
                    />
                </svg>
            </button>
        </div>

        <nav
            class="flex flex-col gap-y-1.5 transition-all duration-500 ease-in-out overflow-hidden"
            :class="[
                isMobileMenuOpen
                    ? 'max-h-[1000px] opacity-100 mt-4'
                    : 'max-h-0 opacity-0 xl:max-h-none xl:opacity-100',
            ]"
        >
            <Link
                :href="route('shop')"
                :preserve-scroll="true"
                @click="isMobileMenuOpen = false"
                :class="[
                    'group flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 border border-transparent',
                    !currentCategory
                        ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/40 font-semibold'
                        : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200',
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
                @click="isMobileMenuOpen = false"
                :class="[
                    'group flex items-center px-4 py-3.5 rounded-xl transition-all duration-300 border border-transparent',
                    currentCategory === category.slug
                        ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-900/40 font-semibold'
                        : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200',
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
