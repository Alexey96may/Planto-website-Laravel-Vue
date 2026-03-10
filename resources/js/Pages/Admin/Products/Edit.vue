<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        AlertCircle,
        ChevronLeft,
        DollarSign,
        Package,
        Save,
        Tag,
        Type,
        X,
        Zap,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import ImageUploader from '@/Components/UI/ImageUploader.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Category, Product, ProductEditForm } from '@/types';

    const props = defineProps<{
        product: Product;
        categories: Category[];
        page: string | number;
    }>();

    const form = useForm<ProductEditForm>({
        _method: 'put',
        title: props.product.title,
        description: props.product.description,
        price: props.product.price,
        category_id: props.product.category_id ?? '',
        image: null,
        is_trending: Boolean(props.product?.is_trending) ?? false,
        trending_order: props.product?.trending_order ?? 999,
        stock: props.product?.stock ?? 0,
    });

    const uploader = ref<InstanceType<typeof ImageUploader> | null>(null);

    const submit = () => {
        form.post(
            route('admin.products.update', {
                product: props.product.id,
                page: props.page,
            }),
            {
                forceFormData: true,
                onSuccess: () => {
                    uploader.value?.clearImage();
                },
            },
        );
    };

    const handleFileSelect = (file: File | null) => {
        form.image = file;
    };
</script>

<template>
    <AdminLayout>
        <Head :title="'Edit Unit: ' + product.title" />

        <div class="mx-auto max-w-4xl space-y-8">
            <div class="flex flex-wrap items-center justify-between gap-4 px-2">
                <div class="space-y-1">
                    <Link
                        :href="route('admin.products.index', { page: props.page })"
                        class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 transition-colors hover:text-[#c5d86d]"
                    >
                        <ChevronLeft class="h-3 w-3" /> Return to Storage
                    </Link>
                    <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                        Modify <span class="text-[#c5d86d]">Product</span>
                    </h1>
                </div>

                <div>
                    <span
                        class="rounded-xl border border-white/5 bg-white/5 px-4 py-2 text-[10px] font-black uppercase tracking-widest text-zinc-600"
                    >
                        ID: {{ product.id.toString().padStart(4, '0') }}
                    </span>
                </div>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 gap-8 2xl:grid-cols-3">
                <div class="space-y-6 lg:col-span-1">
                    <div class="rounded-[2.5rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl">
                        <h3
                            class="mb-6 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        >
                            Visual Identity
                        </h3>
                        <ImageUploader
                            ref="uploader"
                            v-model="form.image"
                            label="Unit Image"
                            :error="form.errors.image"
                            :existingImage="product.image_url"
                            @on-file-select="handleFileSelect"
                        />
                        <p class="mt-4 text-[9px] italic leading-relaxed text-zinc-500">
                            Recommended: 1000x1000px, PNG/JPG. This image will represent the product
                            in the digital storefront.
                        </p>
                    </div>

                    <div class="space-y-4 rounded-[2rem] border border-white/5 bg-black/40 p-6">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black uppercase text-zinc-500"
                                >Live Preview</span
                            >
                            <div
                                :class="[
                                    'h-2 w-2 rounded-full',
                                    form.stock > 0
                                        ? 'bg-[#c5d86d] shadow-[0_0_8px_#c5d86d]'
                                        : 'bg-red-500',
                                ]"
                            ></div>
                        </div>
                        <div class="text-sm font-bold text-zinc-300">
                            {{ form.title || 'Unnamed Unit' }}
                        </div>
                    </div>
                </div>

                <div class="space-y-6 lg:col-span-2">
                    <div
                        class="space-y-6 rounded-[2.5rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl"
                    >
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <div class="space-y-2 md:col-span-2">
                                <label
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <Type class="h-3 w-3 text-[#c5d86d]" /> Product Title
                                </label>
                                <input
                                    v-model="form.title"
                                    type="text"
                                    class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 font-bold text-white outline-none transition-all placeholder:text-zinc-700 focus:ring-1 focus:ring-[#c5d86d]/50"
                                    placeholder="Enter unit name..."
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
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <Tag class="h-3 w-3 text-[#c5d86d]" /> Classification
                                </label>
                                <select
                                    v-model="form.category_id"
                                    class="w-full appearance-none rounded-2xl border border-white/5 bg-black/50 p-4 font-bold text-zinc-300 outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                >
                                    <option :value="''">Uncategorized</option>
                                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                        {{ cat.title }}
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <DollarSign class="h-3 w-3 text-[#c5d86d]" /> Credits (Price)
                                </label>
                                <div class="relative">
                                    <input
                                        v-model="form.price"
                                        type="number"
                                        class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 pl-10 font-mono font-bold text-[#c5d86d] outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
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
                                >Detailed Manifest (Description)</label
                            >
                            <textarea
                                v-model="form.description"
                                rows="4"
                                class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 text-sm leading-relaxed text-zinc-400 outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                placeholder="Describe the item specifications..."
                            ></textarea>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div
                            class="space-y-4 rounded-[2rem] border border-white/5 bg-[#161b14] p-6"
                        >
                            <label
                                class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                            >
                                <Package class="h-3 w-3 text-blue-400" /> Stock Level
                            </label>
                            <input
                                type="number"
                                v-model="form.stock"
                                min="0"
                                class="w-full rounded-xl border border-white/10 bg-black p-3 text-center text-xl font-black text-white outline-none transition-all focus:border-blue-500/50"
                                :class="{
                                    'border-red-500 bg-red-500/5 text-red-500': form.stock <= 0,
                                }"
                            />
                            <p
                                v-if="form.stock <= 0"
                                class="flex animate-pulse items-center gap-2 text-[10px] font-black uppercase text-red-500"
                            >
                                <AlertCircle class="h-3 w-3" /> Offline from Store
                            </p>
                        </div>

                        <div
                            class="space-y-4 rounded-[2rem] border border-white/5 bg-[#161b14] p-6"
                        >
                            <div class="flex items-center justify-between">
                                <label
                                    class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    <Zap class="h-3 w-3 text-amber-400" /> Trending status
                                </label>
                                <input
                                    type="checkbox"
                                    v-model="form.is_trending"
                                    class="peer sr-only"
                                />
                                <div
                                    @click="form.is_trending = !form.is_trending"
                                    class="relative h-5 w-10 cursor-pointer rounded-full bg-zinc-800 transition-colors after:absolute after:left-1 after:top-1 after:h-3 after:w-3 after:rounded-full after:bg-white after:transition-all after:content-[''] peer-checked:bg-amber-500"
                                    :class="{
                                        'bg-amber-500 after:translate-x-5': form.is_trending,
                                    }"
                                ></div>
                            </div>

                            <div
                                v-if="form.is_trending"
                                class="animate-in fade-in slide-in-from-top-2 pt-2 duration-300"
                            >
                                <p class="mb-2 text-[9px] font-bold uppercase text-zinc-500">
                                    Priority Order
                                </p>
                                <input
                                    v-model="form.trending_order"
                                    type="number"
                                    class="w-full rounded-xl border border-white/10 bg-black p-2 text-center text-sm font-bold text-amber-400"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end gap-4 px-2 pt-4">
                        <Link
                            :href="route('admin.products.index', { page: props.page })"
                            class="text-xs font-black uppercase tracking-widest text-zinc-500 transition-colors hover:text-white"
                        >
                            Abort Changes
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex items-center gap-2 rounded-2xl bg-[#c5d86d] px-8 py-4 text-xs font-black uppercase tracking-[0.2em] text-black shadow-lg shadow-[#c5d86d]/10 transition-all hover:bg-[#d4e685] active:scale-95 disabled:opacity-50"
                        >
                            <Save class="h-4 w-4" />
                            Synchronize Unit
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
    input[type='number'] {
        -moz-appearance: textfield;
    }
</style>
