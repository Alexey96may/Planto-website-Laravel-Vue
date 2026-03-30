<script setup lang="ts">
    import { computed, nextTick, onMounted, onUnmounted, ref, watch } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconArrowMore from 'img/icons/arrow-more.svg?component';

    import MobileMenu from '@/Components/Shared/MobileMenu.vue';
    import NavDropdown from '@/Components/Shared/NavDropdown.vue';
    import AppVisualEffectsToggle from '@/Components/UI/AppVisualEffectsToggle.vue';
    import { SharedData } from '@/types';
    import { getHref } from '@/utils/navigation';

    const page = usePage<SharedData>();
    const menuItems = computed(() => page.props.navigation?.header || []);

    const containerRef = ref<HTMLElement | null>(null);
    const ghostRef = ref<HTMLElement | null>(null);
    const visibleCount = ref(menuItems.value.length);
    const moreBtnRef = ref<HTMLElement | null>(null);

    const updateNavigation = () => {
        if (
            typeof window === 'undefined' ||
            !containerRef.value ||
            !ghostRef.value ||
            !moreBtnRef.value
        )
            return;

        const parent = containerRef.value.parentElement;
        if (!parent) return;

        const maxAllowedWidth = parent.offsetWidth * 0.55;
        const styles = window.getComputedStyle(ghostRef.value);
        const gap = parseInt(styles.columnGap) || 20;

        const moreButtonWidth = moreBtnRef.value.offsetWidth + gap;
        const ghostItems = Array.from(ghostRef.value.querySelectorAll('.nav__item--ghost')).slice(
            1,
        );

        let totalWidth = 0;
        let count = 0;

        for (let i = 0; i < ghostItems.length; i++) {
            const itemWidth = (ghostItems[i] as HTMLElement).offsetWidth + gap;
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

        if (count === ghostItems.length) {
            visibleCount.value = count;
        } else {
            visibleCount.value = count > 0 ? count : 1;
        }
    };

    let observer: ResizeObserver;

    onMounted(() => {
        if (typeof window !== 'undefined') {
            observer = new ResizeObserver(() => {
                window.requestAnimationFrame(() => updateNavigation());
            });

            if (containerRef.value?.parentElement) {
                observer.observe(containerRef.value.parentElement);
            }

            nextTick(() => updateNavigation());
        }
    });

    watch(menuItems, () => {
        nextTick(() => updateNavigation());
    });

    onUnmounted(() => observer?.disconnect());
</script>

<template>
    <nav
        class="nav relative order-1 flex items-center justify-between gap-5 lg:order-none lg:w-full"
        id="nav"
        aria-label="Main Navigation"
        ref="containerRef"
    >
        <ul
            class="nav__list hidden flex-1 items-center justify-center gap-5 lg:flex"
            role="menubar"
        >
            <template v-for="(item, index) in menuItems" :key="item.id">
                <template v-if="index < visibleCount">
                    <NavDropdown
                        v-if="item.children && item.children.length > 0"
                        :items="item.children"
                        :title="item.title"
                    />

                    <li v-else class="nav__item whitespace-nowrap" role="none">
                        <Link
                            :href="getHref(item)"
                            role="menuitem"
                            class="nav__link rounded-lg p-1 font-bold text-green-50 outline-none transition-colors hover:text-white focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-900"
                        >
                            {{ item.title }}
                        </Link>
                    </li>
                </template>
            </template>

            <NavDropdown
                v-if="visibleCount < menuItems.length"
                :items="menuItems.slice(visibleCount)"
                title="More"
            />
        </ul>

        <div class="leading-none lg:hidden">
            <MobileMenu>
                <div class="mobile-nav-wrapper flex flex-col gap-6 pb-6 sm:gap-8">
                    <template v-for="item in menuItems" :key="'mobile-' + item.id">
                        <div v-if="item.children?.length" class="flex flex-col gap-3 sm:gap-4">
                            <span
                                class="text-md ml-1 text-[0.65rem] font-black uppercase tracking-[0.2em] text-emerald-500/60"
                            >
                                {{ item.title }}
                            </span>

                            <div class="ml-1 flex flex-col gap-1 border-l border-zinc-700/50">
                                <Link
                                    v-for="child in item.children"
                                    :key="child.id"
                                    :href="getHref(child)"
                                    class="group relative py-2 pl-5 text-base text-sm text-zinc-300 transition-all hover:text-emerald-400 active:bg-emerald-500/5"
                                >
                                    <span
                                        class="absolute left-[-1px] top-0 h-full w-[2px] scale-y-0 bg-emerald-500 transition-transform duration-300 group-hover:scale-y-100"
                                    ></span>
                                    {{ child.title }}
                                </Link>
                            </div>
                        </div>

                        <Link
                            v-else
                            :href="getHref(item)"
                            class="text-md font-bold text-zinc-100 transition-transform hover:text-emerald-400 active:translate-x-1"
                        >
                            {{ item.title }}
                        </Link>
                    </template>

                    <div class="mt-4 border-t border-zinc-400 pt-8">
                        <Link
                            :href="$page.props.auth?.user ? route('dashboard') : route('register')"
                            :aria-label="$page.props.auth?.user ? 'To Dashboard' : 'To Register'"
                            class="!block text-sm font-medium text-zinc-400 sm:!hidden"
                        >
                            User Dashboard
                        </Link>
                        <AppVisualEffectsToggle />
                    </div>
                </div>
            </MobileMenu>
        </div>

        <ul
            ref="ghostRef"
            class="pointer-events-none invisible absolute left-0 top-0 flex h-0 w-full gap-5 overflow-hidden"
            aria-hidden="true"
        >
            <li ref="moreBtnRef" class="nav__item--ghost font-bold">
                More <IconArrowMore class="inline-block" />
            </li>
            <li
                v-for="item in menuItems"
                :key="'ghost-' + item.id"
                class="nav__item--ghost whitespace-nowrap font-bold"
            >
                {{ item.title }}
                <IconArrowMore v-if="item.children?.length" class="inline-block" />
            </li>
        </ul>
    </nav>
</template>
