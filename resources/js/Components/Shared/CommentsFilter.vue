<script setup lang="ts">
    import { computed, onMounted, onUnmounted, ref } from 'vue';

    import { useSound } from '@/composables/useSound';

    const props = defineProps<{ modelValue: string }>();
    const emit = defineEmits(['update:modelValue']);

    const isOpen = ref(false);
    const options = [
        { value: 'newest', label: 'Newest' },
        { value: 'highest', label: 'High rating' },
        { value: 'lowest', label: 'Low rating' },
    ];

    const selectedLabel = computed(
        () => options.find((o) => o.value === props.modelValue)?.label || 'Sorting',
    );

    const selectOption = (val: string) => {
        emit('update:modelValue', val);
        playClick();
        isOpen.value = false;
    };

    const close = (e: MouseEvent) => {
        if (!(e.target as HTMLElement).closest('.custom-select')) isOpen.value = false;
    };
    onMounted(() => window.addEventListener('click', close));
    onUnmounted(() => window.removeEventListener('click', close));

    const { playClick } = useSound();
</script>

<template>
    <div class="custom-select relative min-w-[220px]">
        <button
            @click.stop="isOpen = !isOpen"
            type="button"
            @mousedown="playClick"
            class="group flex w-full items-center justify-between rounded-xl border border-emerald-400/30 bg-plant-shop/80 px-6 py-3 text-xs font-black uppercase tracking-widest text-white backdrop-blur-md transition-all duration-200 hover:border-emerald-400/60 hover:bg-plant-shop/100 focus:outline-none lg:w-full lg:rounded-full"
        >
            <span class="truncate">{{ selectedLabel }}</span>
            <svg
                class="ml-3 h-4 w-4 text-emerald-400 transition-transform duration-500 ease-out"
                :class="{ 'rotate-180': isOpen }"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2.5"
                    d="M19 9l-7 7-7-7"
                />
            </svg>
        </button>

        <transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform -translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isOpen"
                class="absolute right-0 z-[60] mt-3 w-full overflow-hidden rounded-2xl border border-white/10 bg-[#0a2d1d]/90 p-1 shadow-[0_20px_50px_rgba(0,0,0,0.5)] backdrop-blur-xl"
            >
                <div
                    v-for="opt in options"
                    :key="opt.value"
                    @click="selectOption(opt.value)"
                    class="relative cursor-pointer rounded-xl px-5 py-3 text-[11px] font-bold uppercase tracking-wider text-zinc-300 transition-all hover:bg-emerald-800/50 hover:text-white"
                    :class="{ 'bg-white/5 text-emerald-400': modelValue === opt.value }"
                >
                    {{ opt.label }}

                    <div
                        v-if="modelValue === opt.value"
                        class="absolute right-4 top-1/2 h-1.5 w-1.5 -translate-y-1/2 rounded-full bg-emerald-400 shadow-[0_0_8px_#34d399]"
                    ></div>
                </div>
            </div>
        </transition>
    </div>
</template>
