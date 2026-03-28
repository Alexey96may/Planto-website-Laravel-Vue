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

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import WindEffect from '@/Components/UI/WindEffect.vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { FullOrder, OrderStatus, Seo } from '@/types';
    import { formatUSD } from '@/utils/money';

    const props = defineProps<{
        orders: FullOrder[];
        seo?: Seo;
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
    <SeoMeta :seo="seo" />

    <AuthenticatedLayout>
        <main class="px-6 py-16 lg:py-24">
            <WindEffect aria-hidden="true" :particleCount="35" :windStrength="1.5" />

            <h2
                class="relative z-[3] mb-16 text-3xl font-black uppercase italic tracking-tighter text-white"
            >
                Terminal / <span class="text-[#c5d86d]">Order_History</span>
            </h2>

            <div class="relative z-[3] mx-auto max-w-5xl sm:px-4 lg:px-6">
                <div
                    v-if="orders.length === 0"
                    role="status"
                    class="flex flex-col items-center justify-center rounded-[2rem] border border-dashed border-white/10 p-20 text-center backdrop-blur-sm"
                >
                    <ShoppingBag class="mb-4 h-12 w-12 text-zinc-700" aria-hidden="true" />
                    <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">
                        No active transmissions found
                    </p>
                    <Link
                        :href="route('shop')"
                        class="mt-6 text-xs font-black uppercase tracking-widest text-[#c5d86d] underline decoration-2 underline-offset-8 transition-colors hover:text-white"
                    >
                        Initialize New Purchase
                    </Link>
                </div>

                <div v-else class="space-y-12" role="list" aria-label="Order history">
                    <article
                        v-for="order in orders"
                        :key="order.id"
                        role="listitem"
                        :aria-labelledby="'order-title-' + order.id"
                        class="group relative overflow-hidden rounded-[0.5rem] border border-emerald-400/50 bg-plant-shop p-6 shadow-2xl transition-all hover:border-[#c5d86d]/30 md:rounded-[1.5rem]"
                    >
                        <div
                            aria-hidden="true"
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
                                    <Hash class="h-3 w-3" aria-hidden="true" />
                                    <span
                                        >Deployment ID: <span class="sr-only">Number</span>
                                        {{ order.id }}</span
                                    >
                                </div>
                                <h3
                                    :id="'order-title-' + order.id"
                                    class="text-lg font-bold text-white"
                                >
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
                                    :class="getStatusConfig(order.status)?.color"
                                    :aria-label="'Status: ' + getStatusConfig(order.status)?.label"
                                >
                                    <component
                                        :is="getStatusConfig(order.status)?.icon"
                                        class="h-3 w-3"
                                        aria-hidden="true"
                                    />
                                    <span class="text-[10px] font-black uppercase tracking-widest">
                                        {{ getStatusConfig(order.status)?.label }}
                                    </span>
                                </div>
                                <p class="text-xl font-black italic tracking-tighter text-white">
                                    <span class="sr-only">Total price:</span>
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
                                        :aria-label="'Product: ' + item.product_name"
                                        class="text-zinc-300 decoration-[#c5d86d]/30 underline-offset-4 transition-colors hover:text-[#c5d86d] hover:underline"
                                    >
                                        {{ item.product_name }}
                                    </Link>
                                    <span v-else class="text-zinc-300">{{
                                        item.product_name
                                    }}</span>
                                    <span class="font-mono text-[#c5d86d]" aria-label="Quantity">
                                        <span aria-hidden="true">x</span>{{ item.quantity }}
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </article>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>

<style scoped>
    .group:hover {
        box-shadow: 0 0 30px -10px rgba(197, 216, 109, 0.1);
    }
</style>
