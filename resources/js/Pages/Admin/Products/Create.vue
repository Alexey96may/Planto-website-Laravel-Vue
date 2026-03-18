<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        ChevronLeft,
        DollarSign,
        Package,
        PlusCircle,
        Sparkles,
        Tag,
        Type,
        X,
        Zap,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import ImageUploader from '@/Components/UI/ImageUploader.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Category, ProductForm, Seo } from '@/types';

    const uploader = ref<InstanceType<typeof ImageUploader> | null>(null);

    defineProps<{
        categories: Category[];
        seo?: Seo;
    }>();

    const form = useForm<ProductForm>({
        title: '',
        description: '',
        price: '',
        category_id: '',
        image: null,
        is_trending: false,
        trending_order: 999,
        stock: 1,
    });

    const submit = () => {
        form.post(route('admin.products.store'), {
            onError: (e) => {
                console.warn(e);
            },
        });
    };

    const handleImageSelected = (file: File | null) => {
        form.image = file;
    };
</script>

<template>
    <SeoMeta :seo="seo" />

    <AdminLayout>
        <div class="mx-auto max-w-4xl space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-6 px-2">
                <div class="space-y-1">
                    <Link
                        :href="route('admin.products.index')"
                        class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 transition-colors hover:text-[#c5d86d]"
                    >
                        <ChevronLeft class="h-3 w-3" /> Back to Archive
                    </Link>
                    <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                        New <span class="text-[#c5d86d]">Inventory</span> Unit
                    </h1>
                </div>

                <div
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-[#c5d86d]/20 bg-[#c5d86d]/10 text-[#c5d86d]"
                >
                    <Sparkles class="h-6 w-6" />
                </div>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 gap-8 2xl:grid-cols-3">
                <div class="space-y-6 lg:col-span-1">
                    <div class="rounded-[2.5rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl">
                        <h3
                            class="mb-6 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        >
                            Visual Setup
                        </h3>

                        <ImageUploader
                            ref="uploader"
                            v-model="form.image"
                            label="Unit Blueprint"
                            :error="form.errors.image"
                            @on-file-select="handleImageSelected"
                        />

                        <div v-if="form.progress" class="mt-4">
                            <div class="h-1 w-full overflow-hidden rounded-full bg-white/5">
                                <div
                                    class="h-full bg-[#c5d86d] transition-all duration-300"
                                    :style="{ width: form.progress.percentage + '%' }"
                                ></div>
                            </div>
                            <p
                                class="mt-2 text-center text-[9px] font-black uppercase tracking-widest text-zinc-500"
                            >
                                Uploading: {{ form.progress.percentage }}%
                            </p>
                        </div>
                    </div>

                    <div class="rounded-[2rem] border border-white/5 bg-black/40 p-6">
                        <p
                            class="text-[9px] font-bold uppercase italic leading-relaxed tracking-tighter text-zinc-500"
                        >
                            Notice: Newly created items are set to "Active" by default if stock > 0.
                        </p>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-2">
                    <div
                        class="space-y-6 rounded-[2.5rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl"
                    >
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2 md:col-span-2">
                                <label
                                    for="productTitle"
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <Type class="h-3 w-3 text-[#c5d86d]" /> Identity Title
                                </label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    id="productTitle"
                                    required
                                    class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 font-bold text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                    placeholder="e.g. Monstera Deliciosa v.2"
                                />
                                <div
                                    v-if="form.errors.title"
                                    class="text-[10px] font-bold uppercase text-red-500"
                                >
                                    {{ form.errors.title }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    for="productCategory"
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <Tag class="h-3 w-3 text-[#c5d86d]" /> Type
                                </label>
                                <select
                                    v-model="form.category_id"
                                    id="productCategory"
                                    class="w-full appearance-none rounded-2xl border border-white/5 bg-black/50 p-4 font-bold text-zinc-300 outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                >
                                    <option value="">Uncategorized</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.title }}
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label
                                    for="productPrice"
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <DollarSign class="h-3 w-3 text-[#c5d86d]" /> Valuation
                                </label>
                                <div class="relative">
                                    <input
                                        v-model="form.price"
                                        id="productPrice"
                                        step="0.01"
                                        type="number"
                                        required
                                        class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 pl-10 font-mono font-bold text-[#c5d86d] outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                    />
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 font-bold text-zinc-600"
                                        >$</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                for="description"
                                >Specifications & Care</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="4"
                                id="description"
                                class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 text-sm leading-relaxed text-zinc-400 outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                placeholder="Enter technical details and product description..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div
                            class="space-y-4 rounded-[2rem] border border-white/5 bg-[#161b14] p-6"
                        >
                            <label
                                class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                for="stock"
                            >
                                <Package class="h-3 w-3 text-blue-400" /> Initial Stock
                            </label>
                            <input
                                type="number"
                                v-model="form.stock"
                                min="0"
                                id="stock"
                                class="w-full rounded-xl border border-white/10 bg-black p-3 text-center text-xl font-black text-white outline-none transition-all focus:border-blue-500/50"
                            />
                            <p
                                class="text-center text-[9px] font-bold uppercase tracking-widest text-zinc-500"
                            >
                                Status: {{ form.stock > 0 ? 'Deploying to Store' : 'Draft Mode' }}
                            </p>
                        </div>

                        <div
                            class="space-y-4 rounded-[2rem] border border-white/5 bg-[#161b14] p-6"
                        >
                            <div class="flex items-center justify-between">
                                <label
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                    for="is_trending"
                                >
                                    <Zap class="h-3 w-3 text-amber-400" /> Hot Item
                                </label>
                                <div
                                    @click="form.is_trending = !form.is_trending"
                                    id="is_trending"
                                    class="relative h-5 w-10 cursor-pointer rounded-full bg-zinc-800 transition-colors"
                                    :class="{ 'bg-amber-500': form.is_trending }"
                                >
                                    <div
                                        class="absolute left-1 top-1 h-3 w-3 rounded-full bg-white transition-transform duration-200"
                                        :class="{ 'translate-x-5': form.is_trending }"
                                    ></div>
                                </div>
                            </div>

                            <div v-if="form.is_trending" class="pt-2">
                                <input
                                    v-model="form.trending_order"
                                    type="number"
                                    class="w-full rounded-xl border border-white/10 bg-black p-2 text-center text-sm font-bold text-amber-400"
                                    placeholder="Order"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-6 px-2 pt-4">
                        <Link
                            :href="route('admin.products.index')"
                            class="text-xs font-black uppercase tracking-widest text-zinc-500 transition-colors hover:text-white"
                        >
                            Discard
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex items-center gap-2 rounded-2xl bg-[#c5d86d] px-10 py-4 text-xs font-black uppercase tracking-[0.2em] text-black shadow-xl shadow-[#c5d86d]/5 transition-all hover:bg-[#d4e685] active:scale-95 disabled:opacity-50"
                        >
                            <PlusCircle class="h-4 w-4" />
                            {{ form.processing ? 'Uploading...' : 'Initialize Unit' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<style scoped>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
