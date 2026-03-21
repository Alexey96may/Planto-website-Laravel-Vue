<script setup lang="ts">
    import { Edit3, Trash2 } from 'lucide-vue-next';

    import AppRating from '@/Components/UI/AppRating.vue';
    import ShareButton from '@/Components/UI/ShareButton.vue';
    import { useSound } from '@/composables/useSound';
    import { Comment } from '@/types';

    defineProps<{
        comment: Comment;
        isDeleting: boolean;
        userName: string;
        title: string;
        text: string;
        url: string;
    }>();

    const emit = defineEmits<{
        (e: 'edit', comment: Comment): void;
        (e: 'delete', id: number): void;
    }>();

    const { playClick } = useSound();
</script>

<template>
    <article
        class="group relative snap-start snap-always scroll-mt-6 rounded-[1rem] border border-white/5 bg-black/40 p-5 transition-all hover:border-[#c5d86d]/30"
        :class="{
            'pointer-events-none scale-95 opacity-30 grayscale': isDeleting,
        }"
        :aria-busy="isDeleting"
    >
        <div
            class="mb-4 flex flex-col flex-wrap items-end justify-between gap-2 sm:flex-row sm:items-center"
        >
            <div
                class="flex items-center gap-1.5"
                :aria-label="`Rating: ${comment.rating} out of 5 stars`"
            >
                <AppRating :rating="comment.rating" aria-hidden="true" />
                <span class="text-[1rem] font-bold text-emerald-500" aria-hidden="true">
                    ({{ comment.rating }})
                </span>
            </div>

            <div class="flex items-center gap-3">
                <button
                    @click="emit('delete', comment.id)"
                    @mousedown="playClick"
                    type="button"
                    class="text-zinc-600 transition-colors duration-200 hover:text-red-400 focus:text-red-400 focus:outline-none"
                    aria-label="Delete comment"
                >
                    <Trash2 class="h-4 w-4" aria-hidden="true" />
                </button>

                <ShareButton variant="icon" :title="title" :text="text" :url="url" />

                <button
                    v-if="!comment.is_active"
                    @click="emit('edit', comment)"
                    @mousedown="playClick"
                    type="button"
                    class="text-zinc-600 transition-colors duration-200 hover:text-[#c5d86d] focus:text-[#c5d86d] focus:outline-none"
                    aria-label="Edit pending transmission"
                >
                    <Edit3 class="h-4 w-4" aria-hidden="true" />
                </button>

                <span
                    class="rounded border border-white/10 px-2 py-0.5 text-[8px] font-black uppercase tracking-tighter"
                    :class="
                        comment.is_active ? 'border-[#c5d86d]/20 text-[#c5d86d]' : 'text-zinc-600'
                    "
                    role="status"
                >
                    {{ comment.is_active ? 'Verified' : 'Pending' }}
                </span>
            </div>
        </div>

        <p class="text-xs font-medium leading-relaxed text-zinc-400">
            {{ comment.body }}
        </p>
    </article>
</template>
