<script setup>
import { Head, useForm, Link } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
    product: Object,
    categories: Array,
    page: [String, Number],
});

// Заполняем форму старыми данными товара
const form = useForm({
    _method: "put",
    title: props.product.title,
    description: props.product.description,
    price: props.product.price,
    category_id: props.product.category_id ?? "",
    image: null,
    is_trending: props.product?.is_trending ?? false,
    trending_order: props.product?.trending_order ?? 999,
});

const submit = () => {
    form.post(
        route("admin.products.update", {
            product: props.product.id,
            page: props.page,
        }),
    );
};
</script>

<template>
    <AdminLayout>
        <Head :title="'Редактировать ' + product.title" />

        <div class="max-w-2xl mx-auto p-6 bg-white rounded-xl shadow">
            <h1 class="text-2xl font-bold mb-6">
                Редактирование товара {{ filters }}
            </h1>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium">Название</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border rounded-lg p-2"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1"
                        >Текущее фото</label
                    >

                    <div v-if="product.image" class="mb-3">
                        <img
                            :src="product.image"
                            class="w-32 h-32 object-cover rounded-lg border shadow-sm"
                            alt="Preview"
                        />
                    </div>

                    <input
                        type="file"
                        @input="form.image = $event.target.files[0]"
                        class="w-full border rounded-lg p-2"
                        accept="image/*"
                    />

                    <p class="text-xs text-gray-500 mt-1">
                        Оставьте пустым, если не хотите менять фото
                    </p>
                    <div
                        v-if="form.errors.image"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ form.errors.image }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium">Категория</label>
                    <select
                        v-model="form.category_id"
                        class="w-full border rounded-lg p-2"
                    >
                        <option :value="null">Без категории</option>
                        <option
                            v-for="cat in categories"
                            :key="cat.id"
                            :value="cat.id"
                        >
                            {{ cat.title }}
                        </option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium">Цена</label>
                    <input
                        v-model="form.price"
                        type="number"
                        class="w-full border rounded-lg p-2"
                    />
                </div>

                <div>
                    <label class="block text-sm font-medium">Описание</label>
                    <textarea
                        v-model="form.description"
                        class="w-full border rounded-lg p-2"
                        rows="3"
                    ></textarea>
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
                            Показать в блоке "Тренды"
                        </label>
                    </div>

                    <div v-if="form.is_trending">
                        <label class="block text-sm font-medium text-gray-700"
                            >Порядок в трендах</label
                        >
                        <input
                            v-model="form.trending_order"
                            type="number"
                            placeholder="0"
                            class="mt-1 w-full border rounded-lg p-2 bg-white"
                        />
                        <p class="text-[10px] text-gray-400 mt-1">
                            Меньшее число отображается первым
                        </p>
                    </div>
                </div>

                <div class="flex justify-end space-x-2">
                    <Link
                        class="px-4 py-2 text-black rounded-lg"
                        :href="
                            route('admin.products.index', { page: props.page })
                        "
                    >
                        Отмена
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg"
                    >
                        Сохранить изменения
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
