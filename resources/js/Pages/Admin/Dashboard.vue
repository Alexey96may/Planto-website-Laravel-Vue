<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        FolderTreeIcon,
        MapIcon,
        MessageSquareIcon,
        PackageIcon,
        PlusIcon,
        SendIcon,
        ShoppingCartIcon,
        UsersIcon,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';

    interface Stats {
        products_count: number;
        users_count: number;
    }

    const props = defineProps<{
        stats: Stats;
    }>();

    const form = useForm({ subject: '', message: '' });

    const navigationCards = [
        {
            name: 'Products',
            route: 'admin.products.index',
            stats: props.stats.products_count,
            icon: PackageIcon,
            color: 'text-emerald-500',
            bg: 'bg-emerald-500/10',
            desc: 'Manage inventory',
        },
        {
            name: 'Categories',
            route: 'admin.categories.index',
            icon: FolderTreeIcon,
            color: 'text-purple-500',
            bg: 'bg-purple-500/10',
            desc: 'Organize plants',
        },
        {
            name: 'Orders',
            route: 'admin.orders.index',
            icon: ShoppingCartIcon,
            color: 'text-blue-500',
            bg: 'bg-blue-500/10',
            desc: 'Check sales',
        },
        {
            name: 'Comments',
            route: 'admin.comments.index',
            icon: MessageSquareIcon,
            color: 'text-amber-500',
            bg: 'bg-amber-500/10',
            desc: 'User reviews',
            badge: true,
        },
        {
            name: 'Users',
            route: 'admin.users.index',
            stats: props.stats.users_count,
            icon: UsersIcon,
            color: 'text-cyan-500',
            bg: 'bg-cyan-500/10',
            desc: 'Customer list',
        },
        {
            name: 'Navigation',
            route: 'admin.navigation.index',
            icon: MapIcon,
            color: 'text-zinc-400',
            bg: 'bg-zinc-400/10',
            desc: 'Menu structure',
        },
    ];
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />

        <div class="space-y-10">
            <div
                class="relative overflow-hidden rounded-[2rem] border border-white/5 bg-gradient-to-r from-[#1a2315] to-[#242b1f] p-8 shadow-2xl"
            >
                <div
                    class="relative z-10 flex flex-col items-center justify-between gap-6 md:flex-row"
                >
                    <div>
                        <h2 class="text-2xl font-black uppercase italic tracking-tight text-white">
                            Quick Action
                        </h2>
                        <p class="mt-1 text-zinc-400">Ready to expand your green collection?</p>
                    </div>
                    <Link
                        :href="route('admin.products.create')"
                        class="flex items-center gap-2 rounded-2xl bg-[#c5d86d] px-8 py-4 font-black text-[#0f120e] shadow-xl shadow-[#c5d86d]/20 transition-all hover:scale-105 hover:bg-[#d4e685] active:scale-95"
                    >
                        <PlusIcon class="h-5 w-5" />
                        ADD NEW PRODUCT
                    </Link>
                </div>
                <div class="absolute -right-10 -top-10 h-40 w-40 bg-[#c5d86d]/10 blur-[80px]"></div>
            </div>

            <div>
                <h1
                    class="mb-8 text-4xl font-black uppercase italic tracking-tighter text-white underline decoration-[white] decoration-4 underline-offset-8"
                >
                    Overview
                </h1>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 2xl:grid-cols-3">
                    <Link
                        v-for="card in navigationCards"
                        :key="card.name"
                        :href="route(card.route)"
                        class="group relative flex flex-col justify-stretch gap-4 rounded-[2rem] border border-white/5 bg-[#161b14]/60 p-8 backdrop-blur-sm transition-all duration-300 hover:border-[#c5d86d]/30"
                    >
                        <div
                            :class="[card.bg, card.color]"
                            class="flex h-14 w-14 items-center justify-center rounded-2xl transition-transform duration-500 group-hover:scale-110"
                        >
                            <component :is="card.icon" class="h-6 w-6" />
                        </div>

                        <div class="flex items-center gap-2">
                            <h2 class="text-xl font-bold text-white">{{ card.name }}</h2>
                            <span
                                v-if="card.badge && $page.props.pending_comments_count"
                                class="h-2 w-2 animate-ping rounded-full bg-red-500"
                            ></span>
                        </div>

                        <p class="text-sm text-zinc-500">
                            {{ card.desc }}
                            <span v-if="card.stats" class="font-mono italic text-zinc-300"
                                >({{ card.stats }})</span
                            >
                        </p>

                        <div
                            class="mt-auto flex items-center text-xs font-bold uppercase tracking-widest transition-all"
                            :class="card.color"
                        >
                            Go to {{ card.name }}
                            <span class="ml-2 transition-transform group-hover:translate-x-2"
                                >→</span
                            >
                        </div>
                    </Link>
                </div>
            </div>

            <div class="rounded-[2rem] border border-white/5 bg-[#161b14]/60 p-10 backdrop-blur-sm">
                <div class="mb-8 flex items-center gap-4">
                    <div class="rounded-xl bg-blue-500/10 p-3 text-blue-500">
                        <SendIcon class="h-6 w-6" />
                    </div>
                    <h2 class="text-2xl font-bold uppercase tracking-tight text-white">
                        Create Newsletter
                    </h2>
                </div>

                <form
                    @submit.prevent="form.post(route('admin.newsletter.broadcast'))"
                    class="grid grid-cols-1 gap-8 lg:grid-cols-2"
                >
                    <div class="space-y-6">
                        <div>
                            <label
                                class="mb-2 ml-1 block text-xs font-black uppercase tracking-widest text-zinc-500"
                                >Email Subject</label
                            >
                            <input
                                v-model="form.subject"
                                type="text"
                                class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:border-transparent focus:ring-2 focus:ring-[#c5d86d]"
                                placeholder="Summer sale is coming..."
                            />
                        </div>
                        <button
                            :disabled="form.processing"
                            class="flex w-full items-center justify-center gap-3 rounded-2xl bg-blue-600 px-10 py-4 font-black text-white shadow-xl shadow-blue-600/20 transition-all hover:bg-blue-500 disabled:opacity-50 lg:w-auto"
                        >
                            <SendIcon v-if="!form.processing" class="h-5 w-5" />
                            {{ form.processing ? 'SENDING...' : 'BROADCAST TO ALL' }}
                        </button>
                    </div>

                    <div>
                        <label
                            class="mb-2 ml-1 block text-xs font-black uppercase tracking-widest text-zinc-500"
                            >Message Body</label
                        >
                        <textarea
                            v-model="form.message"
                            rows="5"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:border-transparent focus:ring-2 focus:ring-[#c5d86d]"
                            placeholder="Tell your subscribers something beautiful..."
                        ></textarea>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
