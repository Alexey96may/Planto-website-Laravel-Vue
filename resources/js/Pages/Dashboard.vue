<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, useForm } from '@inertiajs/vue3';

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
                imageUrl.value = null;
            },
            onError: (errors) => {
                console.error('Error updating profile:', errors);
            },
        });
    };

    const submitComment = (): void => {
        form.post(route('comments.update'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            },
            onError: (errors) => {
                console.log('Oops, something went wrong with the comment.', errors);
            },
        });
    };
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-[#1a1f16] text-gray-200 py-12 px-4 sm:px-6 lg:px-8 font-sans">
            <div class="max-w-4xl mx-auto space-y-8">
                <header class="text-center mb-12">
                    <h2 class="text-4xl font-bold text-white tracking-tight">Breath Natureal</h2>
                    <p class="mt-2 text-gray-400">Welcome to Planto Shop, {{ formAvatar.name }}!</p>
                </header>

                <section
                    class="bg-[#242b1f] rounded-[2rem] p-8 shadow-xl border border-white/5 reveal"
                >
                    <form @submit.prevent="submitInfo" class="space-y-6">
                        <div class="flex flex-col items-center sm:flex-row gap-8">
                            <div class="relative group">
                                <img
                                    :src="imageUrl"
                                    class="w-32 h-32 rounded-3xl object-cover border-2 border-[#c5d86d]/30 group-hover:border-[#c5d86d] transition-all duration-300"
                                />
                                <div
                                    class="absolute -inset-1 bg-[#c5d86d]/20 rounded-3xl blur opacity-0 group-hover:opacity-100 transition"
                                ></div>
                            </div>

                            <div class="flex-1 space-y-4 w-full">
                                <div class="flex flex-col gap-2">
                                    <input
                                        type="file"
                                        id="avatar-input"
                                        @change="onFileChange"
                                        accept="image/*"
                                        class="hidden"
                                    />
                                    <label
                                        for="avatar-input"
                                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-[#c5d86d] text-[#1a1f16] font-bold hover:bg-[#b3c65a] cursor-pointer transition"
                                    >
                                        Edit photo
                                    </label>
                                    <p v-if="formAvatar.errors.avatar" class="text-red-400 text-xs">
                                        {{ formAvatar.errors.avatar }}
                                    </p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-1">
                                        <label
                                            class="text-xs uppercase tracking-widest text-gray-500 font-bold"
                                            >Имя</label
                                        >
                                        <input
                                            v-model="formAvatar.name"
                                            type="text"
                                            class="w-full bg-[#1a1f16] border-none rounded-xl focus:ring-2 focus:ring-[#c5d86d] text-white"
                                        />
                                        <p
                                            v-if="formAvatar.errors.name"
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{ formAvatar.errors.name }}
                                        </p>
                                    </div>
                                    <div class="space-y-1">
                                        <label
                                            class="text-xs uppercase tracking-widest text-gray-500 font-bold"
                                            >Email</label
                                        >
                                        <input
                                            v-model="formAvatar.email"
                                            type="email"
                                            class="w-full bg-[#1a1f16] border-none rounded-xl focus:ring-2 focus:ring-[#c5d86d] text-white"
                                        />
                                        <p
                                            v-if="formAvatar.errors.email"
                                            class="text-red-500 text-xs mt-1"
                                        >
                                            {{ formAvatar.errors.email }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-white/5 flex flex-col items-center gap-4">
                            <button
                                type="submit"
                                :disabled="formAvatar.processing"
                                class="w-full sm:w-auto px-12 py-3 bg-white/10 hover:bg-white/20 text-white rounded-xl font-bold transition disabled:opacity-50"
                            >
                                {{ formAvatar.processing ? 'Saving...' : 'Save settings' }}
                            </button>
                            <Transition
                                enter-active-class="transition"
                                enter-from-class="opacity-0"
                            >
                                <p
                                    v-if="formAvatar.recentlySuccessful"
                                    class="text-[#c5d86d] text-sm"
                                >
                                    ✨ Данные успешно обновлены!
                                </p>
                            </Transition>
                        </div>
                    </form>
                </section>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <section
                        class="bg-[#242b1f] rounded-[2rem] p-8 shadow-xl border border-white/5 reveal"
                    >
                        <h3 class="text-xl font-bold text-white mb-6">Feedback</h3>
                        <form @submit.prevent="submitComment" class="space-y-4">
                            <textarea
                                v-model="form.body"
                                :disabled="form.processing"
                                rows="3"
                                class="w-full bg-[#1a1f16] border-none rounded-2xl focus:ring-2 focus:ring-[#c5d86d] text-white placeholder-gray-600"
                                placeholder="What do you think about Planto?"
                            ></textarea>

                            <div class="space-y-2">
                                <div
                                    class="flex justify-between text-xs font-bold text-gray-500 uppercase"
                                >
                                    <span>Rating</span>
                                    <span class="text-[#c5d86d] text-base"
                                        >{{ form.rating }} ⭐</span
                                    >
                                </div>
                                <input
                                    type="range"
                                    v-model="form.rating"
                                    :disabled="form.processing"
                                    min="0.5"
                                    max="5"
                                    step="0.5"
                                    class="w-full h-1.5 bg-[#1a1f16] rounded-lg appearance-none cursor-pointer accent-[#c5d86d]"
                                />
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing || !form.body"
                                class="w-full py-3 bg-[#c5d86d] text-[#1a1f16] font-bold rounded-xl hover:bg-[#b3c65a] transition"
                            >
                                {{ formAvatar.processing ? 'Publication...' : 'Publish' }}
                            </button>
                        </form>
                    </section>

                    <section
                        class="bg-[#242b1f] rounded-[2rem] p-8 shadow-xl border border-white/5 overflow-y-auto max-h-[400px] reveal"
                    >
                        <h3 class="text-xl font-bold text-white mb-6">My reviews</h3>
                        <div v-if="myComments.length === 0" class="text-gray-500 text-center py-8">
                            Пока пусто...
                        </div>

                        <div
                            v-for="comment in myComments"
                            :key="comment.id"
                            class="mb-4 p-4 bg-[#1a1f16] rounded-2xl border border-white/5"
                        >
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-[#c5d86d] font-bold"
                                    >{{ comment.rating }} ⭐</span
                                >
                                <span
                                    class="text-[10px] px-2 py-1 rounded-full border border-white/10 text-gray-400 uppercase tracking-tighter"
                                >
                                    {{ comment.is_active ? 'Ok' : 'Wait' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-300 leading-relaxed">{{ comment.body }}</p>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
