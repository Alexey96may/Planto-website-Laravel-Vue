<script setup lang="ts">
    import { Head, useForm } from '@inertiajs/vue3';

    import { KeyIcon } from '@heroicons/vue/24/outline';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { Seo } from '@/types';

    interface ResetPasswordForm {
        token: string;
        email: string;
        password: string;
        password_confirmation: string;
    }

    const props = defineProps<{
        email: string;
        token: string;
        seo?: Seo;
    }>();

    const form = useForm<ResetPasswordForm>({
        token: props.token,
        email: props.email,
        password: '',
        password_confirmation: '',
    });

    const submit = (): void => {
        form.post(route('password.store'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <GuestLayout>
        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
            >
                <KeyIcon class="h-8 w-8" />
            </div>
            <h2 class="text-2xl font-bold text-zinc-100">Set New Password</h2>
            <p class="mt-2 text-sm text-zinc-500">
                Almost there! Choose a strong password for your garden account.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-5 text-zinc-900">
            <div>
                <InputLabel for="email" value="Email Address" class="text-zinc-400" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full cursor-not-allowed opacity-60"
                    v-model="form.email"
                    required
                    readonly
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="password" value="New Password" class="text-zinc-400" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autofocus
                        placeholder="••••••••"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-1" :message="form.errors.password" />
                </div>

                <div>
                    <InputLabel for="password_confirmation" value="Confirm" class="text-zinc-400" />
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        placeholder="••••••••"
                        autocomplete="new-password"
                    />
                    <InputError class="mt-1" :message="form.errors.password_confirmation" />
                </div>
            </div>

            <div class="pt-4">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Update Password
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
