<script setup lang="ts">
    import { computed, nextTick, ref, watch } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import { Edit3, Star, X } from 'lucide-vue-next';

    import { useSound } from '@/composables/useSound';
    import { Comment } from '@/types';

    const props = defineProps<{
        modelValue: boolean;
        comment: Comment | null;
    }>();

    const emit = defineEmits(['update:modelValue', 'success', 'submit']);
    const { playCancel, playClick } = useSound();

    const modalRef = ref<HTMLElement | null>(null);
    const firstInput = ref<HTMLElement | null>(null);

    const form = useForm({
        body: '',
        rating: 5,
    });

    watch(
        () => props.modelValue,
        async (isOpen) => {
            if (isOpen) {
                if (props.comment) {
                    form.body = props.comment.body;
                    form.rating = props.comment.rating;
                }
                document.body.style.overflow = 'hidden';
                await nextTick();
                firstInput.value?.focus();
            } else {
                document.body.style.overflow = '';
            }
        },
    );

    const closeModal = () => {
        emit('update:modelValue', false);
        playCancel();
    };

    const handleKeydown = (e: KeyboardEvent) => {
        if (e.key === 'Escape' && props.modelValue) closeModal();
    };

    const submit = () => {
        emit('submit', { body: form.body, rating: form.rating });
    };

    const ratingProgress = computed(() => ((form.rating - 0.5) / (5 - 0.5)) * 100);
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div
                v-if="modelValue"
                class="fixed inset-0 z-[100] flex items-center justify-center px-4"
                role="dialog"
                aria-modal="true"
                aria-labelledby="modal-title"
                @keydown="handleKeydown"
            >
                <div
                    class="absolute inset-0 bg-black/80 backdrop-blur-md"
                    @click="closeModal"
                    aria-hidden="true"
                ></div>

                <div
                    ref="modalRef"
                    class="relative flex max-h-[90vh] w-full max-w-lg flex-col rounded-[2rem] border border-emerald-400/50 bg-[#0a0f0d] p-6 shadow-2xl md:p-8"
                >
                    <div class="mb-6 flex items-center justify-between">
                        <h3
                            id="modal-title"
                            class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-white"
                        >
                            <Edit3 class="h-4 w-4 text-[#c5d86d]" aria-hidden="true" />
                            Modification Mode
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-zinc-500 outline-none hover:text-white focus:text-white"
                            aria-label="Close modal"
                        >
                            <X class="h-5 w-5" />
                        </button>
                    </div>

                    <form @submit.prevent="submit" class="flex flex-col overflow-hidden">
                        <div class="custom-scrollbar space-y-6 overflow-y-auto pb-4 pr-2">
                            <div class="space-y-2">
                                <label for="edit-body" class="sr-only">Comment Body</label>
                                <textarea
                                    id="edit-body"
                                    ref="firstInput"
                                    v-model="form.body"
                                    class="min-h-[120px] w-full resize-none rounded-xl border border-white/5 bg-black/40 p-5 text-sm text-white outline-none transition-all focus:ring-1 focus:ring-emerald-500/50"
                                    placeholder="Edit your feedback..."
                                    required
                                ></textarea>
                            </div>

                            <div class="rounded-2xl border border-white/5 bg-black/20 p-4">
                                <div class="mb-4 flex items-center justify-between">
                                    <label
                                        for="edit-rating"
                                        class="text-[10px] font-black uppercase tracking-widest text-zinc-400"
                                    >
                                        Signal Rating
                                    </label>
                                    <div
                                        class="flex items-center gap-1 text-[#c5d86d]"
                                        aria-hidden="true"
                                    >
                                        <span class="text-lg font-black">{{ form.rating }}</span>
                                        <Star class="h-4 w-4 fill-current" />
                                    </div>
                                </div>

                                <input
                                    id="edit-rating"
                                    type="range"
                                    v-model.number="form.rating"
                                    min="0.5"
                                    max="5"
                                    step="0.5"
                                    @input="playClick"
                                    class="h-1.5 w-full cursor-pointer appearance-none rounded-lg bg-emerald-700 accent-[#c5d86d] outline-none focus:ring-4 focus:ring-emerald-500/20"
                                    :style="{
                                        background: `linear-gradient(to right, #c5d86d ${ratingProgress}%, rgba(255,255,255,0.1) ${ratingProgress}%)`,
                                    }"
                                />
                            </div>
                        </div>

                        <div class="mt-auto flex gap-4 border-t border-white/5 pt-6">
                            <button
                                type="button"
                                @click="closeModal"
                                class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 outline-none transition-colors hover:text-white focus:text-white"
                            >
                                Abort
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex-1 rounded-2xl bg-emerald-400 py-4 text-[10px] font-black uppercase tracking-widest text-black outline-none transition-all hover:bg-emerald-300 focus:ring-4 focus:ring-emerald-400/30 disabled:opacity-50"
                            >
                                {{ form.processing ? 'Syncing...' : 'Apply_Changes' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
    .modal-enter-active,
    .modal-leave-active {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .modal-enter-from,
    .modal-leave-to {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
        margin: 10px 0;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(16, 185, 129, 0.2);
        border-radius: 10px;
        border: 1px solid rgba(16, 185, 129, 0.1);
        transition: all 0.3s ease;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(16, 185, 129, 0.5);
        box-shadow: 0 0 8px rgba(16, 185, 129, 0.3);
    }
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(16, 185, 129, 0.2) transparent;
    }

    input[type='range']::-webkit-slider-thumb {
        appearance: none;
        width: 16px;
        height: 16px;
        background: #c5d86d;
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 0 10px rgba(197, 216, 109, 0.5);
    }
</style>
