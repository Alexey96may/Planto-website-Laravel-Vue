<script setup lang="ts">
    import { computed, onMounted, onUnmounted, ref } from 'vue';

    import { Link } from '@inertiajs/vue3';

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

    const menuId = 'category-navigation-menu';

    const isDesktop = ref(true);

    const updateWidth = () => {
        isDesktop.value = window.innerWidth >= 1280;
    };

    onMounted(() => {
        updateWidth();
        window.addEventListener('resize', updateWidth);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', updateWidth);
    });
</script>

<template>
    <div class="shop-sidebar w-full xl:w-64">
        <div class="mb-2 xl:mb-6">
            <h2
                id="categories-label"
                class="hidden text-xs font-bold uppercase tracking-widest text-zinc-500 xl:block"
            >
                Categories
            </h2>

            <button
                @click="toggleMenu"
                type="button"
                :aria-expanded="isMobileMenuOpen"
                :aria-controls="menuId"
                class="flex w-full items-center justify-between rounded-2xl border border-zinc-800 bg-zinc-900/50 px-5 py-4 text-zinc-200 transition-colors hover:border-emerald-500/30 xl:hidden"
            >
                <div class="flex flex-col items-start text-left">
                    <span class="text-[10px] uppercase tracking-tighter text-zinc-500"
                        >Selected Category</span
                    >
                    <span class="text-sm font-bold uppercase tracking-wider text-emerald-500">
                        {{ activeCategoryName }}
                    </span>
                </div>
                <svg
                    class="h-5 w-5 transition-transform duration-500 ease-in-out"
                    :class="{ 'rotate-180 text-emerald-500': isMobileMenuOpen }"
                    aria-hidden="true"
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
            :id="menuId"
            aria-labelledby="categories-label"
            class="flex flex-col gap-y-1.5 transition-all duration-500 ease-in-out"
            :class="[
                isMobileMenuOpen
                    ? 'mt-4 max-h-[1000px] opacity-100'
                    : 'max-h-0 overflow-hidden opacity-0 xl:max-h-none xl:opacity-100',
            ]"
        >
            <button
                type="button"
                @click="
                    $emit('change-category', null);
                    isMobileMenuOpen = false;
                "
                :class="[
                    'group flex w-full items-center rounded-xl border border-transparent px-4 py-3.5 transition-all duration-300',
                    !currentCategory ? 'bg-emerald-600 font-semibold text-white' : 'text-zinc-400',
                ]"
            >
                <span>All Plants</span>
            </button>

            <button
                v-for="category in categories"
                :key="category.id"
                type="button"
                @click="
                    $emit('change-category', category.slug);
                    isMobileMenuOpen = false;
                "
                :class="[
                    'group flex w-full items-center rounded-xl border border-transparent px-4 py-3.5 transition-all duration-300',
                    currentCategory === category.slug
                        ? 'bg-emerald-600 font-semibold text-white shadow-lg shadow-emerald-900/40'
                        : 'text-zinc-400 hover:bg-zinc-800 hover:text-zinc-200',
                ]"
            >
                <span class="text-sm">{{ category.title }}</span>
                <div
                    v-if="currentCategory === category.slug"
                    class="ml-auto h-1.5 w-1.5 rounded-full bg-white"
                ></div>
            </button>
        </nav>
    </div>
</template>
