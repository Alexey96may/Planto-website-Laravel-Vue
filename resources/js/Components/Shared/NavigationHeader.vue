<script setup lang="ts">
    import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconArrowMore from 'img/icons/arrow-more.svg?component';

    import { SharedData } from '@/types';
    import { getHref } from '@/utils/navigation';

    const page = usePage<SharedData>();
    const menuItems = computed(() => page.props.navigation?.header || []);

    const containerRef = ref<HTMLElement | null>(null);
    const ghostRef = ref<HTMLElement | null>(null); // Реф для скрытого списка
    const visibleCount = ref(menuItems.value.length);

    const updateNavigation = () => {
        if (!containerRef.value || !ghostRef.value) return;

        // Ищем родителя (например, <header> или <div> в котором лежит nav)
        const parent = containerRef.value.parentElement;
        if (!parent) return;

        // Берем 50% от РОДИТЕЛЯ. Эта величина стабильна и не схлопнется.
        const maxAllowedWidth = parent.offsetWidth * 0.5;

        const ghostItems = ghostRef.value.querySelectorAll('.nav__item--ghost');
        const moreButtonWidth = 100; // Немного увеличим запас для надежности

        let totalWidth = 0;
        let count = 0;

        for (let i = 0; i < ghostItems.length; i++) {
            const itemWidth = (ghostItems[i] as HTMLElement).offsetWidth + 20; // gap 20

            const isLast = i === ghostItems.length - 1;
            const neededWidth = isLast
                ? totalWidth + itemWidth
                : totalWidth + itemWidth + moreButtonWidth;

            if (neededWidth <= maxAllowedWidth) {
                totalWidth += itemWidth;
                count++;
            } else {
                break;
            }
        }

        // Финальная проверка: если влезли все, но мы считали с учетом кнопки More
        if (count < ghostItems.length) {
            // Проверяем, может ли влезть на один больше, если это последний (без кнопки More)
            const nextIdx = count;
            const lastItemWidth = (ghostItems[nextIdx] as HTMLElement).offsetWidth + 20;
            if (
                nextIdx === ghostItems.length - 1 &&
                totalWidth + lastItemWidth <= maxAllowedWidth
            ) {
                count++;
            }
        }

        visibleCount.value = count > 0 ? count : 1;
    };
    let observer: ResizeObserver;

    onMounted(() => {
        observer = new ResizeObserver(() => updateNavigation());
        if (containerRef.value) observer.observe(containerRef.value);

        // Первый замер после отрисовки
        nextTick(() => updateNavigation());
    });

    // Следим за обновлением данных с сервера
    watch(menuItems, () => {
        nextTick(() => updateNavigation());
    });

    onUnmounted(() => observer?.disconnect());
</script>

<template>
    <nav class="nav relative flex-1" id="nav" aria-label="Navigation" ref="containerRef">
        <ul class="nav__list justify-center items-center" aria-label="Navigation list">
            <li
                class="nav__item min-w-0 nav__item--plus whitespace-nowrap text-nowrap text-ellipsis cursor-pointer py-1"
                v-for="item in menuItems.slice(0, visibleCount)"
                :key="item.id"
            >
                <div v-if="item.children && item.children.length > 0" class="relative group">
                    <span aria-label="To plants type"
                        >{{ item.title }}

                        <IconArrowMore
                            class="nav__item-more inline-block"
                            id="moreArrow"
                            aria-label="Arrow sprite"
                        />
                    </span>

                    <ul
                        class="absolute overflow-hidden left-0 top-full w-48 text-zinc-200 bg-zinc-800/70 border border-emerald-500/10 shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50"
                    >
                        <li v-for="child in item.children" :key="child.id">
                            <Link
                                :href="getHref(item) || '#'"
                                class="block text-xs px-4 py-2 hover:bg-emerald-500/10"
                            >
                                {{ child.title }}
                            </Link>
                        </li>
                    </ul>
                </div>

                <Link
                    :href="getHref(item) || '#'"
                    class="nav__link"
                    aria-label="To see more plants"
                    v-else
                >
                    {{ item.title }}
                </Link>
            </li>
            <li v-if="visibleCount < menuItems.length" class="relative group">
                <button class="flex items-center gap-1 font-bold text-green-600">
                    More
                    <IconArrowMore
                        class="nav__item-more inline-block"
                        id="moreArrow"
                        aria-label="Arrow sprite"
                    />
                </button>

                <ul
                    class="absolute overflow-hidden left-0 top-full w-48 text-zinc-200 bg-zinc-800/70 border border-emerald-500/10 shadow-xl rounded-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50"
                >
                    <li v-for="item in menuItems.slice(visibleCount)" :key="item.id">
                        <Link
                            :href="getHref(item)"
                            class="block text-xs px-4 py-2 hover:bg-emerald-500/10"
                        >
                            {{ item.title }}
                        </Link>
                    </li>
                </ul>
            </li>
        </ul>

        <ul
            ref="ghostRef"
            class="absolute w-full top-0 left-0 flex gap-5 invisible pointer-events-none h-0 overflow-hidden"
        >
            <li
                v-for="item in menuItems"
                :key="'ghost-' + item.id"
                class="nav__item--ghost whitespace-nowrap"
            >
                {{ item.title }} <IconArrowMore v-if="item.children?.length" />
            </li>
        </ul>
    </nav>
</template>
