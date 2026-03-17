<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, router } from '@inertiajs/vue3';

    import { Calendar, Eye, EyeOff, MessageSquare, Star, Trash2, User } from 'lucide-vue-next';

    import AppImage from '@/Components/UI/AppImage.vue';
    import AppRating from '@/Components/UI/AppRating.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import { Review } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        comments: Review[];
    }>();

    const { notifyWithUndo } = useFlash();

    const toggleStatus = (id: number) => {
        router.patch(
            route('admin.comments.update', id),
            {},
            {
                preserveScroll: true,
            },
        );
    };

    let isDeleting = ref<number | null>(null);

    const deleteComment = async (id: number) => {
        if (isDeleting.value === id) return;

        isDeleting.value = id;
        try {
            const confirmed = await notifyWithUndo('Purging the comment from the core...', 5000);

            if (confirmed) {
                router.delete(route('admin.comments.destroy', id), {
                    preserveScroll: true,
                    onFinish: () => {
                        isDeleting.value = null;
                    },
                    onError: () => {
                        isDeleting.value = null;
                    },
                });
            } else {
                isDeleting.value = null;
            }
        } catch (e) {
            isDeleting.value = null;
        }
    };

    const formatDate = (dateStr?: string) => {
        if (!dateStr) return '';
        return new Date(dateStr).toLocaleDateString();
    };
</script>

<template>
    <Head title="Moderation" />

    <div class="mx-auto max-w-4xl space-y-6">
        <div class="px-2">
            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                Review <span class="text-[#c5d86d]">Moderation</span>
            </h1>
            <p class="mt-1 text-sm italic text-zinc-500">Keep the community clean and green.</p>
        </div>

        <div class="space-y-4">
            <transition-group
                enter-active-class="transition duration-500 ease-out"
                enter-from-class="transform translate-x-4 opacity-0"
                leave-active-class="transition duration-300 ease-in"
                leave-to-class="transform -translate-x-10 opacity-0"
                move-class="transition duration-500"
                tag="div"
                class="space-y-4"
            >
                <div
                    v-for="comment in comments"
                    :key="comment.id"
                    class="rounded-[2rem] border bg-[#161b14]/60 p-6 shadow-xl backdrop-blur-sm transition-all duration-300"
                    :class="[
                        comment.is_active
                            ? 'border-white/5 opacity-100'
                            : 'border-red-500/20 bg-red-500/[0.02]',
                        isDeleting === comment.id ? 'pointer-events-none scale-95 opacity-50' : '',
                    ]"
                >
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full bg-[#c5d86d]/10 text-[#c5d86d]"
                            >
                                <AppImage
                                    :src="comment.user?.avatar_url || ''"
                                    :alt="comment.user?.name || 'User'"
                                ></AppImage>
                            </div>
                            <div>
                                <div class="font-bold leading-none text-white">
                                    {{ comment.user?.name || 'Guest' }}
                                </div>
                                <div
                                    class="mt-1 flex items-center gap-1 text-[10px] uppercase tracking-widest text-zinc-500"
                                >
                                    <Calendar class="h-3 w-3"></Calendar>
                                    <span>{{ formatDate(comment.created_at) }}</span>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center gap-1 rounded-full border border-white/5 bg-black/20 px-3 py-1.5"
                        >
                            <AppRating :rating="comment.rating" />

                            <span class="ml-2 text-xs font-black italic text-white">{{
                                comment.rating
                            }}</span>
                        </div>
                    </div>

                    <div class="relative mb-6 rounded-2xl border border-white/5 bg-black/20 p-4">
                        <p class="whitespace-pre-wrap text-sm italic leading-relaxed text-zinc-300">
                            "{{ comment.body }}"
                        </p>
                    </div>

                    <div class="flex items-center justify-between border-t border-white/5 pt-2">
                        <div class="flex items-center gap-2">
                            <div
                                class="h-2 w-2 animate-pulse rounded-full"
                                :class="
                                    comment.is_active
                                        ? 'bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]'
                                        : 'bg-red-500'
                                "
                            ></div>
                            <span
                                class="text-[10px] font-black uppercase tracking-widest transition-colors"
                                :class="comment.is_active ? 'text-emerald-500' : 'text-red-500'"
                            >
                                {{ comment.is_active ? 'Visible on site' : 'Hidden from public' }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2">
                            <button
                                @click="toggleStatus(comment.id)"
                                class="flex items-center gap-2 rounded-xl px-4 py-2 text-xs font-bold transition-all"
                                :class="
                                    comment.is_active
                                        ? 'bg-white/5 text-zinc-400 hover:bg-white/10'
                                        : 'bg-[#c5d86d] text-[#0f120e] hover:shadow-lg hover:shadow-[#c5d86d]/20'
                                "
                            >
                                <Eye v-if="!comment.is_active" class="h-4 w-4" />
                                <EyeOff v-else class="h-4 w-4" />
                                {{ comment.is_active ? 'Hide' : 'Approve' }}
                            </button>

                            <button
                                @click="deleteComment(comment.id)"
                                class="rounded-xl bg-red-500/10 p-2.5 text-red-500 transition-all hover:bg-red-500 hover:text-white"
                                title="Delete permanently"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </transition-group>

            <div
                v-if="comments.length === 0"
                class="rounded-[2rem] border border-dashed border-white/5 bg-[#161b14]/20 py-20 text-center"
            >
                <MessageSquare class="mx-auto mb-4 h-12 w-12 text-zinc-700" />
                <p class="italic text-zinc-500">No reviews yet. Silence is gold...</p>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .v-move {
        transition: transform 0.5s ease;
    }

    .v-leave-active {
        position: absolute;
        width: 100%;
    }
</style>
