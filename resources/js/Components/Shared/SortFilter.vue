<script setup lang="ts">
    import { onMounted, onUnmounted, ref } from 'vue';

    const props = defineProps<{
        modelValue: string;
    }>();

    const emit = defineEmits(['update:modelValue', 'change']);

    const isOpen = ref(false);
    const options = [
        { value: 'popular', label: 'Best Sellers' },
        { value: 'new', label: 'New Arrivals' },
        { value: 'price_asc', label: 'Price: Low to High' },
        { value: 'price_desc', label: 'Price: High to Low' },
    ];

    const selectedLabel = ref(
        options.find((o) => o.value === props.modelValue)?.label || 'Сортировка',
    );

    const selectOption = (option: { value: string; label: string }) => {
        selectedLabel.value = option.label;
        emit('update:modelValue', option.value);
        emit('change', option.value);
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
    <div class="custom-select relative inline-block text-left w-[200px]">
        <button
            @click="isOpen = !isOpen"
            type="button"
            class="flex items-center justify-between w-full px-3 py-2 text-sm font-bold text-zinc-200 bg-zinc-800/50 hover:bg-zinc-800 border border-zinc-200 rounded-xl transition-all"
        >
            <span class="truncate">{{ selectedLabel }}</span>
            <svg
                class="w-4 h-4 ml-2 transition-transform duration-300 text-emerald-500"
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
                class="absolute z-50 w-full mt-2 origin-top-right bg-zinc-900 border border-emerald-500/10 rounded-xl shadow-2xl overflow-hidden"
            >
                <div class="py-1">
                    <button
                        v-for="option in options"
                        :key="option.value"
                        @click="selectOption(option)"
                        class="block w-full px-4 py-2.5 text-sm text-left transition-colors hover:bg-emerald-500/10"
                        :class="
                            modelValue === option.value
                                ? 'text-emerald-400 bg-emerald-500/5'
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
