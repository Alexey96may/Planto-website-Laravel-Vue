<script setup lang="ts">
    import { computed } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        ArrowLeft,
        Calendar,
        CheckCircle2,
        CreditCard,
        MapPin,
        Package,
        Phone,
        RefreshCcw,
        User,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { FullOrder, OrderStatus } from '@/types';
    import { calculateTotal, formatUSD } from '@/utils/money';

    defineOptions({ layout: AdminLayout });

    const props = defineProps<{
        order: FullOrder;
    }>();

    const form = useForm<{ status: OrderStatus }>({
        status: props.order.status as OrderStatus,
    });

    const getStatusStyles = (status: OrderStatus) => {
        const styles: Record<OrderStatus, string> = {
            new: 'bg-blue-500/10 text-blue-400 border-blue-500/20',
            processing: 'bg-amber-500/10 text-amber-400 border-amber-500/20',
            completed: 'bg-[#c5d86d]/10 text-[#c5d86d] border-[#c5d86d]/20',
            cancelled: 'bg-red-500/10 text-red-400 border-red-500/20',
        };
        return styles[status] || 'bg-zinc-500/10 text-zinc-400 border-zinc-500/20';
    };

    const updateStatus = () => {
        form.patch(route('admin.orders.update', props.order.id), {
            preserveScroll: true,
        });
    };

    const orderTotal = computed(() => {
        return props.order.items.reduce((acc, item) => {
            return acc + calculateTotal(item.price, item.quantity);
        }, 0);
    });
</script>

<template>
    <Head :title="'Order #' + order.id" />

    <div class="mx-auto max-w-5xl space-y-8">
        <div class="flex flex-col flex-wrap justify-between gap-4 px-2 md:flex-row md:items-center">
            <div class="space-y-1">
                <Link
                    :href="route('admin.orders.index')"
                    class="flex items-center gap-2 text-xs font-black uppercase tracking-[0.2em] text-zinc-500 transition-colors hover:text-[#c5d86d]"
                >
                    <ArrowLeft class="h-3 w-3" /> Back to Terminal
                </Link>
                <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white">
                    Manifest
                    <span class="text-[#c5d86d]">#{{ order.id.toString().padStart(5, '0') }}</span>
                </h1>
            </div>

            <div
                class="flex items-center gap-3 rounded-2xl border border-white/5 bg-[#161b14] p-2 shadow-xl"
            >
                <select
                    v-model="form.status"
                    class="rounded-xl border-none bg-black px-4 py-2 text-xs font-bold uppercase tracking-widest text-zinc-400 focus:ring-1 focus:ring-[#c5d86d]/50"
                >
                    <option value="new">New Entry</option>
                    <option value="processing">Processing</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button
                    @click="updateStatus"
                    :disabled="form.processing"
                    class="flex items-center gap-2 rounded-xl bg-[#c5d86d] px-4 py-2 text-xs font-black uppercase text-black transition-all hover:bg-[#d4e685] disabled:opacity-50"
                >
                    <RefreshCcw class="h-3.5 w-3.5" :class="{ 'animate-spin': form.processing }" />
                    Update
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-8 2xl:grid-cols-3">
            <div class="space-y-6 lg:col-span-1">
                <div
                    class="relative overflow-hidden rounded-[2rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl"
                >
                    <div class="absolute right-0 top-0 p-4 opacity-5">
                        <User class="h-20 w-20 text-white" />
                    </div>

                    <h3
                        class="mb-6 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                    >
                        <User class="h-3 w-3 text-[#c5d86d]" /> Customer Profile
                    </h3>

                    <div class="relative z-10 space-y-4">
                        <div>
                            <p class="mb-1 text-right text-xs uppercase italic text-zinc-500">
                                Full Name
                            </p>
                            <p class="text-xl font-bold tracking-tight text-white">
                                {{ order.customer_name }}
                            </p>
                        </div>
                        <div
                            class="flex items-center gap-3 rounded-xl border border-white/5 bg-black/30 p-3"
                        >
                            <Phone class="h-4 w-4 text-[#c5d86d]" />
                            <p class="font-mono text-sm text-zinc-300">
                                {{ order.customer_phone }}
                            </p>
                        </div>
                        <div class="border-t border-white/5 pt-4">
                            <p
                                class="mb-2 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            >
                                <MapPin class="h-3 w-3 text-[#c5d86d]" /> Delivery Path
                            </p>
                            <p class="text-sm italic leading-relaxed text-zinc-400">
                                {{ order.delivery_address || 'No physical address provided' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="flex items-center justify-between rounded-[2rem] border border-white/5 bg-[#161b14] p-6"
                >
                    <div>
                        <p
                            class="mb-1 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        >
                            Status
                        </p>
                        <div
                            :class="[
                                'inline-block rounded-lg border px-3 py-1 text-[10px] font-black uppercase tracking-widest',
                                getStatusStyles(order.status),
                            ]"
                        >
                            {{ order.status }}
                        </div>
                    </div>
                    <div class="text-right">
                        <p
                            class="mb-1 text-right text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        >
                            Registered
                        </p>
                        <div
                            class="flex items-center justify-end gap-2 text-xs font-bold text-zinc-300"
                        >
                            <Calendar class="h-3 w-3 text-[#c5d86d]" />
                            {{ new Date(order.created_at).toLocaleDateString() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-[2.5rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl">
                    <h3
                        class="mb-8 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                    >
                        <Package class="h-4 w-4 text-[#c5d86d]" /> Package Contents
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr
                                    class="border-b border-white/5 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-600"
                                >
                                    <th class="pb-4 text-left">Product Unit</th>
                                    <th class="pb-4 text-center">Qty</th>
                                    <th class="pb-4 text-right">Price</th>
                                    <th class="pb-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                <tr v-for="item in order.items" :key="item.id" class="group">
                                    <td class="py-6">
                                        <div
                                            class="text-sm font-bold uppercase tracking-tight text-white transition-colors group-hover:text-[#c5d86d]"
                                        >
                                            <Link
                                                v-if="item.product"
                                                :href="'/shop/plant-' + item.product?.id"
                                                aria-label="To {{ item.product_name }}"
                                                >{{ item.product_name }}
                                            </Link>
                                        </div>
                                    </td>
                                    <td class="py-6 text-center">
                                        <span
                                            class="rounded-lg bg-white/5 px-3 py-1 font-mono text-xs text-zinc-400"
                                        >
                                            x{{ item.quantity }}
                                        </span>
                                    </td>
                                    <td class="py-6 text-right font-mono text-sm text-zinc-400">
                                        {{ formatUSD(item.price) }}
                                    </td>
                                    <td class="py-6 text-right font-black italic text-zinc-200">
                                        {{ formatUSD(calculateTotal(item.price, item.quantity)) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        class="mt-8 flex items-end justify-between border-t-2 border-dashed border-white/5 pt-8"
                    >
                        <div class="hidden md:block">
                            <div class="flex items-center gap-2 text-[#c5d86d] opacity-50">
                                <CheckCircle2 class="h-4 w-4" />
                                <span class="text-[10px] font-black uppercase tracking-widest"
                                    >Digital Invoice Verified</span
                                >
                            </div>
                        </div>
                        <div class="space-y-1 text-right">
                            <p
                                class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            >
                                Total Transaction Value
                            </p>
                            <p class="text-4xl font-black italic tracking-tighter text-[#c5d86d]">
                                {{ formatUSD(orderTotal) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between px-4">
                    <div
                        class="flex items-center gap-4 text-xs font-bold uppercase tracking-widest text-zinc-600"
                    >
                        <CreditCard class="h-4 w-4" /> Paid via Gateway
                    </div>
                    <button
                        class="rounded-xl border border-white/10 px-4 py-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 transition-colors hover:text-white"
                    >
                        Print Manifest
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
