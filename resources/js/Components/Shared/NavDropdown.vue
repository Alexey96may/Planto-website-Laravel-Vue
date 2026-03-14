<script setup lang="ts">
    import { ref } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import { onClickOutside } from '@vueuse/core';
    import IconArrowMore from 'img/icons/arrow-more.svg?component';

    import { useSound } from '@/composables/useSound';
    import { NavigationItem } from '@/types';
    import { getHref } from '@/utils/navigation';

    const props = withDefaults(defineProps<Props>(), {
        title: 'More',
    });

    interface Props {
        items: NavigationItem[];
        title?: string;
    }

    const isOpened = ref<boolean>(false);
    const dropdownRef = ref<HTMLElement | null>(null);

    onClickOutside(dropdownRef, () => {
        isOpened.value = false;
    });

    const toggleMenu = (): void => {
        playClick();
        isOpened.value = !isOpened.value;
    };

    const { playClick } = useSound();
</script>

<template>
    <li class="relative list-none" ref="dropdownRef">
        <button
            @click="toggleMenu"
            type="button"
            aria-haspopup="true"
            :aria-expanded="isOpened"
            :class="[isOpened ? 'text-emerald-400' : 'text-green-50 hover:text-white']"
            class="flex items-center gap-1 rounded-md p-1 font-bold outline-none transition-all focus:outline-none focus:ring-0 focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2 focus-visible:ring-offset-zinc-900 active:scale-95"
        >
            <span>{{ title }}</span>
            <IconArrowMore
                class="nav__item-more inline-block transition-transform duration-300"
                :class="[
                    isOpened ? '-translate-y-1/2 rotate-180 fill-emerald-400' : 'fill-green-50',
                ]"
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
                role="menu"
                class="absolute left-0 top-full z-50 mt-2 min-w-24 overflow-hidden rounded-md border border-emerald-500/10 bg-zinc-800/80 text-zinc-200 shadow-2xl backdrop-blur-md"
            >
                <li v-for="item in items" :key="item.id" role="none">
                    <Link
                        :href="getHref(item)"
                        role="menuitem"
                        @click="isOpened = false"
                        class="block px-4 py-2.5 text-xs font-medium transition-colors hover:bg-emerald-500/10 hover:text-white"
                    >
                        {{ item.title }}
                    </Link>
                </li>
            </ul>
        </Transition>
    </li>
</template>
