<script setup lang="ts">
    import { useSound } from '@/composables/useSound';
    import { useUiSettings } from '@/composables/useUiSettings';

    const { isEffectsEnabled } = useUiSettings();
    const { playClick } = useSound();
</script>

<template>
    <div class="flex flex-col gap-6 pt-8">
        <div class="flex items-center justify-start gap-4 text-sm text-zinc-400">
            <span class="font-medium tracking-wide">Visual Effects</span>

            <button
                type="button"
                @click="isEffectsEnabled = !isEffectsEnabled"
                @mousedown="playClick"
                class="relative h-6 w-11 shrink-0 rounded-full transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2 focus:ring-offset-zinc-900"
                :class="isEffectsEnabled ? 'bg-emerald-600' : 'bg-plant-shop'"
                aria-label="Toggle visual effects"
            >
                <div
                    class="absolute left-1 top-1 h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform duration-300"
                    :class="isEffectsEnabled ? 'translate-x-5' : 'translate-x-0'"
                ></div>
            </button>
        </div>
    </div>
</template>
