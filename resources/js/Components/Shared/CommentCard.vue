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
    }>();

    const emit = defineEmits<{
        (e: 'edit', comment: Comment): void;
        (e: 'delete', id: number): void;
    }>();

    const { playClick } = useSound();
</script>

<template>
    <div
        class="group relative snap-start snap-always scroll-mt-6 rounded-[1rem] border border-white/5 bg-black/40 p-5 transition-all hover:border-[#c5d86d]/30"
        :class="{
            'pointer-events-none scale-95 opacity-30 grayscale': isDeleting,
        }"
    >
        <div
            class="mb-4 flex flex-col flex-wrap items-end justify-between gap-2 sm:flex-row sm:items-center"
        >
            <div class="flex items-center gap-1.5">
                <AppRating :rating="comment.rating" />
                <span class="text-[1rem] text-emerald-500">({{ comment.rating }})</span>
            </div>

            <div class="flex gap-2">
                <button
                    @click="emit('delete', comment.id)"
                    @mousedown="playClick"
                    class="text-zinc-600 transition-colors duration-200 hover:text-red-400"
                    title="Delete log"
                >
                    <Trash2 class="h-4 w-4" />
                </button>

                <ShareButton variant="icon" :title="'Review by ' + userName" :text="comment.body" />

                <button
                    v-if="!comment.is_active"
                    @click="emit('edit', comment)"
                    @mousedown="playClick"
                    class="text-zinc-600 transition-colors duration-200 hover:text-[#c5d86d]"
                    title="Edit transmission"
                >
                    <Edit3 class="h-4 w-4" />
                </button>

                <span
                    class="rounded border border-white/10 px-2 py-0.5 text-[8px] font-black uppercase tracking-tighter"
                    :class="
                        comment.is_active ? 'border-[#c5d86d]/20 text-[#c5d86d]' : 'text-zinc-600'
                    "
                >
                    {{ comment.is_active ? 'Verified' : 'Pending' }}
                </span>
            </div>
        </div>

        <p class="text-xs font-medium leading-relaxed text-zinc-400">
            {{ comment.body }}
        </p>
    </div>
</template>
