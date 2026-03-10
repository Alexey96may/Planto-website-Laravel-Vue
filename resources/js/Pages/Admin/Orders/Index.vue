<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';

    import {
        ArrowUpRight,
        ChevronRight,
        Clock,
        Filter,
        Search,
        ShoppingBag,
        User,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import Toast from '@/Components/Shared/Toast.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { OrderStatus, OrderWithUser } from '@/types';
    import { formatUSD } from '@/utils/money';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        orders: OrderWithUser[];
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
    <Head title="Order Management" />

    <Toast />

    <div class="mx-auto max-w-7xl space-y-8">
        <div
            class="flex flex-col flex-wrap gap-6 px-2 md:flex-row md:items-center md:justify-between"
        >
            <div>
                <h1
                    class="whitespace-normal text-4xl font-black uppercase italic tracking-tighter text-white"
                >
                    Order <span class="text-[#c5d86d]">Terminal</span>
                </h1>
                <p class="mt-1 text-sm italic text-zinc-500">
                    Processing real-time transactions and logistics.
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <div
                    class="flex items-center gap-2 rounded-2xl border border-white/5 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-widest text-zinc-400"
                >
                    <Filter class="h-3 w-3" /> Filter
                </div>
                <div class="relative">
                    <Search
                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                    />
                    <input
                        type="text"
                        placeholder="SEARCH ID OR CLIENT..."
                        class="rounded-2xl border border-white/5 bg-[#0f120e] py-2 pl-10 pr-4 text-xs font-bold uppercase tracking-widest text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50 md:w-64"
                    />
                </div>
            </div>
        </div>

        <div
            class="overflow-hidden rounded-[1rem] border border-white/5 bg-[#161b14]/60 shadow-2xl backdrop-blur-sm md:rounded-[2.5rem]"
        >
            <div class="overflow-x-auto">
                <table class="w-full border-collapse text-left">
                    <thead>
                        <tr
                            class="border-b border-white/5 bg-white/[0.02] text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        >
                            <th class="px-8 py-6">Reference</th>
                            <th class="px-6 py-6">Customer</th>
                            <th class="px-6 py-6 text-center">Amount</th>
                            <th class="px-6 py-6 text-center">Status</th>
                            <th class="px-6 py-6">Timeline</th>
                            <th class="px-8 py-6 text-right">Access</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr
                            v-for="order in orders"
                            :key="order.id"
                            class="group transition-colors hover:bg-white/[0.02]"
                        >
                            <td class="whitespace-nowrap px-8 py-6">
                                <span
                                    class="font-mono text-sm font-bold text-zinc-500 group-hover:text-[#c5d86d]"
                                >
                                    #{{ order.id.toString().padStart(5, '0') }}
                                </span>
                            </td>

                            <td class="px-6 py-6">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/5 text-zinc-400 group-hover:bg-[#c5d86d]/10 group-hover:text-[#c5d86d]"
                                    >
                                        <User class="h-5 w-5" />
                                    </div>
                                    <div>
                                        <div
                                            class="text-sm font-bold uppercase tracking-tight text-white"
                                        >
                                            {{ order.customer_name }}
                                        </div>
                                        <div class="text-[10px] font-medium italic text-zinc-500">
                                            {{ order.customer_phone }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="whitespace-nowrap px-6 py-6 text-center">
                                <span
                                    class="text-base font-black italic tracking-tighter text-[#c5d86d]"
                                >
                                    {{ formatUSD(order.total_price) }}
                                </span>
                            </td>

                            <td class="px-6 py-6 text-center">
                                <span
                                    :class="[
                                        'inline-flex items-center rounded-lg border px-3 py-1 text-[10px] font-black uppercase tracking-widest transition-all',
                                        getStatusStyles(order.status),
                                    ]"
                                >
                                    <span class="mr-1.5 h-1.5 w-1.5 rounded-full bg-current"></span>
                                    {{ order.status }}
                                </span>
                            </td>

                            <td class="whitespace-nowrap px-6 py-6">
                                <div
                                    class="flex items-center gap-2 text-xs font-bold text-zinc-500"
                                >
                                    <Clock class="h-3 w-3" />
                                    {{
                                        new Date(order.created_at).toLocaleDateString('ru-RU', {
                                            day: '2-digit',
                                            month: 'short',
                                        })
                                    }}
                                </div>
                            </td>

                            <td class="px-8 py-6 text-right">
                                <Link
                                    :href="route('admin.orders.show', order.id)"
                                    class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 bg-white/5 text-zinc-400 transition-all hover:border-[#c5d86d]/30 hover:bg-[#c5d86d] hover:text-[#0f120e]"
                                >
                                    <ArrowUpRight class="h-5 w-5" />
                                </Link>
                            </td>
                        </tr>

                        <tr v-if="orders.length === 0">
                            <td colspan="6" class="px-8 py-20 text-center">
                                <ShoppingBag class="mx-auto mb-4 h-12 w-12 text-zinc-800" />
                                <p class="font-medium italic text-zinc-600">
                                    No transmissions found in the database.
                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
    tr group * {
        transition: all 0.2s ease-in-out;
    }
</style>
