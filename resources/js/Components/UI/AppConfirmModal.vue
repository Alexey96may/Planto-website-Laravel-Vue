<script setup lang="ts">
    import { nextTick, onUnmounted, ref, watch } from 'vue';

    const props = defineProps<{
        show: boolean;
        title: string;
        message: string;
    }>();

    const emit = defineEmits(['confirm', 'close']);

    const modalRef = ref<HTMLElement | null>(null);
    let lastFocusedElement: HTMLElement | null = null;

    const handleKeyDown = (e: KeyboardEvent) => {
        if (!props.show) return;

        // 1. Закрытие на Escape
        if (e.key === 'Escape') {
            emit('close');
            return;
        }

        if (e.key === 'Tab' && modalRef.value) {
            const focusableElements = modalRef.value.querySelectorAll(
                'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])',
            );
            const firstElement = focusableElements[0] as HTMLElement;
            const lastElement = focusableElements[focusableElements.length - 1] as HTMLElement;

            if (e.shiftKey) {
                if (document.activeElement === firstElement) {
                    lastElement.focus();
                    e.preventDefault();
                }
            } else {
                if (document.activeElement === lastElement) {
                    firstElement.focus();
                    e.preventDefault();
                }
            }
        }
    };

    watch(
        () => props.show,
        async (isVisible) => {
            if (isVisible) {
                lastFocusedElement = document.activeElement as HTMLElement;
                document.addEventListener('keydown', handleKeyDown);

                await nextTick();
                const firstButton = modalRef.value?.querySelector('button');
                firstButton?.focus();
            } else {
                document.removeEventListener('keydown', handleKeyDown);
                lastFocusedElement?.focus();
            }
        },
        { immediate: true },
    );

    onUnmounted(() => {
        document.removeEventListener('keydown', handleKeyDown);
    });
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
                @click.self="emit('close')"
            >
                <div
                    ref="modalRef"
                    role="dialog"
                    aria-modal="true"
                    aria-labelledby="modal-title"
                    aria-describedby="modal-description"
                    class="w-full max-w-md overflow-hidden rounded-[2rem] border border-white/10 bg-[#161b14] p-8 shadow-2xl"
                >
                    <h3
                        id="modal-title"
                        class="text-2xl font-black uppercase tracking-tighter text-white"
                    >
                        {{ title }}
                    </h3>
                    <p id="modal-description" class="mt-4 italic text-zinc-400">
                        {{ message }}
                    </p>

                    <div class="mt-8 flex gap-4">
                        <button
                            type="button"
                            @click="emit('close')"
                            class="flex-1 rounded-xl border border-white/5 bg-white/5 py-4 text-xs font-black uppercase tracking-widest text-white outline-none transition-all hover:bg-white/10 focus-visible:ring-2 focus-visible:ring-white/20"
                        >
                            Cancel
                        </button>
                        <button
                            type="button"
                            @click="emit('confirm')"
                            class="flex-1 rounded-xl bg-red-500 py-4 text-xs font-black uppercase tracking-widest text-white outline-none transition-all hover:bg-red-600 focus-visible:ring-2 focus-visible:ring-red-400"
                        >
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
