<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';

    interface RegisterForm {
        name: string;
        email: string;
        password: string;
        password_confirmation: string;
    }

    const form = useForm<RegisterForm>({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit = (): void => {
        form.post(route('register'), {
            onFinish: () => form.reset('password', 'password_confirmation'),
        });
    };
</script>

<template>
    <GuestLayout>
        <Head title="Join the Garden" />

        <div class="mb-8 text-center">
            <h2 class="text-2xl font-bold text-zinc-100 lg:text-3xl">Create Account</h2>
            <p class="mt-2 text-sm text-zinc-500">Join our community of plant lovers 🌿</p>
        </div>

        <form @submit.prevent="submit" class="space-y-5">
            <div>
                <InputLabel for="name" value="Full Name" class="text-zinc-400" />
                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    placeholder="John Doe"
                    autocomplete="name"
                />
                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div>
                <InputLabel for="email" value="Email Address" class="text-zinc-400" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    placeholder="name@example.com"
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <InputLabel for="password" value="Password" class="text-zinc-400" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
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
                    Create Account
                </PrimaryButton>
            </div>

            <div class="text-center text-sm text-zinc-500">
                Already part of the garden?
                <Link
                    :href="route('login')"
                    class="font-bold text-emerald-600 transition-colors hover:text-emerald-500"
                >
                    Log in here
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
