<script setup lang="ts">
    import { computed, onMounted, onUnmounted, ref } from 'vue';

    const props = defineProps<{ modelValue: string }>();
    const emit = defineEmits(['update:modelValue']);

    const isOpen = ref(false);
    const options = [
        { value: 'newest', label: 'Newest First' },
        { value: 'highest', label: 'Highest Rated' },
        { value: 'lowest', label: 'Lowest Rated' },
    ];

    const selectedLabel = computed(
        () => options.find((o) => o.value === props.modelValue)?.label || 'Sort by',
    );

    const selectOption = (val: string) => {
        emit('update:modelValue', val);
        isOpen.value = false;
    };

    // Закрытие при клике вне дропдауна
    const close = (e: MouseEvent) => {
        if (!(e.target as HTMLElement).closest('.custom-select')) isOpen.value = false;
    };
    onMounted(() => window.addEventListener('click', close));
    onUnmounted(() => window.removeEventListener('click', close));
</script>

<template>
    <div class="custom-select relative min-w-[200px]">
        <button
            @click.stop="isOpen = !isOpen"
            type="button"
            class="flex w-full items-center justify-between rounded-xl border border-zinc-700 bg-zinc-800 px-4 py-2.5 text-sm text-zinc-200 transition-all hover:border-zinc-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
        >
            <span>{{ selectedLabel }}</span>
            <svg
                class="ml-2 h-4 w-4 text-zinc-500 transition-transform duration-300"
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
                class="absolute z-50 mt-2 w-full overflow-hidden rounded-xl border border-zinc-700 bg-zinc-800 shadow-2xl"
            >
                <div
                    v-for="opt in options"
                    :key="opt.value"
                    @click="selectOption(opt.value)"
                    class="cursor-pointer px-4 py-3 text-sm text-zinc-300 transition-colors hover:bg-emerald-600 hover:text-white"
                    :class="{ 'bg-zinc-700/50 text-emerald-400': modelValue === opt.value }"
                >
                    {{ opt.label }}
                </div>
            </div>
        </transition>
    </div>
</template>
