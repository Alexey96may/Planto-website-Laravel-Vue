<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';

    import { ArrowUpRight, Clock, ShoppingBag } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { OrderStatus, OrderWithUser, Seo } from '@/types';
    import { formatUSD } from '@/utils/money';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        orders: OrderWithUser[];
        seo?: Seo;
    }>();

    const getStatusStyles = (status: OrderStatus): string => {
        const styles: Record<OrderStatus, string> = {
            new: 'bg-blue-500/10 text-blue-400 border-blue-500/20 shadow-[0_0_15px_rgba(59,130,246,0.1)]',
            processing:
                'bg-amber-500/10 text-amber-400 border-amber-500/20 shadow-[0_0_15px_rgba(245,158,11,0.1)]',
            completed:
                'bg-[#c5d86d]/10 text-[#c5d86d] border-[#c5d86d]/20 shadow-[0_0_15px_rgba(197,216,109,0.1)]',
            cancelled:
                'bg-red-500/10 text-red-400 border-red-500/20 shadow-[0_0_15px_rgba(239,68,68,0.1)]',
        };
        return styles[status] || 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2 2xl:grid-cols-3">
        <transition-group
            enter-active-class="transition duration-500 ease-out"
            enter-from-class="transform translate-x-4 opacity-0"
            leave-active-class="transition duration-300 ease-in"
            leave-to-class="transform -translate-x-10 opacity-0"
            move-class="transition duration-500"
        >
            <div
                v-for="order in orders"
                :key="order.id"
                class="group relative overflow-hidden rounded-[2rem] border border-white/5 bg-[#161b14]/60 p-6 backdrop-blur-sm transition-all hover:border-[#c5d86d]/20 hover:bg-[#1a2118]"
            >
                <div
                    class="absolute -right-2 -top-2 select-none font-mono text-6xl font-black text-white/[0.02] transition-colors group-hover:text-[#c5d86d]/[0.03]"
                >
                    #{{ order.id.toString().padStart(4, '0') }}
                </div>

                <div class="relative flex h-full flex-col justify-between space-y-6">
                    <div class="flex items-start justify-between">
                        <div class="space-y-1">
                            <span
                                class="font-mono text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-600"
                            >
                                Reference Node
                            </span>
                            <h3
                                class="font-mono text-lg font-black text-zinc-300 group-hover:text-[#c5d86d]"
                            >
                                #{{ order.id.toString().padStart(5, '0') }}
                            </h3>
                        </div>

                        <span
                            :class="[
                                'inline-flex items-center rounded-xl border px-3 py-1.5 text-[9px] font-black uppercase tracking-widest transition-all',
                                getStatusStyles(order.status),
                            ]"
                        >
                            <span
                                class="mr-1.5 h-1 w-1 animate-pulse rounded-full bg-current"
                            ></span>
                            {{ order.status }}
                        </span>
                    </div>

                    <div
                        class="flex items-center gap-4 rounded-2xl border border-white/5 bg-white/[0.02] p-3"
                    >
                        <div
                            class="flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full bg-zinc-800 text-zinc-400 group-hover:bg-[#c5d86d]/10 group-hover:text-[#c5d86d]"
                        >
                            <AppImage
                                :src="order.user?.avatar_url || ''"
                                :alt="order.customer_name || 'User'"
                            ></AppImage>
                        </div>
                        <div class="min-w-0">
                            <p
                                class="truncate text-sm font-black uppercase tracking-tight text-white"
                            >
                                {{ order.customer_name }}
                            </p>
                            <p
                                class="flex items-center gap-1 text-[11px] font-medium italic text-zinc-500"
                            >
                                {{ order.customer_phone }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <p
                                class="text-[9px] font-black uppercase tracking-widest text-zinc-600"
                            >
                                Timeline
                            </p>
                            <div
                                class="flex items-center gap-2 text-[11px] font-bold text-zinc-400"
                            >
                                <Clock class="h-3.5 w-3.5 text-zinc-600" />
                                {{ new Date(order.created_at).toLocaleDateString('ru-RU') }}
                            </div>
                        </div>
                        <div class="space-y-1 text-right">
                            <p
                                class="text-[9px] font-black uppercase tracking-widest text-zinc-600"
                            >
                                Net Amount
                            </p>
                            <p class="text-lg font-black italic tracking-tighter text-[#c5d86d]">
                                {{ formatUSD(order.total_price) }}
                            </p>
                        </div>
                    </div>

                    <Link
                        :href="route('admin.orders.show', order.id)"
                        class="flex w-full items-center justify-center gap-2 rounded-xl border border-white/5 bg-white/5 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400 transition-all hover:border-[#c5d86d]/30 hover:bg-[#c5d86d] hover:text-[#0f120e]"
                    >
                        Access Protocol <ArrowUpRight class="h-3.5 w-3.5" />
                    </Link>
                </div>
            </div>
        </transition-group>

        <div v-if="orders.length === 0" class="col-span-full flex flex-col items-center py-20">
            <ShoppingBag class="mb-4 h-16 w-16 text-zinc-800" />
            <p class="font-black uppercase italic tracking-widest text-zinc-600">
                No transmissions found
            </p>
        </div>
    </div>
</template>

<style scoped>
    .v-move {
        transition: transform 0.5s ease;
    }

    .v-leave-active {
        position: absolute;
        width: 100%;
    }
</style>
