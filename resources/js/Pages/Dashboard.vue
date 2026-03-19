<script setup lang="ts">
    import { computed, ref, watch } from 'vue';
    import { onMounted, onUnmounted } from 'vue';

    import { Head, useForm } from '@inertiajs/vue3';

    import { History, MessageSquare, ShieldAlert, ShieldCheck, Star } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import CommentCard from '@/Components/Shared/CommentCard.vue';
    import EditCommentModal from '@/Components/Shared/EditCommentModal.vue';
    import ProfileSettings from '@/Components/Shared/ProfileSettings.vue';
    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import WindEffect from '@/Components/UI/WindEffect.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useCommentActions } from '@/composables/useCommentActions';
    import { useFlash } from '@/composables/useFlash';
    import { useSound } from '@/composables/useSound';
    import { AuthProps, Comment, CommentForm, Seo, UserForm } from '@/types';

    const props = defineProps<{
        myComments: Comment[];
        auth: AuthProps;
        seo: Seo;
    }>();

    const {
        isEditModalOpen,
        selectedComment,
        editForm,
        deletingIds,
        openEditModal,
        handleUpdate,
        deleteComment,
    } = useCommentActions();

    const { playClick, playCancel, playSuccess } = useSound();

    const form = useForm<CommentForm>({
        body: '',
        rating: 5.0,
    });

    const formAvatar = useForm<UserForm>({
        name: props.auth.user.name,
        email: props.auth.user.email,
        avatar: null,
        _method: 'patch',
    });

    const { notify } = useFlash();

    const imageUrl = ref<string | null>(props.auth.user.avatar_url);

    watch(
        () => props.auth.user.avatar_url,
        (newUrl) => {
            if (newUrl && !formAvatar.processing) {
                imageUrl.value = newUrl;
            }
        },
    );

    const isError = ref(false);

    const submitComment = (): void => {
        if (!form.body.trim()) {
            isError.value = true;
            notify('Body text required', 'error');
            playCancel();
            return;
        }

        isError.value = false;

        form.post(route('comments.store'), {
            preserveScroll: true,

            onSuccess: () => {
                form.reset();
                playSuccess();
            },

            onError: (errors) => {
                console.error(errors);
                const firstError = Object.values(errors)[0];
                notify(firstError, 'error');

                isError.value = true;
                playCancel();
            },
        });
    };

    const isAdmin = computed(() => props.auth.user.role === 'admin');

    watch(isEditModalOpen, (isOpen) => {
        if (isOpen) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }
    });

    const handleEsc = (e: KeyboardEvent) => {
        if (e.key === 'Escape' && isEditModalOpen.value) {
            isEditModalOpen.value = false;
            playCancel();
        }
    };

    onMounted(() => window.addEventListener('keydown', handleEsc));
    onUnmounted(() => window.removeEventListener('keydown', handleEsc));

    const ratingProgress = computed(() => {
        return ((form.rating - 0.5) / (5 - 0.5)) * 100;
    });
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <AuthenticatedLayout>
        <div
            class="min-h-screen bg-plant-shop px-4 py-16 font-sans selection:bg-[#c5d86d] selection:text-black sm:px-6 lg:px-8 lg:py-24"
        >
            <div class="mx-auto max-w-5xl">
                <WindEffect :particleCount="35" :windStrength="1.5" />

                <header
                    class="relative z-[3] mb-16 flex flex-col justify-between gap-4 md:flex-row md:items-end"
                >
                    <div>
                        <div class="mb-2 flex items-center gap-3">
                            <span
                                v-if="isAdmin"
                                class="rounded bg-[#c5d86d] px-2 py-0.5 text-[10px] font-black uppercase text-black"
                                >Administrator</span
                            >
                            <span
                                v-else
                                class="rounded bg-white/10 px-2 py-0.5 text-[10px] font-black uppercase text-zinc-400"
                                >User</span
                            >
                        </div>
                        <h2
                            class="mb-2 text-5xl font-black uppercase italic tracking-tighter text-white"
                        >
                            User <span class="text-[#c5d86d]">Core</span>
                        </h2>
                        <p class="font-medium tracking-tight text-zinc-500">
                            Accessing terminal for {{ formAvatar.name }}...
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <div
                            class="min-w-[120px] rounded-xl border border-emerald-400/50 p-4 backdrop-blur-sm"
                        >
                            <p class="mb-1 text-[10px] font-black uppercase text-zinc-600">
                                Feedback count
                            </p>
                            <p class="text-2xl font-black text-white">{{ myComments.length }}</p>
                        </div>
                    </div>
                </header>

                <ProfileSettings :auth="auth" />

                <div
                    class="relative z-[3] grid grid-cols-1 gap-8 italic lg:grid-cols-12"
                    id="feedbackSection"
                >
                    <section
                        class="rounded-[1rem] border border-emerald-400/50 p-4 shadow-2xl backdrop-blur-xl md:p-8 lg:col-span-5 lg:rounded-[2rem]"
                    >
                        <h3
                            class="mb-8 flex items-center gap-3 text-xs font-black uppercase tracking-[0.2em] text-white"
                        >
                            <MessageSquare class="h-4 w-4 text-[#c5d86d]" /> New Feedback
                        </h3>

                        <form @submit.prevent="submitComment" class="space-y-6">
                            <div class="space-y-2">
                                <div class="group relative overflow-hidden">
                                    <textarea
                                        v-model="form.body"
                                        id="reviewMessage"
                                        @input="isError = false"
                                        :disabled="form.processing"
                                        rows="4"
                                        placeholder=" "
                                        class="custom-scrollbar peer relative z-[1] w-full resize-none rounded-xl border-0 bg-[#0d0d0d] p-5 pt-10 text-sm text-white placeholder-zinc-600 outline-none transition-all focus:border-emerald-500/50 focus:shadow-none focus:outline-none focus:ring-0 focus:ring-offset-0"
                                        :class="{ 'border-red-500/50': form.errors.body }"
                                    ></textarea>

                                    <label
                                        for="reviewMessage"
                                        class="pointer-events-none absolute left-3 top-0 z-[2] origin-left px-2 py-4 text-xs font-black uppercase tracking-widest text-zinc-500 transition-all duration-300 before:absolute before:inset-0 before:-z-10 before:rounded-lg before:bg-[#0d0d0d] before:opacity-0 before:transition-opacity peer-focus:-translate-y-[15%] peer-focus:scale-75 peer-focus:text-emerald-400 peer-focus:before:opacity-100 peer-[:not(:placeholder-shown)]:-translate-y-[15%] peer-[:not(:placeholder-shown)]:scale-75 peer-[:not(:placeholder-shown)]:text-emerald-400 peer-[:not(:placeholder-shown)]:before:opacity-100"
                                    >
                                        Report experience
                                    </label>
                                </div>

                                <Transition name="fade">
                                    <div
                                        v-if="isError"
                                        class="mt-4 flex items-start gap-3 rounded-xl border border-red-500/20 bg-red-500/5 p-4"
                                    >
                                        <ShieldAlert
                                            class="animate-pulse-fast h-5 w-5 shrink-0 text-red-500"
                                        />
                                        <div class="space-y-1">
                                            <p
                                                class="text-[10px] font-black uppercase tracking-[0.2em] text-red-500"
                                            >
                                                [Failure]
                                            </p>
                                            <p
                                                class="text-[11px] font-medium leading-relaxed text-red-400/80"
                                            >
                                                Transmission body is null or very small. Terminal
                                                requires a valid string input.
                                            </p>
                                        </div>
                                    </div>
                                </Transition>
                            </div>

                            <div class="rounded-2xl border border-white/5 bg-black/20 p-4">
                                <div class="mb-4 flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-zinc-400"
                                        >Signal Rating</span
                                    >
                                    <div class="flex items-center gap-1 text-[#c5d86d]">
                                        <span class="text-lg font-black">{{ form.rating }}</span>
                                        <Star class="h-4 w-4 fill-current" />
                                    </div>
                                </div>

                                <input
                                    type="range"
                                    @change="playClick"
                                    v-model="form.rating"
                                    min="0.5"
                                    max="5"
                                    step="0.5"
                                    class="h-1.5 w-full cursor-pointer appearance-none rounded-lg bg-emerald-700 accent-[#c5d86d]"
                                    :style="{
                                        background: `linear-gradient(to right, #c5d86d ${ratingProgress}%, rgba(255,255,255,0.1) ${ratingProgress}%)`,
                                    }"
                                />
                            </div>

                            <button
                                type="submit"
                                @mousedown="playClick"
                                :disabled="form.processing"
                                class="w-full cursor-pointer rounded-2xl bg-emerald-400 py-4 text-[10px] font-black uppercase tracking-widest text-black transition-all hover:scale-[1.02] active:scale-95"
                                :class="{ 'shake-anim': isError }"
                            >
                                {{ form.processing ? 'Syncing...' : 'Publish' }}
                            </button>
                        </form>
                    </section>

                    <section
                        class="flex flex-col rounded-[1rem] border border-emerald-400/50 p-4 shadow-2xl backdrop-blur-xl md:p-8 lg:col-span-7 lg:rounded-[2rem]"
                    >
                        <h3
                            class="mb-8 flex items-center gap-3 text-xs font-black uppercase tracking-[0.2em] text-white"
                        >
                            <History class="h-4 w-4 text-[#c5d86d]" /> Archive_Log
                        </h3>

                        <div
                            class="custom-scrollbar h-[600px] snap-y snap-proximity space-y-4 overflow-y-auto scroll-smooth pr-2"
                        >
                            <div
                                v-if="myComments.length === 0"
                                class="flex flex-col items-center justify-center py-20 text-emerald-800"
                            >
                                <ShieldCheck class="mb-4 h-12 w-12 opacity-30" />
                                <p
                                    class="text-center text-[10px] font-black uppercase tracking-widest"
                                >
                                    No previous logs found in the database
                                </p>
                            </div>

                            <CommentCard
                                v-for="comment in myComments"
                                :key="comment.id"
                                :comment="comment"
                                :userName="auth.user.name"
                                :is-deleting="deletingIds.has(comment.id)"
                                :title="seo.title"
                                :text="seo.description"
                                :url="seo.canonical || ''"
                                @edit="openEditModal"
                                @delete="deleteComment"
                                class="snap-start snap-always shadow-none"
                            />
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <EditCommentModal
            v-model="isEditModalOpen"
            :comment="selectedComment"
            @submit="handleUpdate"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
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

    @keyframes shake {
        0%,
        100% {
            transform: translateX(0);
        }
        25% {
            transform: translateX(-4px);
        }
        75% {
            transform: translateX(4px);
        }
    }

    .shake-anim {
        animation: shake 0.2s ease-in-out 0s 2;
        border: 1px solid rgba(248, 113, 113, 0.5) !important;
    }

    .fade-enter-active,
    .fade-leave-active {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .fade-enter-from,
    .fade-leave-to {
        opacity: 0;
        transform: translateY(-8px);
    }

    .animate-pulse-fast {
        animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    @keyframes pulse {
        0%,
        100% {
            opacity: 1;
        }
        50% {
            opacity: 0.6;
        }
    }
</style>
