<script setup lang="ts">
    import { usePage } from '@inertiajs/vue3';

    import { Mail, MapPin, Phone, Share2 } from 'lucide-vue-next';

    import Mapbox from '@/Components/Shared/Mapbox.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { SharedData } from '@/types';

    type SocialKey = 'link_fb' | 'link_x' | 'link_li';

    const page = usePage<SharedData>();

    const getSocialLink = (platform: string): string | null => {
        const key = `link_${platform}` as SocialKey;
        return page.props.settings[key] || null;
    };

    defineOptions({
        layout: MainLayout,
    });
</script>

<template>
    <div
        class="min-h-screen bg-[#0b120c] px-6 py-20 font-sans text-white selection:bg-emerald-500/30 lg:py-32"
    >
        <div class="mx-auto max-w-6xl">
            <header class="relative z-[3] mb-20">
                <h1
                    class="font-serif text-6xl font-light italic tracking-tight md:text-8xl lg:text-9xl"
                >
                    Contact
                    <span
                        class="not-italic text-emerald-500 drop-shadow-[0_0_15px_rgba(16,185,129,0.3)]"
                        >Us</span
                    >
                </h1>
                <div class="mt-6 h-px w-24 bg-emerald-500/50"></div>
            </header>

            <div class="grid gap-16 xl:grid-cols-12">
                <div class="relative z-[3] space-y-16 xl:col-span-5">
                    <section>
                        <h2 class="mb-8 font-serif text-3xl italic opacity-90">
                            {{ $page.props.settings.site_name }}
                        </h2>

                        <div class="space-y-8">
                            <div
                                v-if="$page.props.settings?.contact_address"
                                class="group flex items-start gap-6"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-900/25 text-emerald-600 transition-colors group-hover:bg-emerald-900/80 group-hover:text-white"
                                >
                                    <MapPin class="h-5 w-5" />
                                </div>
                                <div>
                                    <p
                                        class="mb-1 text-xs font-black uppercase tracking-widest text-emerald-500/50"
                                    >
                                        Location
                                    </p>
                                    <p class="text-lg font-medium text-zinc-300">
                                        {{ $page.props.settings.contact_address }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="$page.props.settings?.contact_phone"
                                class="group flex items-start gap-6"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-900/25 text-emerald-400 transition-colors group-hover:bg-emerald-900/80 group-hover:text-white"
                                >
                                    <Phone class="h-5 w-5" />
                                </div>
                                <div>
                                    <p
                                        class="mb-1 text-xs font-black uppercase tracking-widest text-emerald-500/50"
                                    >
                                        Phone
                                    </p>
                                    <p class="text-lg font-medium text-zinc-300">
                                        {{ $page.props.settings.contact_phone }}
                                    </p>
                                </div>
                            </div>

                            <div
                                v-if="$page.props.settings?.contact_email"
                                class="group flex items-start gap-6"
                            >
                                <div
                                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl border border-emerald-500/20 bg-emerald-900/25 text-emerald-400 transition-colors group-hover:bg-emerald-900/80 group-hover:text-white"
                                >
                                    <Mail class="h-5 w-5" />
                                </div>
                                <div>
                                    <p
                                        class="mb-1 text-xs font-black uppercase tracking-widest text-emerald-500/50"
                                    >
                                        Email
                                    </p>
                                    <p class="text-lg font-medium text-zinc-300">
                                        {{ $page.props.settings.contact_email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="mb-8 flex items-center gap-4">
                            <Share2 class="h-5 w-5 text-emerald-500" />
                            <h3 class="text-sm font-black uppercase tracking-[0.3em] text-zinc-500">
                                Social Networks
                            </h3>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <a
                                v-for="social in ['fb', 'x', 'li']"
                                :key="social"
                                v-show="getSocialLink(social)"
                                :href="getSocialLink(social) ?? '#'"
                                class="flex h-14 w-14 items-center justify-center rounded-2xl border border-white/5 bg-emerald-900/30 text-[10px] font-black uppercase tracking-tighter transition-all hover:-translate-y-1 hover:border-emerald-500/50 hover:bg-emerald-500/10 hover:text-emerald-400"
                            >
                                {{ social }}
                            </a>
                        </div>
                    </section>
                </div>

                <div class="xl:col-span-7">
                    <Mapbox class="sticky top-12 h-[600px] overflow-hidden rounded-[2rem]" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
    .min-h-screen {
        background:
            radial-gradient(circle at 80% 20%, rgba(16, 185, 129, 0.08) 0%, transparent 40%),
            radial-gradient(circle at 20% 80%, rgba(5, 46, 22, 0.4) 0%, #0b120c 70%);
    }

    section {
        animation: fadeUp 1s ease-out forwards;
    }

    @keyframes fadeUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
