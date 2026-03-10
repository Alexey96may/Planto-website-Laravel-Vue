<script setup lang="ts">
    import { Link, useForm, usePage } from '@inertiajs/vue3';

    import { CheckCircleIcon, MailIcon, UserIcon } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';
    import { User } from '@/types';

    const props = defineProps<{
        mustVerifyEmail?: boolean;
        status?: string;
    }>();

    const user = (usePage().props.auth as { user: User }).user;

    const form = useForm({
        name: user.name,
        email: user.email,
    });
</script>

<template>
    <section>
        <header class="flex items-center gap-4">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c5d86d]/10 text-[#c5d86d]"
            >
                <UserIcon class="h-5 w-5" />
            </div>
            <div>
                <h2 class="text-lg font-bold text-zinc-100">Profile Information</h2>
                <p class="mt-1 text-sm text-zinc-500">
                    Update your account's profile information and email address.
                </p>
            </div>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-8 space-y-6">
            <div class="max-w-md">
                <InputLabel for="name" value="Your Name" class="text-zinc-400" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Enter your name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="max-w-md">
                <InputLabel for="email" value="Email Address" class="text-zinc-400" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    placeholder="name@example.com"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div
                v-if="mustVerifyEmail && user.email_verified_at === null"
                class="max-w-md rounded-2xl border border-amber-500/10 bg-amber-500/5 p-4"
            >
                <div class="flex gap-3">
                    <MailIcon class="h-5 w-5 shrink-0 text-amber-500" />
                    <div>
                        <p class="text-sm text-zinc-300">Your email address is unverified.</p>
                        <Link
                            :href="route('verification.send')"
                            method="post"
                            as="button"
                            class="mt-2 text-xs font-bold uppercase tracking-widest text-amber-500 transition hover:text-amber-400 focus:outline-none"
                        >
                            Resend verification email
                        </Link>
                    </div>
                </div>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-3 flex items-center gap-2 text-sm font-medium text-emerald-500"
                >
                    <CheckCircleIcon class="h-4 w-4" />
                    Link sent to your inbox.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton
                    :disabled="form.processing"
                    class="px-8 shadow-lg shadow-emerald-900/20"
                >
                    Save Profile
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0 translate-x-[-10px]"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0 translate-x-[10px]"
                >
                    <div
                        v-if="form.recentlySuccessful"
                        class="flex items-center gap-2 text-sm font-medium text-emerald-500"
                    >
                        <CheckCircleIcon class="h-4 w-4" />
                        <span>Profile updated</span>
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>
