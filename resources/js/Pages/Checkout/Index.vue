<script setup lang="ts">
    import { Head, useForm, usePage } from '@inertiajs/vue3';

    import {
        ArrowRight,
        CreditCard,
        Mail,
        MapPin,
        MessageSquare,
        Phone,
        ShoppingBag,
        User,
    } from 'lucide-vue-next';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import GuestLayout from '@/Layouts/GuestLayout.vue';
    import { CartItem, CheckoutForm, Seo, SharedData } from '@/types';

    const props = defineProps<{
        cartItems: CartItem[];
        total: number;
        seo?: Seo;
    }>();

    const form = useForm<CheckoutForm>({
        name: usePage<SharedData>().props.auth.user?.name || '',
        email: usePage<SharedData>().props.auth.user?.email || '',
        phone: '',
        address: '',
        comment: '',
        create_account: false,
    });

    const submit = () => {
        form.post(route('checkout.store'), {
            preserveScroll: true,
            onError: (errors) => {
                const firstError = Object.keys(errors)[0];
                const element = document.getElementsByName(firstError)[0];
                element?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            },
        });
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <GuestLayout>
        <div class="mx-auto max-w-7xl px-2 py-6 md:px-4 md:py-12">
            <div class="mb-12 text-center">
                <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white">
                    Final <span class="text-[#c5d86d]">Checkout</span>
                </h1>
                <p class="text-[10px] font-black uppercase tracking-[0.3em] text-zinc-500">
                    Secure Transaction Protocol
                </p>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 gap-8">
                <div class="space-y-6">
                    <section
                        class="rounded-[1rem] border border-white/5 bg-[#161b14] p-4 shadow-2xl md:rounded-[2rem] md:p-8"
                    >
                        <h2
                            class="mb-8 flex items-center gap-3 text-xs font-black uppercase tracking-widest text-[#c5d86d]"
                        >
                            <User class="h-4 w-4" /> Personal Information
                        </h2>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                    for="name"
                                    >Full Name</label
                                >
                                <div class="relative">
                                    <User
                                        class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                    />
                                    <input
                                        v-model="form.name"
                                        id="name"
                                        name="name"
                                        type="text"
                                        class="w-full rounded-xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                        placeholder="John Doe"
                                        required
                                    />
                                </div>
                                <p
                                    v-if="form.errors.name"
                                    class="px-2 text-[10px] font-bold uppercase text-red-500"
                                >
                                    {{ form.errors.name }}
                                </p>
                            </div>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label
                                        class="ml-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                        for="phone"
                                        >Phone</label
                                    >
                                    <div class="relative">
                                        <Phone
                                            class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                        />
                                        <input
                                            v-model="form.phone"
                                            id="phone"
                                            name="phone"
                                            type="tel"
                                            class="w-full rounded-xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                            placeholder="+7 (___) ___"
                                            required
                                        />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="ml-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                        for="email"
                                        >Email</label
                                    >
                                    <div class="relative">
                                        <Mail
                                            class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-600"
                                        />
                                        <input
                                            v-model="form.email"
                                            id="email"
                                            name="email"
                                            type="email"
                                            class="w-full rounded-xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                            placeholder="email@example.com"
                                            required
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section
                        class="rounded-[1rem] border border-white/5 bg-[#161b14] p-4 shadow-2xl md:rounded-[2rem] md:p-8"
                    >
                        <h2
                            class="mb-8 flex items-center gap-3 text-xs font-black uppercase tracking-widest text-[#c5d86d]"
                        >
                            <MapPin class="h-4 w-4" /> Logistics
                        </h2>

                        <div class="space-y-4">
                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                    for="address"
                                    >Delivery Address</label
                                >
                                <textarea
                                    v-model="form.address"
                                    name="address"
                                    id="address"
                                    rows="3"
                                    class="w-full rounded-xl border border-white/5 bg-black/40 p-4 text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                    placeholder="Enter your full address..."
                                    required
                                ></textarea>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="ml-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                    for="comment"
                                    >Comment</label
                                >
                                <div class="relative">
                                    <MessageSquare
                                        class="absolute left-4 top-5 h-4 w-4 text-zinc-600"
                                    />
                                    <textarea
                                        v-model="form.comment"
                                        id="comment"
                                        name="comment"
                                        rows="2"
                                        class="w-full rounded-xl border border-white/5 bg-black/40 p-4 pl-12 text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                        placeholder="Gate code, floor, etc."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <div
                            v-if="!$page.props.auth.user"
                            class="mt-6 border-t border-white/5 pt-6"
                        >
                            <label
                                for="create_account"
                                class="group flex cursor-pointer items-center gap-3"
                            >
                                <div
                                    class="relative flex h-5 w-5 items-center justify-center rounded border border-white/10 bg-black/40 transition-all group-hover:border-[#c5d86d]/50"
                                >
                                    <input
                                        type="checkbox"
                                        id="create_account"
                                        v-model="form.create_account"
                                        class="peer absolute h-full w-full cursor-pointer opacity-0"
                                    />
                                    <div
                                        class="h-2 w-2 rounded-sm bg-[#c5d86d] opacity-0 transition-opacity peer-checked:opacity-100"
                                    ></div>
                                </div>
                                <span
                                    class="text-[11px] font-bold uppercase tracking-tight text-zinc-400 transition-colors group-hover:text-white"
                                >
                                    Create a system account for tracking
                                </span>
                            </label>
                        </div>
                    </section>
                </div>

                <div>
                    <div class="top-8 space-y-6">
                        <section
                            class="rounded-[1rem] border border-white/5 bg-[#161b14] p-4 shadow-2xl md:rounded-[2rem] md:p-8"
                        >
                            <h2
                                class="mb-6 text-xs font-black uppercase tracking-widest text-white"
                            >
                                Order <span class="text-[#c5d86d]">Manifest</span>
                            </h2>

                            <div class="custom-scrollbar space-y-4">
                                <div
                                    v-for="item in cartItems"
                                    :key="item.id"
                                    class="flex flex-col flex-wrap items-start gap-4 rounded-xl border border-white/[0.02] bg-black/20 p-4"
                                >
                                    <div class="flex flex-wrap items-center gap-4">
                                        <AppImage
                                            :src="item.image"
                                            class="h-16 w-16 rounded-xl border border-white/5 object-cover"
                                        />
                                        <div class="min-w-[50px] flex-1">
                                            <h4 class="text-xs font-bold text-white">
                                                {{ item.title }}
                                            </h4>
                                            <p class="font-mono text-[10px] text-[#c5d86d]">
                                                {{ item.quantity }} x ${{ item.price }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-xs font-black text-white">
                                        ${{ (item.quantity * Number(item.price)).toFixed(2) }}
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 space-y-3 border-t border-white/5 pt-6">
                                <div
                                    class="flex flex-wrap justify-between gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                >
                                    <span>Subtotal</span>
                                    <span>${{ total }}</span>
                                </div>
                                <div
                                    class="flex flex-wrap justify-between gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                >
                                    <span>Delivery</span>
                                    <span class="text-[#c5d86d]">FREE</span>
                                </div>
                                <div
                                    class="flex flex-wrap justify-between gap-2 border-t border-white/10 pt-4"
                                >
                                    <span
                                        class="text-xs font-black uppercase tracking-[0.3em] text-white"
                                        >Total Amount</span
                                    >
                                    <span class="text-xl font-black text-[#c5d86d]"
                                        >${{ total }}</span
                                    >
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="mt-8 flex w-full items-center justify-center gap-3 rounded-xl bg-emerald-500 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-black transition-all hover:scale-[1.02] hover:bg-emerald-300 active:scale-95 disabled:opacity-50"
                            >
                                <CreditCard
                                    v-if="!form.processing"
                                    class="hidden h-4 w-4 sm:block"
                                />
                                <span>{{ form.processing ? 'Syncing...' : 'Initiate Order' }}</span>
                                <ArrowRight v-if="!form.processing" class="h-4 w-4" />
                            </button>
                        </section>
                    </div>
                </div>
            </form>
        </div>
    </GuestLayout>
</template>

<style scoped>
    .custom-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(197, 216, 109, 0.2);
        border-radius: 10px;
    }
</style>
