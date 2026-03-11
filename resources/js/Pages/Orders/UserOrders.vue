<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';

    import {
        CheckCircle2,
        Clock,
        ExternalLink,
        Hash,
        Package,
        ShoppingBag,
        XCircle,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { FullOrder, OrderStatus } from '@/types';
    import { formatUSD } from '@/utils/money';

    const props = defineProps<{
        orders: FullOrder[];
    }>();

    const getStatusConfig = (status: OrderStatus) => {
        const configs: Record<OrderStatus, { label: string; color: string; icon: any }> = {
            new: { label: 'Accepted', color: 'text-blue-400', icon: Clock },
            processing: { label: 'In Progress', color: 'text-amber-400', icon: Package },
            completed: { label: 'Dispatched', color: 'text-[#c5d86d]', icon: CheckCircle2 },
            cancelled: { label: 'Terminated', color: 'text-red-500', icon: XCircle },
        };
        return configs[status];
    };
</script>

<template>
    <Head title="Order Archive" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-black uppercase italic tracking-tighter text-white">
                Terminal / <span class="text-[#c5d86d]">Order_History</span>
            </h2>
        </template>

        <div class="py-4 sm:py-8 lg:py-12">
            <div class="mx-auto max-w-5xl px-2 sm:px-4 lg:px-6">
                <div
                    v-if="orders.length === 0"
                    class="flex flex-col items-center justify-center rounded-[2rem] border border-dashed border-white/10 bg-[#161b14] p-20 text-center"
                >
                    <ShoppingBag class="mb-4 h-12 w-12 text-zinc-700" />
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">
                        No active transmissions found
                    </p>
                    <Link
                        :href="route('products.index')"
                        class="mt-6 text-xs font-black uppercase tracking-widest text-[#c5d86d] underline decoration-2 underline-offset-8 transition-colors hover:text-white"
                    >
                        Initialize New Purchase
                    </Link>
                </div>

                <div v-else class="space-y-6">
                    <div
                        v-for="order in orders"
                        :key="order.id"
                        class="group relative overflow-hidden rounded-[0.5rem] border border-white/5 bg-emerald-950 p-6 shadow-2xl transition-all hover:border-[#c5d86d]/30 md:rounded-[1.5rem]"
                    >
                        <div
                            class="absolute left-0 top-0 h-full w-1"
                            :class="
                                order.status === 'completed' ? 'bg-emerald-400' : 'bg-emerald-800'
                            "
                        ></div>

                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <Hash class="h-3 w-3" />
                                    <span>Deployment ID: {{ order.id }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-white">
                                    {{
                                        new Date(order.created_at).toLocaleDateString('en-US', {
                                            month: 'long',
                                            day: 'numeric',
                                            year: 'numeric',
                                        })
                                    }}
                                </h3>
                            </div>

                            <div class="flex flex-col items-end gap-2">
                                <div
                                    class="flex items-center gap-2 rounded-full border border-white/5 bg-black/40 px-3 py-1"
                                    :class="getStatusConfig(order.status).color"
                                >
                                    <component
                                        :is="getStatusConfig(order.status).icon"
                                        class="h-3 w-3"
                                    />
                                    <span class="text-[10px] font-black uppercase tracking-widest">
                                        {{ getStatusConfig(order.status).label }}
                                    </span>
                                </div>
                                <p class="text-xl font-black italic tracking-tighter text-white">
                                    {{ formatUSD(order.total_price) }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-6 rounded-sm border border-white/[0.02] bg-black/20 p-4 md:rounded-xl"
                        >
                            <h4
                                class="mb-3 text-[10px] font-black uppercase tracking-widest text-zinc-600"
                            >
                                Cargo Manifest:
                            </h4>
                            <ul class="space-y-2">
                                <li
                                    v-for="item in order.items"
                                    :key="item.id"
                                    class="flex items-center justify-between gap-2 border-b border-white/[0.05] pb-2 text-xs font-medium last:border-0 last:pb-0"
                                >
                                    <Link
                                        v-if="item.product"
                                        :href="'/shop/plant-' + item.product.id"
                                        class="text-zinc-300 decoration-[#c5d86d]/30 underline-offset-4 transition-colors hover:text-[#c5d86d] hover:underline"
                                    >
                                        {{ item.product_name }}
                                    </Link>
                                    <span
                                        v-else
                                        class="text-zinc-300 decoration-[#c5d86d]/30 underline-offset-4 transition-colors hover:text-[#c5d86d] hover:underline"
                                        >x{{ item.product_name }}</span
                                    >
                                    <span class="font-mono text-[#c5d86d]"
                                        >x{{ item.quantity }}</span
                                    >
                                </li>
                            </ul>
                        </div>

                        <div class="mt-4 flex justify-end">
                            <button
                                class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500 transition-colors hover:text-[#c5d86d]"
                            >
                                View Full Protocol <ExternalLink class="h-3 w-3" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    .group:hover {
        box-shadow: 0 0 30px -10px rgba(197, 216, 109, 0.1);
    }
</style>
