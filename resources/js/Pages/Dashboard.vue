<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Head, useForm, usePage } from '@inertiajs/vue3';

    import {
        Camera,
        CheckCircle2,
        History,
        Mail,
        MessageSquare,
        Save,
        ShieldCheck,
        Star,
        User,
    } from 'lucide-vue-next';

    import AppImage from '@/Components/UI/AppImage.vue';
    import AppRating from '@/Components/UI/AppRating.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { AuthProps, Comment, CommentForm, UserForm } from '@/types';

    const props = defineProps<{
        myComments: Comment[];
        auth: AuthProps;
    }>();

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

    const imageUrl = ref<string | null>(props.auth.user.avatar_url);

    const onFileChange = (e: Event) => {
        const target = e.target as HTMLInputElement;
        const file = target.files?.[0];
        if (file) {
            formAvatar.avatar = file;
            if (imageUrl.value && imageUrl.value.startsWith('blob:')) {
                URL.revokeObjectURL(imageUrl.value);
            }
            imageUrl.value = URL.createObjectURL(file);
        }
    };

    const submitInfo = (): void => {
        formAvatar.post(route('profile.update'), {
            preserveScroll: true,
            onSuccess: () => {
                formAvatar.avatar = null;
            },
        });
    };

    const submitComment = (): void => {
        form.post(route('comments.update'), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
        });
    };

    const isAdmin = computed(() => props.auth.user.role === 'admin');
</script>

<template>
    <Head title="System Dashboard" />

    <AuthenticatedLayout>
        <div
            class="min-h-screen bg-plant-shop px-4 py-12 font-sans selection:bg-[#c5d86d] selection:text-black sm:px-6 lg:px-8"
        >
            <div class="mx-auto max-w-5xl space-y-8">
                <header class="mb-12 flex flex-col justify-between gap-4 md:flex-row md:items-end">
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
                                >Standard User</span
                            >
                        </div>
                        <h2
                            class="text-5xl font-black uppercase italic tracking-tighter text-white"
                        >
                            User <span class="text-[#c5d86d]">Core</span>
                        </h2>
                        <p class="font-medium tracking-tight text-zinc-500">
                            Accessing terminal for {{ formAvatar.name }}...
                        </p>
                    </div>

                    <div class="flex gap-4">
                        <div
                            class="min-w-[120px] rounded-2xl border border-white/5 bg-[#1a1f16] p-4"
                        >
                            <p class="mb-1 text-[10px] font-black uppercase text-zinc-600">
                                Feedback count
                            </p>
                            <p class="text-2xl font-black text-white">{{ myComments.length }}</p>
                        </div>
                    </div>
                </header>

                <section
                    class="group relative overflow-hidden rounded-[1rem] border border-white/5 bg-[#161b14] p-4 shadow-2xl md:rounded-[2rem] md:p-8"
                >
                    <div
                        class="absolute right-0 top-0 -mr-32 -mt-32 h-64 w-64 bg-[#c5d86d]/5 blur-[100px]"
                    ></div>

                    <form @submit.prevent="submitInfo" class="relative z-10 space-y-8">
                        <div class="flex flex-col items-start gap-12 lg:flex-row">
                            <div class="group/avatar relative mx-auto lg:mx-0">
                                <div
                                    class="h-40 w-40 overflow-hidden rounded-[1rem] border-2 border-white/5 shadow-2xl transition-all duration-500 group-hover/avatar:border-[#c5d86d]/50 md:rounded-[2rem]"
                                >
                                    <AppImage
                                        :src="imageUrl || ''"
                                        class="h-full w-full object-cover transition-transform duration-700 group-hover/avatar:scale-110"
                                    />
                                </div>
                                <label
                                    for="avatar-input"
                                    class="absolute -bottom-2 -right-2 cursor-pointer rounded-2xl bg-[#c5d86d] p-3 shadow-xl shadow-black/50 transition-transform hover:scale-110"
                                >
                                    <Camera class="h-5 w-5 text-black" />
                                    <input
                                        type="file"
                                        id="avatar-input"
                                        @change="onFileChange"
                                        accept="image/*"
                                        class="hidden"
                                    />
                                </label>
                            </div>

                            <div class="w-full flex-1 space-y-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                    <div class="space-y-2">
                                        <label
                                            class="ml-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                            >Identity Name</label
                                        >
                                        <div class="relative">
                                            <User
                                                class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                            />
                                            <input
                                                v-model="formAvatar.name"
                                                type="text"
                                                class="w-full rounded-2xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                            />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label
                                            class="ml-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                            >Secure Email</label
                                        >
                                        <div class="relative">
                                            <Mail
                                                class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                            />
                                            <input
                                                v-model="formAvatar.email"
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

                <div class="grid grid-cols-1 gap-8 italic lg:grid-cols-12">
                    <section
                        class="rounded-[1rem] border border-white/5 bg-[#161b14] p-4 shadow-2xl md:rounded-[2rem] md:p-8 lg:col-span-5"
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
                                class="w-full resize-none rounded-3xl border border-white/5 bg-black/40 p-5 text-sm text-white placeholder-zinc-700 outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                placeholder="Report your experience with the system..."
                            ></textarea>

                            <div class="rounded-2xl border border-white/5 bg-black/20 p-4">
                                <div class="mb-4 flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                        >Signal Rating</span
                                    >
                                    <div class="flex items-center gap-1 text-[#c5d86d]">
                                        <span class="text-lg font-black">{{ form.rating }}</span>
                                        <Star class="h-4 w-4 fill-current" />
                                    </div>
                                </div>
                                <input
                                    type="range"
                                    v-model="form.rating"
                                    min="1"
                                    max="5"
                                    step="0.5"
                                    class="h-1.5 w-full cursor-pointer appearance-none rounded-lg bg-zinc-800 accent-[#c5d86d]"
                                />
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing || !form.body"
                                class="w-full rounded-2xl bg-[#c5d86d] py-4 text-[10px] font-black uppercase tracking-widest text-black transition-all hover:scale-[1.02] active:scale-95"
                            >
                                Publish Transmission
                            </button>
                        </form>
                    </section>

                    <section
                        class="flex flex-col rounded-[1rem] border border-white/5 bg-[#161b14] p-4 shadow-2xl md:rounded-[2rem] md:p-8 lg:col-span-7"
                    >
                        <h3
                            class="mb-8 flex items-center gap-3 text-xs font-black uppercase tracking-[0.2em] text-white"
                        >
                            <History class="h-4 w-4 text-[#c5d86d]" /> Archive_Log
                        </h3>

                        <div class="custom-scrollbar max-h-[400px] space-y-4 overflow-y-auto pr-2">
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
                                class="group relative rounded-[1.5rem] border border-white/5 bg-black/40 p-5 transition-all hover:border-[#c5d86d]/30"
                            >
                                <div class="mb-3 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <AppRating :rating="comment.rating" />
                                        <span class="text-[1rem] text-emerald-500"
                                            >({{ comment.rating }})</span
                                        >
                                    </div>
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
                                <p class="text-xs font-medium leading-relaxed text-zinc-400">
                                    {{ comment.body }}
                                </p>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(197, 216, 109, 0.2);
        border-radius: 10px;
    }
</style>
