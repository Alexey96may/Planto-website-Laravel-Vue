<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import Checkbox from '@/Components/UI/Checkbox.vue';
    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { Seo } from '@/types';

    interface ConfirmForm {
        email: string;
        password: string;
        remember: boolean;
    }

    type Props = {
        canResetPassword?: boolean;
        seo?: Seo;
        status?: string;
    };

    const props = withDefaults(defineProps<Props>(), {
        canResetPassword: false,
        status: '',
    });

    const form = useForm<ConfirmForm>({
        email: '',
        password: '',
        remember: false,
    });

    const submit = (): void => {
        form.post(route('login'), {
            onFinish: () => form.reset('password'),
        });
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <GuestLayout>
        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-zinc-100">Welcome back</h2>
            <p class="mt-2 text-sm text-zinc-500">Please enter your details to sign in</p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-emerald-500">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email Address" class="text-zinc-400" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full border-zinc-700 bg-zinc-800/50 text-zinc-200 focus:border-emerald-500 focus:ring-emerald-500/20"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="name@example.com"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div>
                <div class="flex items-center justify-between gap-2">
                    <InputLabel for="password" value="Password" class="text-zinc-400" />

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-xs text-emerald-600 transition-colors hover:text-emerald-500"
                    >
                        Forgot password?
                    </Link>
                </div>

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full border-zinc-700 bg-zinc-800/50 text-zinc-200 focus:border-emerald-500 focus:ring-emerald-500/20"
                    v-model="form.password"
                    required
                    placeholder="••••••••"
                    autocomplete="current-password"
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex items-center justify-between">
                <label class="group flex cursor-pointer items-center" for="remember">
                    <Checkbox
                        name="remember"
                        id="remember"
                        v-model:checked="form.remember"
                        class="border-zinc-700 bg-zinc-800 text-emerald-600 focus:ring-emerald-500/20"
                    />
                    <span
                        class="ms-2 text-sm text-zinc-500 transition-colors group-hover:text-zinc-400"
                        >Remember me</span
                    >
                </label>
            </div>

            <div class="pt-2">
                <PrimaryButton
                    class="w-full justify-center rounded-xl bg-emerald-600 py-3 text-white shadow-[0_10px_20px_-10px_rgba(16,185,129,0.3)] transition-all hover:bg-emerald-500 active:scale-[0.98]"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>

            <p class="text-center text-sm text-zinc-500">
                Don't have an account?
                <Link
                    :href="route('register')"
                    class="font-bold text-emerald-600 hover:text-emerald-500"
                >
                    Sign up
                </Link>
            </p>
        </form>
    </GuestLayout>
</template>
