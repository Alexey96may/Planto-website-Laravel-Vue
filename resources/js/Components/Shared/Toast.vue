<script setup lang="ts">
    import { computed } from 'vue';

    import { useFlash } from '@/composables/useFlash';

    const { show, message, type } = useFlash();

    const config = {
        success: { bg: 'bg-green-500', icon: '✅' },
        error: { bg: 'bg-red-500', icon: '❌' },
        warning: { bg: 'bg-yellow-500', icon: '⚠️' },
        info: { bg: 'bg-blue-500', icon: 'ℹ️' },
    };

    const current = computed(() => config[type.value]);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="transform translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed top-5 right-5 z-[100] p-4 rounded-lg shadow-xl text-white min-w-[300px]"
                :class="current.bg"
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <span class="mr-3 text-xl">{{ current.icon }}</span>
                        <span class="font-medium">{{ message }}</span>
                    </div>
                    <button
                        @click="show = false"
                        class="ml-4 opacity-70 hover:opacity-100 text-xl font-bold"
                    >
                        &times;
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
