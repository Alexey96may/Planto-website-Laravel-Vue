<script setup lang="ts">
    defineProps<{
        show: boolean;
        title: string;
        message: string;
    }>();

    const emit = defineEmits(['confirm', 'close']);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4 backdrop-blur-sm"
            >
                <div
                    class="w-full max-w-md overflow-hidden rounded-[2rem] border border-white/10 bg-[#161b14] p-8 shadow-2xl"
                >
                    <h3 class="text-2xl font-black uppercase tracking-tighter text-white">
                        {{ title }}
                    </h3>
                    <p class="mt-4 italic text-zinc-400">
                        {{ message }}
                    </p>

                    <div class="mt-8 flex gap-4">
                        <button
                            @click="emit('close')"
                            class="flex-1 rounded-xl border border-white/5 bg-white/5 py-4 text-xs font-black uppercase tracking-widest text-white transition-all hover:bg-white/10"
                        >
                            Cancel
                        </button>
                        <button
                            @click="emit('confirm')"
                            class="flex-1 rounded-xl bg-red-500 py-4 text-xs font-black uppercase tracking-widest text-white transition-all hover:bg-red-600 hover:shadow-[0_0_20px_rgba(239,68,68,0.3)]"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
