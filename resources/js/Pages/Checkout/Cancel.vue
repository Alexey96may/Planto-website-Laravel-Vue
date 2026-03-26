<script setup lang="ts">
    import { onMounted } from 'vue';

    import { Head, Link } from '@inertiajs/vue3';

    import {
        ArrowLeftIcon,
        QuestionMarkCircleIcon,
        ShoppingCartIcon,
        XCircleIcon,
    } from '@heroicons/vue/24/outline';

    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useSound } from '@/composables/useSound';

    const { playCancel } = useSound();

    defineOptions({ layout: MainLayout });

    onMounted(() => {
        playCancel();
    });
</script>

<template>
    <Head title="Payment Cancelled" />

    <div
        class="flex min-h-[80vh] items-center justify-center px-6 py-16 lg:py-24"
        role="main"
        aria-labelledby="cancel-heading"
    >
        <div class="w-full max-w-2xl text-center">
            <div class="mb-8 flex justify-center">
                <div
                    class="rounded-full border border-amber-500/20 bg-amber-500/10 p-4"
                    aria-hidden="true"
                >
                    <XCircleIcon class="h-16 w-16 text-amber-500" />
                </div>
            </div>

            <header class="space-y-4">
                <h1
                    id="cancel-heading"
                    class="text-4xl font-bold tracking-tight text-zinc-100 lg:text-6xl"
                >
                    Payment <span class="text-amber-500">Cancelled</span>
                </h1>
                <p class="mx-auto max-w-md text-lg text-zinc-400">
                    No worries! Your order has not been processed and no funds were debited. Your
                    plants are still waiting for you in the cart.
                </p>
            </header>

            <section
                class="mt-12 rounded-[2rem] border border-zinc-800 bg-zinc-900/30 p-8 text-left backdrop-blur-sm"
                aria-label="Common reasons for cancellation"
            >
                <h2
                    class="mb-4 flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-zinc-200"
                >
                    <QuestionMarkCircleIcon class="h-5 w-5 text-zinc-500" />
                    What happened?
                </h2>
                <ul class="space-y-3 text-sm text-zinc-500">
                    <li class="flex gap-3">
                        <span class="text-amber-500">•</span>
                        The payment process was interrupted manually.
                    </li>
                    <li class="flex gap-3">
                        <span class="text-amber-500">•</span>
                        The bank declined the transaction (insufficient funds or limit).
                    </li>
                    <li class="flex gap-3">
                        <span class="text-amber-500">•</span>
                        The checkout session timed out.
                    </li>
                </ul>
            </section>

            <nav
                class="mt-12 flex flex-col items-center justify-center gap-4 sm:flex-row"
                aria-label="Cancelled payment options"
            >
                <Link
                    :href="route('cart.index')"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-zinc-800 px-8 py-4 font-bold text-white transition-all hover:bg-zinc-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-zinc-500/50 sm:w-auto"
                    aria-label="Return to your shopping cart"
                >
                    <ShoppingCartIcon class="h-5 w-5" />
                    Back to Cart
                </Link>

                <Link
                    :href="route('shop')"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full border border-zinc-700 px-8 py-4 font-bold text-zinc-300 transition-all hover:bg-zinc-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-zinc-500/50 sm:w-auto"
                    aria-label="Go back to the shop"
                >
                    <ArrowLeftIcon class="h-5 w-5" />
                    Return to Shop
                </Link>
            </nav>

            <p class="mt-12 text-sm text-zinc-600">
                Need help with your payment?
                <Link
                    href="/contact"
                    class="text-amber-600 hover:underline"
                    aria-label="Contact support for payment assistance"
                >
                    Contact support
                </Link>
            </p>
        </div>
    </div>
</template>

<style scoped>
    div[role='main'] {
        animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
