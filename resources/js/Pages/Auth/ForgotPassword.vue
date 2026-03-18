<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3';

    import { ArrowLeftIcon, EnvelopeIcon } from '@heroicons/vue/24/outline';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { Seo } from '@/types';

    interface Props {
        seo?: Seo;
        status?: string;
    }

    interface EmailConfirmForm {
        email: string;
    }

    const props = defineProps<Props>();

    const form = useForm<EmailConfirmForm>({
        email: '',
    });

    const submit = () => {
        form.post(route('password.email'));
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <GuestLayout>
        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
            >
                <EnvelopeIcon class="h-8 w-8" />
            </div>

            <h2 class="text-2xl font-bold text-zinc-100">Forgot Password?</h2>
            <p class="mt-2 text-sm text-zinc-500">
                No worries! Just enter your email and we'll send you a recovery link.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-6 animate-pulse rounded-xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-center text-sm font-medium text-emerald-500"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="email" value="Email Address" class="text-zinc-400" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autofocus
                    placeholder="your@email.com"
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex flex-col gap-4">
                <PrimaryButton
                    class="w-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Send Recovery Link
                </PrimaryButton>

                <Link
                    :href="route('login')"
                    class="flex items-center justify-center gap-2 text-sm text-zinc-500 transition-colors hover:text-emerald-500"
                >
                    <ArrowLeftIcon class="h-4 w-4" />
                    Back to login
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
