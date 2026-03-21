<script setup lang="ts">
    import { onMounted, onUnmounted, ref } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import { onClickOutside } from '@vueuse/core';
    import IconArrowMore from 'img/icons/arrow-more.svg?component';

    import { useSound } from '@/composables/useSound';
    import { NavigationItem } from '@/types';
    import { getHref } from '@/utils/navigation';

    interface Props {
        items: NavigationItem[];
        title?: string;
    }

    const props = withDefaults(defineProps<Props>(), {
        title: 'More',
    });

    const isOpened = ref<boolean>(false);
    const dropdownRef = ref<HTMLElement | null>(null);
    const { playClick } = useSound();

    onClickOutside(dropdownRef, () => {
        isOpened.value = false;
    });

    const handleKeydown = (e: KeyboardEvent) => {
        if (e.key === 'Escape' && isOpened.value) {
            isOpened.value = false;
        }
    };

    onMounted(() => window.addEventListener('keydown', handleKeydown));
    onUnmounted(() => window.removeEventListener('keydown', handleKeydown));

    const toggleMenu = (): void => {
        playClick();
        isOpened.value = !isOpened.value;
    };
</script>

<template>
    <li class="relative list-none" ref="dropdownRef">
        <button
            @click="toggleMenu"
            type="button"
            aria-haspopup="true"
            :aria-expanded="isOpened"
            aria-controls="dropdown-menu"
            :class="[isOpened ? 'text-emerald-400' : 'text-green-50 hover:text-white']"
            class="flex items-center gap-1 rounded-md p-1 font-bold outline-none transition-all focus:outline-none focus:ring-0 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-900 active:scale-95"
        >
            <span>{{ title }}</span>
            <IconArrowMore
                class="nav__item-more inline-block transition-transform duration-300"
                :class="[isOpened ? 'rotate-180 fill-emerald-400' : 'fill-green-50']"
                aria-hidden="true"
            />
        </button>

        <Transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform scale-95 opacity-0 -translate-y-2"
            enter-to-class="transform scale-100 opacity-100 translate-y-0"
            leave-active-class="transition duration-150 ease-in"
            leave-from-class="transform scale-100 opacity-100 translate-y-0"
            leave-to-class="transform scale-95 opacity-0 -translate-y-2"
        >
            <ul
                v-if="isOpened"
                id="dropdown-menu"
                role="menu"
                class="absolute left-0 top-full z-50 mt-2 min-w-[140px] overflow-hidden rounded-xl border border-emerald-500/10 bg-zinc-900/90 text-zinc-200 shadow-2xl backdrop-blur-xl"
            >
                <li v-for="item in items" :key="item.id" role="none">
                    <Link
                        :href="getHref(item)"
                        role="menuitem"
                        @click="isOpened = false"
                        class="block px-4 py-3 text-xs font-medium tracking-wide transition-all hover:bg-emerald-500/10 hover:text-emerald-400 focus:bg-emerald-500/10 focus:text-emerald-400 focus:outline-none"
                    >
                        {{ item.title }}
                    </Link>
                </li>
            </ul>
        </Transition>
    </li>
</template>

<style scoped>
    .nav__item-more {
        backface-visibility: hidden;
    }
</style>
