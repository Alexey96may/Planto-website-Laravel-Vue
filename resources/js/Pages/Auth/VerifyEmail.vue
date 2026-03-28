<script setup lang="ts">
    import { computed } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import { ArrowLeftStartOnRectangleIcon, PaperAirplaneIcon } from '@heroicons/vue/24/outline';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { Seo } from '@/types';

    type VerificationStatus = 'verification-link-sent' | 'already-verified' | '';

    const props = defineProps<{
        status?: VerificationStatus;
        seo?: Seo;
    }>();

    const form = useForm({});

    const submit = (): void => {
        form.post(route('verification.send'));
    };

    const verificationLinkSent = computed<boolean>(() => props.status === 'verification-link-sent');
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <GuestLayout>
        <div class="mb-8 text-center">
            <div
                class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-500/10 text-emerald-500"
            >
                <PaperAirplaneIcon class="h-8 w-8 -rotate-12" />
            </div>

            <h2 class="text-2xl font-bold text-zinc-100">Check your garden mail</h2>
            <p class="mt-2 text-sm leading-relaxed text-zinc-500">
                Thanks for signing up! Before getting started, could you verify your email address
                by clicking on the link we just emailed to you?
            </p>
        </div>

        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
        >
            <div
                v-if="verificationLinkSent"
                class="mb-6 rounded-2xl border border-emerald-500/20 bg-emerald-500/10 p-4 text-center text-sm font-medium text-emerald-500"
            >
                A new verification link has been sent to your email address.
            </div>
        </transition>

        <form @submit.prevent="submit" class="text-zinc-900">
            <div class="flex flex-col gap-4">
                <PrimaryButton
                    class="w-full py-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Resend Verification Email
                </PrimaryButton>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="flex items-center justify-center gap-2 py-2 text-sm text-zinc-500 transition-colors hover:text-zinc-300"
                >
                    <ArrowLeftStartOnRectangleIcon class="h-4 w-4" />
                    Log Out
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>
