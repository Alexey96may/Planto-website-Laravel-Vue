<script setup lang="ts">
    import { onMounted } from 'vue';

    import { Head, Link } from '@inertiajs/vue3';

    import { ArrowRightIcon, CheckCircleIcon, ShoppingBagIcon } from '@heroicons/vue/24/outline';

    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useSound } from '@/composables/useSound';

    const props = defineProps<{
        sessionId: string;
    }>();

    const { playSlideMove } = useSound();

    defineOptions({ layout: MainLayout });

    onMounted(() => {
        playSlideMove();
    });
</script>

<template>
    <Head title="Order Confirmed" />

    <div
        class="flex min-h-[80vh] items-center justify-center bg-plant-shop px-6 py-16 lg:py-24"
        role="main"
        aria-labelledby="success-heading"
    >
        <div class="w-full max-w-2xl text-center">
            <div class="mb-8 flex justify-center">
                <div class="relative" aria-hidden="true">
                    <div class="absolute inset-0 animate-ping rounded-full bg-emerald-500/20"></div>
                    <CheckCircleIcon class="relative h-20 w-20 text-emerald-500" />
                </div>
            </div>

            <header class="space-y-4">
                <h1
                    id="success-heading"
                    class="text-4xl font-bold tracking-tight text-zinc-100 lg:text-6xl"
                >
                    Order <span class="text-emerald-500">Confirmed!</span>
                </h1>
                <p class="mx-auto max-w-md text-lg text-zinc-400">
                    Thank you for your purchase. Your new green friends are being prepared for their
                    journey to your home.
                </p>
            </header>

            <section
                class="mt-12 rounded-[2rem] border border-zinc-800 bg-zinc-900/50 p-6 backdrop-blur-sm"
                aria-label="Order Details"
            >
                <div class="flex flex-col items-center space-y-2">
                    <span class="text-xs font-semibold uppercase tracking-widest text-zinc-500">
                        Payment Session ID
                    </span>
                    <code
                        class="break-all rounded-lg bg-zinc-950 px-4 py-2 font-mono text-sm text-emerald-400"
                        aria-label="Your session ID is {{ sessionId }}"
                    >
                        {{ sessionId }}
                    </code>
                </div>

                <p class="mt-6 text-sm text-zinc-500">
                    A confirmation email has been sent to your inbox with full order details and
                    tracking information.
                </p>
            </section>

            <nav
                class="mt-12 flex flex-col items-center justify-center gap-4 sm:flex-row"
                aria-label="Post-purchase navigation"
            >
                <Link
                    href="/my-orders"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-zinc-800 px-8 py-4 font-bold text-white transition-all hover:bg-zinc-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-zinc-500/50 sm:w-auto"
                    aria-label="View your order history"
                >
                    <ShoppingBagIcon class="h-5 w-5" />
                    View Orders
                </Link>

                <Link
                    href="/shop"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-emerald-600 px-8 py-4 font-bold text-white transition-all hover:bg-emerald-500 focus:outline-none focus-visible:ring-4 focus-visible:ring-emerald-500/50 sm:w-auto"
                    aria-label="Continue shopping for more plants"
                >
                    Continue Shopping
                    <ArrowRightIcon class="h-5 w-5" />
                </Link>
            </nav>

            <p class="mt-12 text-sm text-zinc-600">
                Having issues?
                <Link
                    href="/contact"
                    class="text-emerald-500 hover:underline"
                    aria-label="Contact our support team"
                >
                    Contact our support
                </Link>
            </p>
        </div>
    </div>
</template>

<style scoped>
    div[role='main'] {
        animation: fadeInScale 0.6s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInScale {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(10px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
</style>
