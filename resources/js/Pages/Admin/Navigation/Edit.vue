<script setup>
    import { computed } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import AdminLayout from '@/Layouts/AdminLayout.vue';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps({
        item: Object, // Текущий редактируемый пункт
        parentOptions: Array, // Список возможных родителей (уже отфильтрован в контроллере)
        categories: Array,
    });

    const form = useForm({
        title: props.item.title,
        type: props.item.type || 'link',
        category_id: props.item.category_id,
        link: props.item.link,
        location: props.item.location,
        parent_id: props.item.parent_id,
        order: props.item.order,
        is_active: Boolean(props.item.is_active),
    });

    // Фильтруем родителей по локации (как и в Create)
    const filteredParents = computed(() => {
        return props.parentOptions.filter((p) => p.location === form.location);
    });

    const submit = () => {
        // В Laravel для обновления через Resource используется метод PUT или PATCH
        form.put(route('admin.navigation.update', props.item.id));
    };
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Редактирование: {{ item.title }}</h1>

        <form @submit.prevent="submit" class="max-w-2xl bg-white p-6 rounded shadow">
            <div v-if="form.errors.parent_id" class="mb-4 text-red-600 text-sm">
                {{ form.errors.parent_id }}
            </div>

            <div class="grid gap-4">
                <div>
                    <label class="block font-medium">Название пункта</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border p-2 rounded"
                        required
                    />
                </div>

                <div>
                    <label class="block font-medium">Где отображать?</label>
                    <select v-model="form.location" class="w-full border p-2 rounded">
                        <option value="header">Header (Шапка)</option>
                        <option value="footer">Footer (Подвал)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Тип ссылки</label>
                    <select
                        v-model="form.type"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    >
                        <option value="link">Произвольная ссылка</option>
                        <option value="category">Категория товаров</option>
                    </select>
                </div>

                <div v-if="form.type === 'category'">
                    <label class="block text-sm font-medium text-gray-700"
                        >Выберите категорию</label
                    >
                    <select
                        v-model="form.category_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    >
                        <option :value="null">-- Не выбрано --</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.title }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        Ссылка будет сформирована автоматически на основе слага категории.
                    </p>
                </div>

                <div v-if="form.type === 'link'">
                    <label class="block text-sm font-medium text-gray-700">URL ссылки</label>
                    <input
                        v-model="form.link"
                        type="text"
                        placeholder="/contacts"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>

                <div v-if="filteredParents.length">
                    <label class="block font-medium text-gray-700">Родительский пункт</label>
                    <select v-model="form.parent_id" class="w-full border p-2 rounded">
                        <option :value="null">Без родителя (корневой уровень)</option>
                        <option
                            v-for="parent in filteredParents"
                            :key="parent.id"
                            :value="parent.id"
                        >
                            {{ parent.title }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        * Нельзя сделать родителем этот же пункт меню.
                    </p>
                </div>

                <div>
                    <label class="block font-medium">Порядок сортировки</label>
                    <input v-model="form.order" type="number" class="w-full border p-2 rounded" />
                </div>

                <div class="flex items-center">
                    <input
                        id="is_active"
                        v-model="form.is_active"
                        type="checkbox"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded"
                    />
                    <label for="is_active" class="ml-2 block text-sm text-gray-900"
                        >Показывать на сайте</label
                    >
                </div>

                <div class="flex justify-between items-center mt-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700"
                    >
                        Обновить
                    </button>

                    <button
                        type="button"
                        @click="$window.history.back()"
                        class="text-gray-500 underline"
                    >
                        Отмена
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
