<script setup lang="ts">
    import { ref, watch } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import { Mail, Save, User } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import ImageUploader from '@/Components/UI/ImageUploader.vue';
    import { useSound } from '@/composables/useSound';
    import { AuthProps, UserForm } from '@/types';

    const props = defineProps<{
        auth: AuthProps;
    }>();

    const { playClick, playCancel, playSuccess } = useSound();

    const formAvatar = useForm<UserForm>({
        name: props.auth.user.name,
        email: props.auth.user.email,
        avatar: null,
        _method: 'patch',
    });

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

    const handleFileSelect = (file: File | null) => {
        formAvatar.avatar = file;
        if (file) {
            imageUrl.value = URL.createObjectURL(file);
        } else {
            imageUrl.value = props.auth.user.avatar_url;
        }
    };
</script>

<template>
    <section
        class="group relative z-[3] mb-16 overflow-hidden rounded-[1rem] border border-emerald-400/50 p-4 backdrop-blur-xl md:p-8 lg:rounded-[2rem]"
    >
        <form @submit.prevent="submitInfo" class="relative z-10 space-y-8">
            <div class="flex flex-col items-start gap-12 lg:flex-row">
                <div class="group/avatar relative mx-auto shrink-0 lg:w-48">
                    <ImageUploader
                        ref="uploader"
                        v-model="formAvatar.avatar"
                        label="Avatar"
                        :error="formAvatar.errors.avatar"
                        :existingImage="imageUrl"
                        @on-file-select="handleFileSelect"
                    />
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
                                <div
                                    v-if="formAvatar.errors.name"
                                    class="mt-1 text-xs text-red-400"
                                >
                                    {{ formAvatar.errors.name }}
                                </div>
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
                                <div
                                    v-if="formAvatar.errors.email"
                                    class="mt-1 text-xs text-red-400"
                                >
                                    {{ formAvatar.errors.email }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2 border-t border-white/5 pt-6">
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
</template>
