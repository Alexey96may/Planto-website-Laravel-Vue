<script setup lang="ts">
    import { computed, onMounted, onUnmounted, ref } from 'vue';

    import { useSound } from '@/composables/useSound';

    const props = defineProps<{
        modelValue: string;
    }>();

    const emit = defineEmits(['update:modelValue', 'change']);

    const { playClick } = useSound();

    const isOpen = ref(false);
    const options = [
        { value: 'popular', label: 'Best Sellers' },
        { value: 'new', label: 'New Arrivals' },
        { value: 'price_asc', label: 'Price: Low to High' },
        { value: 'price_desc', label: 'Price: High to Low' },
    ];

    const selectedLabel = computed(() => {
        return options.find((o) => o.value === props.modelValue)?.label || 'Sorting';
    });

    const selectOption = (option: { value: string; label: string }) => {
        emit('update:modelValue', option.value);
        emit('change', option.value);
        playClick();
        isOpen.value = false;
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
    <div class="custom-select relative inline-block w-[200px] text-left">
        <button
            @click="isOpen = !isOpen"
            @mousedown="playClick"
            type="button"
            class="flex w-full items-center justify-between rounded-xl border border-zinc-200 bg-zinc-800/50 px-3 py-2 text-sm font-bold text-zinc-200 transition-all hover:bg-zinc-800"
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
                class="absolute z-50 mt-2 w-full origin-top-right overflow-hidden rounded-xl border border-emerald-500/10 bg-zinc-900 shadow-2xl"
            >
                <div class="py-1">
                    <button
                        v-for="option in options"
                        :key="option.value"
                        @click="selectOption(option)"
                        class="block w-full px-4 py-2.5 text-left text-sm transition-colors hover:bg-emerald-500/10"
                        :class="
                            modelValue === option.value
                                ? 'bg-emerald-500/5 text-emerald-400'
                                : 'text-zinc-400 hover:text-white'
                        "
                    >
                        {{ option.label }}
                    </button>
                </div>
            </div>
        </transition>
    </div>
</template>
