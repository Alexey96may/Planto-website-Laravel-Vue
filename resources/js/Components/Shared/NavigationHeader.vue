<script setup lang="ts">
    import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconArrowMore from 'img/icons/arrow-more.svg?component';

    import NavDropdown from '@/Components/Shared/NavDropdown.vue';
    import { SharedData } from '@/types';
    import { getHref } from '@/utils/navigation';

    const page = usePage<SharedData>();
    const menuItems = computed(() => page.props.navigation?.header || []);

    const containerRef = ref<HTMLElement | null>(null);
    const ghostRef = ref<HTMLElement | null>(null);
    const visibleCount = ref(menuItems.value.length);
    const moreBtnRef = ref<HTMLElement | null>(null);

    const updateNavigation = () => {
        if (!containerRef.value || !ghostRef.value || !moreBtnRef.value) return;

        const parent = containerRef.value.parentElement;
        if (!parent) return;

        // Доступная ширина (берем 100%, если нужно 50% — поправь)
        const maxAllowedWidth = parent.offsetWidth * 0.5;

        // Получаем реальный gap из CSS (Tailwind gap-5 = 20px)
        const gap = parseInt(window.getComputedStyle(ghostRef.value).columnGap) || 0;

        // Замеряем реальную ширину кнопки "More"
        const moreButtonWidth = moreBtnRef.value.offsetWidth + gap;

        // Все элементы кроме первого (который является кнопкой More для замера)
        const ghostItems = Array.from(ghostRef.value.querySelectorAll('.nav__item--ghost')).slice(
            1,
        );

        let totalWidth = 0;
        let count = 0;

        for (let i = 0; i < ghostItems.length; i++) {
            const itemWidth = (ghostItems[i] as HTMLElement).offsetWidth + gap;
            const isLast = i === ghostItems.length - 1;

            // Если это не последний элемент, мы должны проверить,
            // влезет ли он вместе с будущей кнопкой "More"
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

        // Фикс: если мы не вместили последний элемент из-за кнопки More,
        // но кнопка More на самом деле не нужна (так как это был бы последний элемент),
        // проверяем еще раз.
        if (count < ghostItems.length) {
            const lastItemIdx = ghostItems.length - 1;
            const lastItemTotalWidth =
                totalWidth + (ghostItems[count] as HTMLElement).offsetWidth + gap;

            // Если это реально последний айтем и он влезает БЕЗ кнопки More
            if (count === lastItemIdx && lastItemTotalWidth <= maxAllowedWidth) {
                count++;
            }
        }

        visibleCount.value = count > 0 ? count : 1;
    };
    let observer: ResizeObserver;

    onMounted(() => {
        observer = new ResizeObserver(() => updateNavigation());
        if (containerRef.value) observer.observe(containerRef.value);

        nextTick(() => updateNavigation());
    });

    watch(menuItems, () => {
        nextTick(() => updateNavigation());
    });

    onUnmounted(() => observer?.disconnect());
</script>

<template>
    <nav class="nav relative flex-1" id="nav" aria-label="Navigation" ref="containerRef">
        <ul class="nav__list justify-center items-center flex gap-5" aria-label="Navigation list">
            <template v-for="item in menuItems.slice(0, visibleCount)" :key="item.id">
                <NavDropdown
                    v-if="item.children && item.children.length > 0"
                    :items="item.children"
                    :title="item.title"
                />

                <li v-else class="nav__item whitespace-nowrap">
                    <Link
                        :href="getHref(item)"
                        class="nav__link font-bold text-green-50 hover:text-white transition-colors p-1 focus:outline-none focus:ring-0 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-900 rounded-lg outline-none"
                    >
                        {{ item.title }}
                    </Link>
                </li>
            </template>

            <NavDropdown
                v-if="visibleCount < menuItems.length"
                :items="menuItems.slice(visibleCount)"
                title="More"
            />
        </ul>

        <ul
            ref="ghostRef"
            class="absolute w-full top-0 left-0 flex gap-5 invisible pointer-events-none h-0 overflow-hidden"
        >
            <li ref="moreBtnRef" class="nav__item--ghost font-bold text-green-600">
                More <IconArrowMore class="inline-block" />
            </li>

            <li
                v-for="item in menuItems"
                :key="'ghost-' + item.id"
                class="nav__item--ghost whitespace-nowrap font-bold text-green-600"
            >
                {{ item.title }}
                <IconArrowMore v-if="item.children?.length" class="inline-block" />
            </li>
        </ul>
    </nav>
</template>
