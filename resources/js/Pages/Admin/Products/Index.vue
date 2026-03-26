<script setup lang="ts">
    import { onMounted, onUnmounted, ref } from 'vue';

    import { Link, router } from '@inertiajs/vue3';

    import { AlertTriangle, Edit3, Layers, Package, Plus, Trash2 } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import Pagination from '@/Components/Shared/Pagination.vue';
    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import ProductAdminSkeleton from '@/Components/UI/ProductAdminSkeleton.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import '@/types';
    import { PaginatedResponse, ProductWithCategory, Seo } from '@/types';
    import { formatUSD } from '@/utils/money';

    defineProps<{
        products: PaginatedResponse<ProductWithCategory>;
        seo?: Seo;
    }>();

    const isUpdating = ref(false);
    const { notifyWithUndo } = useFlash();

    const isDeleting = ref<number | null>(null);

    const updateProductField = (
        product: ProductWithCategory,
        field: keyof ProductWithCategory,
        value: string | number | boolean,
    ) => {
        let finalValue = value;
        isUpdating.value = true;

        if (['stock', 'trending_order'].includes(field as string)) {
            finalValue = value === '' ? 0 : Number(value);
        }

        if (product[field] === finalValue) {
            isUpdating.value = false;
            return;
        }

        router.patch(
            route('admin.products.update-trending', product.id),
            { [field]: finalValue },
            {
                preserveScroll: true,
                onFinish: () => (isUpdating.value = false),
            },
        );
    };

    const handleChange = (
        product: ProductWithCategory,
        field: keyof ProductWithCategory,
        event: Event,
    ) => {
        const target = event.target as HTMLInputElement;
        if (target.type === 'checkbox') {
            updateProductField(product, field, target.checked);
        } else {
            updateProductField(product, field, target.value);
        }
    };

    const deleteProduct = async (id: number) => {
        if (isDeleting.value === id) return;

        isDeleting.value = id;

        try {
            const confirmed = await notifyWithUndo('Purging product from core...', 5000);

            if (confirmed) {
                router.delete(route('admin.products.destroy', id), {
                    preserveScroll: true,
                    onFinish: () => {
                        isDeleting.value = null;
                    },
                    onError: () => {
                        isDeleting.value = null;
                    },
                });
            } else {
                isDeleting.value = null;
            }
        } catch (e) {
            isDeleting.value = null;
        }
    };

    const isLoading = ref(false);
    let unregisterStart: () => void;
    let unregisterFinish: () => void;

    onMounted(() => {
        unregisterStart = router.on('start', () => {
            isLoading.value = true;
        });

        unregisterFinish = router.on('finish', () => {
            setTimeout(() => {
                isLoading.value = false;
            }, 300);
        });
    });

    onUnmounted(() => {
        if (unregisterStart) unregisterStart();
        if (unregisterFinish) unregisterFinish();
    });
</script>

<template>
    <SeoMeta :seo="seo" />

    <AdminLayout>
        <div class="mx-auto max-w-7xl space-y-8 pb-12">
            <div
                class="flex flex-col flex-wrap gap-6 px-4 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white">
                        Inventory <span class="text-[#c5d86d]">Control</span>
                    </h1>
                    <p class="mt-1 text-sm italic text-zinc-500">
                        Managing {{ products.total }} unique product units.
                    </p>
                </div>

                <Link
                    :href="route('admin.products.create')"
                    class="group flex items-center gap-2 rounded-xl bg-[#c5d86d] px-6 py-3 text-xs font-black uppercase tracking-widest text-black transition-all hover:bg-[#d4e685] hover:shadow-[0_0_20px_rgba(197,216,109,0.3)] active:scale-95"
                >
                    <Plus class="h-4 w-4 stroke-[3px]" />
                    New Product
                </Link>
            </div>

            <div
                class="overflow-hidden rounded-xl shadow-2xl backdrop-blur-sm 2xl:border 2xl:border-white/5 2xl:bg-[#161b14]/60"
            >
                <div
                    class="hidden grid-cols-[80px_1fr_120px_120px_100px_140px] gap-4 px-8 py-6 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 2xl:grid"
                >
                    <div>Visual</div>
                    <div>Identity / Info</div>
                    <div class="text-right">Price</div>
                    <div class="text-center">Trend Status</div>
                    <div class="text-center">Stock Level</div>
                    <div class="text-right">Actions</div>
                </div>

                <div class="min-h-[600px] divide-y divide-white/5">
                    <template v-if="isLoading">
                        <ProductAdminSkeleton v-for="i in products.per_page" :key="i" />
                    </template>

                    <transition-group
                        v-else
                        enter-active-class="transition duration-500 ease-out"
                        enter-from-class="transform translate-x-4 opacity-0"
                        leave-active-class="transition duration-300 ease-in"
                        leave-to-class="transform -translate-x-10 opacity-0"
                        move-class="transition duration-500"
                        tag="div"
                    >
                        <div
                            v-for="product in products.data"
                            :key="product.id"
                            :class="[
                                'group grid grid-cols-1 items-center gap-4 rounded-xl bg-[#161b14] px-8 py-6 shadow-xl ring-1 ring-inset ring-white/10 transition-all hover:bg-white/[0.02] sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-[80px_1fr_120px_120px_100px_140px]',
                                isDeleting === product.id
                                    ? 'pointer-events-none origin-center scale-95 opacity-50'
                                    : '',
                            ]"
                        >
                            <div class="flex justify-center 2xl:justify-start">
                                <div
                                    class="relative h-14 w-14 overflow-hidden rounded-xl border border-white/10 bg-black shadow-inner transition-colors group-hover:border-[#c5d86d]/30"
                                >
                                    <AppImage
                                        :src="product.thumb_url || ''"
                                        class="h-full w-full object-cover opacity-80 transition-opacity group-hover:opacity-100"
                                    />
                                </div>
                            </div>

                            <div class="space-y-1 text-center 2xl:text-left">
                                <div class="text-sm font-bold uppercase tracking-tight text-white">
                                    {{ product.title }}
                                </div>
                                <div
                                    class="flex items-center justify-center gap-2 2xl:justify-start"
                                >
                                    <span
                                        class="inline-flex items-center gap-1 rounded-md border border-white/5 bg-white/5 px-2 py-0.5 text-[10px] font-bold uppercase tracking-tighter text-zinc-500"
                                    >
                                        <Layers class="h-2.5 w-2.5" />
                                        {{ product.category?.title || 'General' }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-center 2xl:text-right">
                                <div
                                    class="font-mono text-base font-black italic tracking-tighter text-white"
                                >
                                    {{ formatUSD(product.price) }}
                                </div>
                            </div>

                            <div class="flex items-center justify-center gap-2">
                                <label
                                    class="relative inline-flex cursor-pointer items-center"
                                    for="is_trending"
                                >
                                    <input
                                        type="checkbox"
                                        :checked="product.is_trending"
                                        id="is_trending"
                                        @change="handleChange(product, 'is_trending', $event)"
                                        class="peer sr-only"
                                    />
                                    <div
                                        class="h-6 w-11 rounded-full border border-white/5 bg-zinc-800 after:absolute after:left-[2px] after:top-[2px] after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:bg-[#c5d86d] peer-checked:after:translate-x-full peer-checked:after:bg-[#0f120e]"
                                    ></div>
                                </label>
                                <input
                                    type="number"
                                    :value="product.trending_order"
                                    @blur="handleChange(product, 'trending_order', $event)"
                                    class="w-12 rounded-lg border border-white/5 bg-black/40 p-1 text-center font-mono text-[10px] text-zinc-400 outline-none focus:border-[#c5d86d]/50"
                                />
                            </div>

                            <div class="flex flex-col items-center gap-1">
                                <div class="relative">
                                    <input
                                        type="number"
                                        min="0"
                                        :value="product.stock"
                                        @blur="handleChange(product, 'stock', $event)"
                                        :class="[
                                            'w-20 rounded-xl border bg-black/40 py-2 text-center text-sm font-black outline-none transition-all',
                                            product.stock <= 0
                                                ? 'border-red-500/50 text-red-400'
                                                : product.stock <= 5
                                                  ? 'border-amber-500/50 text-amber-400'
                                                  : 'border-white/5 text-[#c5d86d]',
                                        ]"
                                    />
                                    <Package
                                        v-if="product.stock > 0"
                                        class="absolute -right-6 top-1/2 h-3 w-3 -translate-y-1/2 opacity-20"
                                    />
                                </div>
                                <span
                                    v-if="product.stock <= 0"
                                    class="flex animate-pulse items-center gap-1 text-[9px] font-black uppercase tracking-widest text-red-500/70"
                                >
                                    <AlertTriangle class="h-2 w-2" /> Out of Stock
                                </span>
                            </div>

                            <div class="flex justify-center gap-2 2xl:justify-end">
                                <Link
                                    :href="route('admin.products.edit', product.id)"
                                    :data="{ page: products.current_page }"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 bg-white/5 text-zinc-400 transition-all hover:border-blue-500/30 hover:bg-blue-500 hover:text-white"
                                >
                                    <Edit3 class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="deleteProduct(product.id)"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 bg-white/5 text-zinc-400 transition-all hover:border-red-500/30 hover:bg-red-500 hover:text-white"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </transition-group>
                </div>

                <div class="px-8 py-4 2xl:border-t 2xl:border-white/5 2xl:bg-white/[0.01]">
                    <Pagination :links="products.links" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
    input[type='number']::-webkit-inner-spin-button,
    input[type='number']::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .v-move {
        transition: transform 0.5s ease;
    }

    .v-leave-active {
        position: absolute;
        width: 100%;
    }
</style>
