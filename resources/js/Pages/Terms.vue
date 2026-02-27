<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useScrollReveal } from '@/composables/useScrollReveal';

    defineOptions({
        layout: MainLayout,
    });

    useScrollReveal();
</script>

<template>
    <div
        class="min-h-screen bg-[#0b120c] text-white py-20 px-6 font-sans selection:bg-green-500/30"
    >
        <div class="max-w-4xl mx-auto">
            <div class="mb-16 border-b border-white/10 pb-10 reveal">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Terms of <span class="text-green-500">Service</span>
                </h1>
                <p class="text-gray-500 uppercase tracking-[0.2em] text-sm">
                    Last updated: February 24, 2026
                </p>
            </div>

            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-[40px] p-8 md:p-12 space-y-12 text-gray-300 leading-relaxed"
            >
                <section class="reveal">
                    <h2 class="text-2xl font-semibold text-white mb-4 flex items-center gap-3">
                        <span class="text-green-500">01.</span> General Provisions
                    </h2>
                    <p>
                        Welcome to Planto. By using our website, you agree to these terms and
                        conditions. We ask that you read them carefully, as they govern your rights
                        and responsibilities when purchasing plants and accessories.
                    </p>
                </section>

                <section class="reveal">
                    <h2 class="text-2xl font-semibold text-white mb-4 flex items-center gap-3">
                        <span class="text-green-500">02.</span> Delivery terms
                    </h2>
                    <p>
                        We care for every plant. Delivery is carried out by specialized courier
                        services. Planto is responsible for the condition of the plant until it is
                        delivered to the buyer.
                    </p>
                </section>

                <section class="reveal">
                    <h2 class="text-2xl font-semibold text-white mb-4 flex items-center gap-3">
                        <span class="text-green-500">03.</span> Return policy
                    </h2>
                    <p>
                        Live plants are perishable goods. Returns are only possible if obvious
                        defects or diseases are discovered upon receipt of the order.
                    </p>
                </section>

                <section class="reveal">
                    <h2 class="text-2xl font-semibold text-white mb-4 flex items-center gap-3">
                        <span class="text-green-500">04.</span> Intellectual property
                    </h2>
                    <p>
                        All content, including plant photographs and descriptions, is the property
                        of Planto. Use of materials without our consent is prohibited.
                    </p>
                </section>

                <div class="pt-8 border-t border-white/5 flex justify-between items-center">
                    <p class="text-sm text-gray-500">
                        Any questions?
                        <Link href="/contacts" class="text-green-500 hover:underline"
                            >Write to us</Link
                        >
                    </p>
                    <Link
                        href="/"
                        class="px-8 py-3 bg-white text-black rounded-full font-bold hover:bg-green-500 hover:text-white transition-colors duration-300"
                    >
                        Agree
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .min-h-screen {
        background: radial-gradient(circle at bottom left, #122214 0%, #0b120c 40%);
    }

    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
    }

    .reveal-visible {
        opacity: 1;
        transform: translateY(0);
    }
</style>
