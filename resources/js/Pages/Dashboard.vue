<script setup lang="ts">
    import { computed, ref, watch } from 'vue';
    import { onMounted, onUnmounted } from 'vue';

    import { Head, useForm } from '@inertiajs/vue3';

    import {
        Edit3,
        History,
        Mail,
        MessageSquare,
        Save,
        ShieldCheck,
        Star,
        Trash2,
        User,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import AppRating from '@/Components/UI/AppRating.vue';
    import ImageUploader from '@/Components/UI/ImageUploader.vue';
    import WindEffect from '@/Components/UI/WindEffect.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import { useSound } from '@/composables/useSound';
    import { AuthProps, Comment, CommentForm, UserForm } from '@/types';

    const props = defineProps<{
        myComments: Comment[];
        auth: AuthProps;
    }>();

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

    const { notifyWithUndo } = useFlash();

    const imageUrl = ref<string | null>(props.auth.user.avatar_url);

    watch(
        () => props.auth.user.avatar_url,
        (newUrl) => {
            if (newUrl && !formAvatar.processing) {
                imageUrl.value = newUrl;
            }
        },
    );

    const submitInfo = (): void => {
        formAvatar.post(route('profile.update'), {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => {
                imageUrl.value = props.auth.user.avatar_url;

                if (imageUrl.value && imageUrl.value.startsWith('blob:')) {
                    URL.revokeObjectURL(imageUrl.value);
                }

                playSuccess();

                formAvatar.avatar = null;
            },
            onError: () => {
                imageUrl.value = props.auth.user.avatar_url;
                playCancel();
            },
        });
    };

    const submitComment = (): void => {
        form.post(route('comments.store'), {
            preserveScroll: true,

            onSuccess: () => {
                form.reset();
                playSuccess();
            },
            onError: (errors) => {
                console.error(errors);
                playCancel();
            },
        });
    };

    const isAdmin = computed(() => props.auth.user.role === 'admin');

    const handleFileSelect = (file: File | null) => {
        formAvatar.avatar = file;
        if (file) {
            imageUrl.value = URL.createObjectURL(file);
        } else {
            imageUrl.value = props.auth.user.avatar_url;
        }
    };

    const isEditModalOpen = ref(false);

    const editForm = useForm({
        id: null as number | null,
        body: '',
        rating: 5.0,
    });

    const openEditModal = (comment: Comment) => {
        editForm.id = comment.id;
        editForm.body = comment.body;
        editForm.rating = comment.rating;
        isEditModalOpen.value = true;
    };

    const submitUpdate = () => {
        if (!editForm.id) return;

        editForm.patch(route('comments.update', editForm.id), {
            preserveScroll: true,
            onSuccess: () => {
                isEditModalOpen.value = false;
                playSuccess();
            },
            onError: () => playCancel(),
        });
    };

    let deletingIds = ref<Set<number>>(new Set());

    const deleteComment = async (id: number) => {
        deletingIds.value.add(id);

        try {
            const confirmed = await notifyWithUndo('Review deletion scheduled...', 5000);

            if (confirmed) {
                form.delete(route('comments.destroy', id), {
                    preserveScroll: true,
                    onSuccess: () => {
                        playSuccess();
                        deletingIds.value.delete(id);
                    },
                    onError: () => {
                        playCancel();
                        deletingIds.value.delete(id);
                    },
                });
            } else {
                deletingIds.value.delete(id);
            }
        } catch (e) {
            deletingIds.value.delete(id);
        }
    };

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
</script>

<template>
    <Head title="System Dashboard" />

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

                <section
                    class="group relative z-[3] mb-16 overflow-hidden rounded-[1rem] border border-emerald-400/50 p-4 backdrop-blur-xl md:p-8 lg:rounded-[2rem]"
                >
                    <form @submit.prevent="submitInfo" class="relative z-10 space-y-8">
                        <div class="flex flex-col items-start gap-12 lg:flex-row">
                            <div class="group/avatar relative mx-auto">
                                <div
                                    class="transition-all duration-500 group-hover/avatar:border-[#c5d86d]/50"
                                >
                                    <div
                                        class="group/avatar relative mx-auto shrink-0 lg:h-48 lg:w-48"
                                    >
                                        <ImageUploader
                                            ref="uploader"
                                            v-model="formAvatar.avatar"
                                            label="Avatar"
                                            :error="formAvatar.errors.avatar"
                                            :existingImage="imageUrl"
                                            @on-file-select="handleFileSelect"
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="w-full flex-1 space-y-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <label
                                            class="ml-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                            for="name"
                                            >Identity Name</label
                                        >
                                        <div class="relative">
                                            <User
                                                class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                            />
                                            <input
                                                v-model="formAvatar.name"
                                                id="name"
                                                type="text"
                                                class="w-full rounded-2xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                            />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="ml-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                            for="email"
                                            >Secure Email</label
                                        >
                                        <div class="relative">
                                            <Mail
                                                class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                            />
                                            <input
                                                v-model="formAvatar.email"
                                                id="email"
                                                type="email"
                                                class="w-full rounded-2xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                            />
                                        </div>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center justify-end gap-2 border-t border-white/5 pt-6"
                                >
                                    <button
                                        type="submit"
                                        @mousedown="playClick"
                                        :disabled="formAvatar.processing"
                                        class="flex items-center gap-3 rounded-2xl bg-emerald-500 px-8 py-4 text-[10px] font-black uppercase tracking-widest text-black transition-all hover:bg-emerald-200 disabled:opacity-50"
                                    >
                                        <Save class="h-4 w-4" />
                                        {{ formAvatar.processing ? 'Syncing...' : 'Update' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </section>

                <div
                    class="relative z-[3] grid grid-cols-1 gap-8 italic lg:grid-cols-12 lg:items-start"
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
                            <textarea
                                v-model="form.body"
                                :disabled="form.processing"
                                rows="4"
                                class="custom-scrollbar w-full resize-none rounded-xl border border-white/5 bg-black/40 p-5 text-sm text-white placeholder-zinc-600 outline-none transition-all focus:ring-1 focus:ring-emerald-500/50"
                                placeholder="Report your experience with the Plant Shop..."
                            ></textarea>

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
                                />
                            </div>

                            <button
                                type="submit"
                                @mousedown="playClick"
                                :disabled="form.processing || !form.body"
                                class="w-full cursor-pointer rounded-2xl bg-emerald-400 py-4 text-[10px] font-black uppercase tracking-widest text-black transition-all hover:scale-[1.02] active:scale-95"
                            >
                                Publish Transmission
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

                        <div class="custom-scrollbar space-y-4 overflow-y-auto pr-2">
                            <div
                                v-if="myComments.length === 0"
                                class="flex flex-col items-center justify-center py-20 text-zinc-600"
                            >
                                <ShieldCheck class="mb-4 h-12 w-12 opacity-10" />
                                <p
                                    class="text-center text-[10px] font-black uppercase tracking-widest"
                                >
                                    No previous logs found in the database
                                </p>
                            </div>

                            <div
                                v-for="comment in myComments"
                                :key="comment.id"
                                class="group relative rounded-[1rem] border border-white/5 bg-black/40 p-5 transition-all hover:border-[#c5d86d]/30"
                                :class="{
                                    'pointer-events-none scale-95 opacity-30 grayscale':
                                        deletingIds.has(comment.id),
                                }"
                            >
                                <div class="mb-3 flex items-center justify-between gap-2">
                                    <div class="flex items-center gap-1.5">
                                        <AppRating :rating="comment.rating" />
                                        <span class="text-[1rem] text-emerald-500"
                                            >({{ comment.rating }})</span
                                        >
                                    </div>

                                    <div class="flex gap-2">
                                        <button
                                            @click="deleteComment(comment.id)"
                                            class="text-zinc-600 transition-colors duration-200 hover:text-red-400"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>

                                        <button
                                            v-if="!comment.is_active"
                                            @click="openEditModal(comment)"
                                            class="text-zinc-600 transition-colors duration-200 hover:text-[#c5d86d]"
                                        >
                                            <Edit3 class="h-4 w-4" />
                                        </button>

                                        <span
                                            class="rounded border border-white/10 px-2 py-0.5 text-[8px] font-black uppercase tracking-tighter"
                                            :class="
                                                comment.is_active
                                                    ? 'border-[#c5d86d]/20 text-[#c5d86d]'
                                                    : 'text-zinc-600'
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
                        </div>
                    </section>
                </div>
            </div>
        </div>

        <Teleport to="body">
            <Transition name="modal">
                <div
                    v-if="isEditModalOpen"
                    class="fixed inset-0 z-[100] flex items-center justify-center px-4"
                >
                    <div
                        class="absolute inset-0 bg-black/80 backdrop-blur-md"
                        @click="isEditModalOpen = false"
                    ></div>

                    <div
                        class="relative flex max-h-[90vh] w-full max-w-lg flex-col rounded-[2rem] border border-emerald-400/50 bg-plant-shop p-6 shadow-2xl md:p-8"
                    >
                        <h3
                            class="mb-6 flex shrink-0 items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-white"
                        >
                            <Edit3 class="h-4 w-4 text-[#c5d86d]" /> Modification Mode
                        </h3>

                        <form
                            @submit.prevent="submitUpdate"
                            class="custom-scrollbar space-y-6 overflow-y-auto pr-2"
                        >
                            <div class="space-y-6">
                                <textarea
                                    v-model="editForm.body"
                                    class="custom-scrollbar min-h-[120px] w-full resize-none rounded-xl border border-white/5 bg-black/40 p-5 text-sm text-white outline-none focus:ring-1 focus:ring-emerald-500/50"
                                ></textarea>

                                <div class="rounded-2xl border border-white/5 bg-black/20 p-4">
                                    <div class="mb-4 flex items-center justify-between">
                                        <span
                                            class="text-[10px] font-black uppercase tracking-widest text-zinc-400"
                                            >Signal Rating</span
                                        >
                                        <div class="flex items-center gap-1 text-[#c5d86d]">
                                            <span class="text-lg font-black">{{
                                                editForm.rating
                                            }}</span>
                                            <Star class="h-4 w-4 fill-current" />
                                        </div>
                                    </div>
                                    <input
                                        type="range"
                                        v-model="editForm.rating"
                                        min="0.5"
                                        max="5"
                                        step="0.5"
                                        class="h-1.5 w-full cursor-pointer appearance-none rounded-lg bg-emerald-700 accent-[#c5d86d]"
                                    />
                                </div>
                            </div>

                            <div class="sticky bottom-0 flex gap-4 bg-plant-shop pb-2 pt-4">
                                <button
                                    type="button"
                                    @click="isEditModalOpen = false"
                                    class="flex-1 py-4 text-[10px] font-black uppercase tracking-widest text-zinc-500 transition-colors hover:text-white"
                                >
                                    Abort
                                </button>
                                <button
                                    type="submit"
                                    :disabled="editForm.processing"
                                    class="flex-1 rounded-2xl bg-emerald-400 py-4 text-[10px] font-black uppercase tracking-widest text-black"
                                >
                                    Apply_Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>
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

    .modal-enter-active,
    .modal-leave-active {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .modal-enter-from,
    .modal-leave-to {
        opacity: 0;
        transform: scale(0.9) translateY(20px);
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
