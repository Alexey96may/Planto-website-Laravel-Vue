<script setup lang="ts">
    import { computed, nextTick, onMounted, onUnmounted, ref } from 'vue';

    import { useSound } from '@/composables/useSound';

    const props = defineProps<{
        modelValue: string;
    }>();

    const emit = defineEmits(['update:modelValue', 'change']);
    const { playClick } = useSound();

    const isOpen = ref(false);
    const focusedIndex = ref(-1);
    const options = [
        { value: 'popular', label: 'Best Sellers' },
        { value: 'new', label: 'New Arrivals' },
        { value: 'price_asc', label: 'Price: Low to High' },
        { value: 'price_desc', label: 'Price: High to Low' },
    ];

    const selectedLabel = computed(() => {
        return options.find((o) => o.value === props.modelValue)?.label || 'Sorting';
    });

    const toggleDropdown = () => {
        isOpen.value = !isOpen.value;
        if (isOpen.value) {
            focusedIndex.value = options.findIndex((o) => o.value === props.modelValue);
        }
    };

    const selectOption = (index: number) => {
        const option = options[index];
        emit('update:modelValue', option.value);
        emit('change', option.value);
        playClick();
        isOpen.value = false;
    };

    const onKeyDown = (e: KeyboardEvent) => {
        if (!isOpen.value) {
            if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
                toggleDropdown();
            }
            return;
        }

        switch (e.key) {
            case 'ArrowDown':
                focusedIndex.value = (focusedIndex.value + 1) % options.length;
                break;
            case 'ArrowUp':
                focusedIndex.value = (focusedIndex.value - 1 + options.length) % options.length;
                break;
            case 'Enter':
            case ' ':
                e.preventDefault();
                selectOption(focusedIndex.value);
                break;
            case 'Escape':
            case 'Tab':
                isOpen.value = false;
                break;
        }
    };

    const closeDropdown = (e: MouseEvent) => {
        if (!(e.target as HTMLElement).closest('.custom-select')) {
            isOpen.value = false;
        }
    };

    onMounted(() => window.addEventListener('click', closeDropdown));
    onUnmounted(() => window.removeEventListener('click', closeDropdown));
</script>

<template>
    <div class="custom-select relative inline-block w-[220px] text-left" @keydown="onKeyDown">
        <button
            @click="toggleDropdown"
            @mousedown="playClick"
            type="button"
            aria-haspopup="listbox"
            :aria-expanded="isOpen"
            class="flex w-full items-center justify-between rounded-xl border border-white/10 bg-zinc-800/50 px-3 py-2.5 text-sm font-bold text-zinc-200 transition-all hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-[#c5d86d]/50"
        >
            <span class="truncate">{{ selectedLabel }}</span>
            <svg
                class="ml-2 h-4 w-4 text-emerald-500 transition-transform duration-300"
                :class="{ 'rotate-180': isOpen }"
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

        <transition
            enter-active-class="transition duration-100 ease-out"
            enter-from-class="transform scale-95 opacity-0"
            enter-to-class="transform scale-100 opacity-100"
            leave-active-class="transition duration-75 ease-in"
            leave-from-class="transform scale-100 opacity-100"
            leave-to-class="transform scale-95 opacity-0"
        >
            <div
                v-if="isOpen"
                role="listbox"
                class="absolute z-50 mt-2 w-full overflow-hidden rounded-xl border border-white/10 bg-zinc-900 shadow-2xl focus:outline-none"
            >
                <div class="py-1">
                    <div
                        v-for="(option, index) in options"
                        :key="option.value"
                        role="option"
                        :aria-selected="modelValue === option.value"
                        @click="selectOption(index)"
                        @mouseenter="focusedIndex = index"
                        class="block w-full cursor-pointer px-4 py-2.5 text-left text-sm transition-colors"
                        :class="[
                            modelValue === option.value ? 'text-emerald-400' : 'text-zinc-400',
                            focusedIndex === index ? 'bg-emerald-500/10 text-white' : '',
                        ]"
                    >
                        {{ option.label }}
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>
