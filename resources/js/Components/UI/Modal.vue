<script setup lang="ts">
    import { computed, onMounted, onUnmounted, ref, watch } from 'vue';

    import { useSound } from '@/composables/useSound';

    interface Props {
        show?: boolean;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl' | 'full';
        closeable?: boolean;
        ariaLabelledby?: string; // ID заголовка внутри модалки
    }

    const props = withDefaults(defineProps<Props>(), {
        show: false,
        maxWidth: '2xl',
        closeable: true,
    });

    const emit = defineEmits<{
        (e: 'close'): void;
    }>();

    const dialog = ref<HTMLDialogElement | null>(null);
    const showSlot = ref<boolean>(props.show);
    const returnFocusElement = ref<HTMLElement | null>(null);

    watch(
        () => props.show,
        (isVisible: boolean) => {
            if (isVisible) {
                returnFocusElement.value = document.activeElement as HTMLElement;

                const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.paddingRight = `${scrollBarWidth}px`;
                document.body.style.overflow = 'hidden';

                showSlot.value = true;
                dialog.value?.showModal();
            } else {
                setTimeout(() => {
                    document.body.style.overflow = '';
                    document.body.style.paddingRight = '';
                    dialog.value?.close();
                    showSlot.value = false;

                    returnFocusElement.value?.focus();
                }, 200);
            }
        },
    );

    const close = (): void => {
        if (props.closeable) {
            emit('close');
        }
    };

    const closeOnEscape = (e: KeyboardEvent): void => {
        if (e.key === 'Escape') {
            e.preventDefault();
            if (props.show) close();
        }
    };

    onMounted(() => document.addEventListener('keydown', closeOnEscape));
    onUnmounted(() => {
        document.removeEventListener('keydown', closeOnEscape);
        document.body.style.overflow = '';
    });

    const maxWidthClass = computed(() => {
        const classMap: Record<string, string> = {
            sm: 'sm:max-w-sm',
            md: 'sm:max-w-md',
            lg: 'sm:max-w-lg',
            xl: 'sm:max-w-xl',
            '2xl': 'sm:max-w-2xl',
            full: 'sm:max-w-none',
        };
        return classMap[props.maxWidth] ?? classMap['2xl'];
    });

    const { playModalOpen, playModalClose } = useSound();

    watch(
        () => props.show,
        (newVal) => {
            if (newVal) playModalOpen();
            else playModalClose();
        },
    );
</script>

<template>
    <dialog
        ref="dialog"
        class="z-50 m-0 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent"
        :aria-labelledby="ariaLabelledby"
        aria-modal="true"
    >
        <div class="fixed inset-0 z-50 overflow-y-auto px-4 py-6 sm:px-0" scroll-region>
            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0"
                enter-to-class="opacity-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div v-show="show" class="fixed inset-0 transform transition-all" @click="close">
                    <div class="absolute inset-0 bg-black/90 backdrop-blur-sm" aria-hidden="true" />
                </div>
            </Transition>

            <Transition
                enter-active-class="ease-out duration-300"
                enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                enter-to-class="opacity-100 translate-y-0 sm:scale-100"
                leave-active-class="ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0 sm:scale-100"
                leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            >
                <div
                    v-show="show"
                    class="mb-6 transform overflow-hidden rounded-[2rem] border border-white/10 bg-[#161b14] shadow-xl transition-all sm:mx-auto sm:w-full"
                    :class="[props.maxWidth === 'full' ? 'w-auto' : 'sm:w-full', maxWidthClass]"
                >
                    <div class="custom-scrollbar overflow-y-auto p-2">
                        <slot v-if="showSlot" />
                    </div>
                </div>
            </Transition>
        </div>
    </dialog>
</template>
