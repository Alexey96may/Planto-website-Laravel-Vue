<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { useScrollReveal } from '@/composables/useScrollReveal';
    import { Seo } from '@/types';

    interface Props {
        seo: Seo;
    }

    const props = defineProps<Props>();

    defineOptions({
        layout: MainLayout,
    });

    useScrollReveal();
</script>

<template>
    <SeoMeta :seo="props.seo" />
    <div
        class="min-h-screen bg-[#0b120c] px-6 py-20 font-sans text-white selection:bg-green-500/30"
        role="main"
    >
        <div class="mx-auto max-w-4xl">
            <div class="reveal mb-16 border-b border-white/10 pb-10">
                <h1 class="mb-4 text-4xl font-bold md:text-6xl">
                    Terms of <span class="text-green-500">Service</span>
                </h1>
                <p class="text-sm uppercase tracking-[0.2em] text-gray-500">
                    Last updated: <time datetime="2026-02-24">February 24, 2026</time>
                </p>
            </div>

            <div
                class="space-y-12 rounded-[40px] border border-white/10 bg-white/5 p-8 leading-relaxed text-gray-300 backdrop-blur-md md:p-12"
            >
                <section class="reveal" aria-labelledby="section-01">
                    <h2
                        id="section-01"
                        class="mb-4 flex items-center gap-3 text-2xl font-semibold text-white"
                    >
                        <span class="text-green-500" aria-hidden="true">01.</span> General
                        Provisions
                    </h2>
                    <p>
                        Welcome to Planto. By using our website, you agree to these terms and
                        conditions. We ask that you read them carefully, as they govern your rights
                        and responsibilities when purchasing plants and accessories.
                    </p>
                </section>

                <section class="reveal" aria-labelledby="section-02">
                    <h2
                        id="section-02"
                        class="mb-4 flex items-center gap-3 text-2xl font-semibold text-white"
                    >
                        <span class="text-green-500" aria-hidden="true">02.</span> Delivery terms
                    </h2>
                    <p>
                        We care for every plant. Delivery is carried out by specialized courier
                        services. Planto is responsible for the condition of the plant until it is
                        delivered to the buyer.
                    </p>
                </section>

                <section class="reveal" aria-labelledby="section-03">
                    <h2
                        id="section-03"
                        class="mb-4 flex items-center gap-3 text-2xl font-semibold text-white"
                    >
                        <span class="text-green-500" aria-hidden="true">03.</span> Return policy
                    </h2>
                    <p>
                        Live plants are perishable goods. Returns are only possible if obvious
                        defects or diseases are discovered upon receipt of the order.
                    </p>
                </section>

                <section class="reveal" aria-labelledby="section-04">
                    <h2
                        id="section-04"
                        class="mb-4 flex items-center gap-3 text-2xl font-semibold text-white"
                    >
                        <span class="text-green-500" aria-hidden="true">04.</span> Intellectual
                        property
                    </h2>
                    <p>
                        All content, including plant photographs and descriptions, is the property
                        of Planto. Use of materials without our consent is prohibited.
                    </p>
                </section>

                <div
                    class="flex items-center justify-between border-t border-white/5 pt-8"
                    role="group"
                    aria-label="Footer actions"
                >
                    <p class="text-sm text-gray-500">
                        Any questions?
                        <Link
                            href="/contacts"
                            class="text-green-500 hover:underline"
                            aria-label="Write to us if you have any questions"
                            >Write to us</Link
                        >
                    </p>
                    <Link
                        href="/"
                        class="rounded-full bg-white px-8 py-3 font-bold text-black transition-colors duration-300 hover:bg-green-500 hover:text-white"
                        aria-label="Agree to terms and return to home page"
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
