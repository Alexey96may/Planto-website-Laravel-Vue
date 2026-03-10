<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3';

    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';

    interface PasswordConfirmForm {
        password: string;
    }

    const form = useForm<PasswordConfirmForm>({
        password: '',
    });

    const submit = (): void => {
        form.post(route('password.confirm'), {
            onFinish: () => form.reset(),
        });
    };

    const goBack = () => {
        window.history.back();
    };
</script>

<template>
    <GuestLayout>
        <Head title="Secure Area" />

        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="h-8 w-8"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"
                    />
                </svg>
            </div>

            <h2 class="text-2xl font-bold text-zinc-100">Secure Area</h2>
            <p class="mt-2 text-sm text-zinc-500">
                This part of the garden is protected. Please confirm your password to continue.
            </p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="password" value="Your Password" class="text-zinc-400" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    placeholder="••••••••"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="flex flex-col gap-4">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm & Access
                </PrimaryButton>

                <Link
                    href="#"
                    @click.prevent="goBack"
                    class="text-sm text-zinc-500 hover:text-zinc-300"
                >
                    Nevermind, take me back
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
