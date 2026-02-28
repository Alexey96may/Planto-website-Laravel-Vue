<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import ImageUploader from '@/Components/Shared/ImageUploader.vue';
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
                    // todo Toast: "Товар обновлен"
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
        <Head :title="'Edit ' + product.title" />

        <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow">
            <h1 class="text-2xl font-bold mb-6">Edit Product: {{ product.title }}</h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Title</label>
                    <input v-model="form.title" type="text" class="w-full border rounded-lg p-2" />
                </div>

                <ImageUploader
                    ref="uploader"
                    v-model="form.image"
                    label="Change photo:"
                    :error="form.errors.image"
                    :existingImage="product.image_url"
                    @on-file-select="handleFileSelect"
                ></ImageUploader>

                <div>
                    <label class="block text-sm font-medium">Category</label>
                    <select v-model="form.category_id" class="w-full border rounded-lg p-2">
                        <option :value="null">Uncategorized</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.title }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Price</label>
                    <input
                        v-model="form.price"
                        type="number"
                        class="w-full border rounded-lg p-2"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium">Description</label>
                    <textarea
                        v-model="form.description"
                        class="w-full border rounded-lg p-2"
                        rows="3"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Stock Quantity</label>
                    <input
                        type="number"
                        v-model="form.stock"
                        min="0"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500"
                        :class="{ 'border-red-500 bg-red-50': form.stock <= 0 }"
                    />
                    <div v-if="form.errors.stock" class="text-red-500 text-xs mt-1">
                        {{ form.errors.stock }}
                    </div>
                    <p
                        v-if="form.stock <= 0"
                        class="text-red-500 text-[10px] mt-1 uppercase font-bold"
                    >
                        Product will be hidden from the website
                    </p>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200"
                >
                    <div class="flex items-center space-x-3">
                        <input
                            id="is_trending"
                            v-model="form.is_trending"
                            type="checkbox"
                            class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                        />
                        <label
                            for="is_trending"
                            class="text-sm font-medium text-gray-700 select-none cursor-pointer"
                        >
                            Show in "Trending" block
                        </label>
                    </div>

                    <div v-if="form.is_trending">
                        <label class="block text-sm font-medium text-gray-700"
                            >Trending Priority</label
                        >
                        <input
                            v-model="form.trending_order"
                            type="number"
                            placeholder="0"
                            class="mt-1 w-full border rounded-lg p-2 bg-white"
                        />
                        <p class="text-[10px] text-gray-400 mt-1">Lower numbers appear first</p>
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <Link
                        class="px-4 py-2 text-black rounded-lg"
                        :href="route('admin.products.index', { page: props.page })"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                    >
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
