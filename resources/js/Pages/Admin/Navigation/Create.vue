<script setup>
    import { computed } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import AdminLayout from '@/Layouts/AdminLayout.vue';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps({
        parentOptions: Array,
        categories: Array,
    });

    const form = useForm({
        title: '',
        location: 'header',
        parent_id: null,
        order: 0,
        is_active: true,
        type: props.navigation?.type || 'link',
        category_id: props.navigation?.category_id || null,
        link: props.navigation?.link || '',
    });

    // Фильтруем родителей: показываем только тех, кто в той же локации, что и новый пункт
    const filteredParents = computed(() => {
        return props.parentOptions.filter((p) => p.location === form.location);
    });

    const submit = () => {
        form.post(route('admin.navigation.store'));
    };
</script>

<template>
    <form @submit.prevent="submit" class="max-w-2xl bg-white p-6 rounded shadow">
        <div class="grid gap-4">
            <div>
                <label class="block">Название пункта</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border p-2 rounded"
                    required
                />
            </div>

            <div>
                <label class="block">Где отображать?</label>
                <select v-model="form.location" class="w-full border p-2 rounded">
                    <option value="header">Header (Шапка)</option>
                    <option value="footer">Footer (Подвал)</option>
                </select>
            </div>

            <div>
                <label>Тип пункта</label>
                <select v-model="form.type" class="w-full border p-2">
                    <option value="link">Обычная ссылка</option>
                    <option value="category">Категория товаров</option>
                </select>
            </div>

            <div v-if="form.type === 'link'">
                <label class="block">Ссылка (URL или /path)</label>
                <input
                    v-model="form.link"
                    placeholder="/about-us"
                    class="w-full border p-2 rounded"
                />
            </div>

            <div v-if="form.type === 'category'">
                <label>Выберите категорию</label>
                <select v-model="form.category_id" class="w-full border p-2">
                    <option :value="null">Выберите из списка...</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                        {{ cat.title }}
                    </option>
                </select>
            </div>

            <div v-if="filteredParents.length">
                <label class="block">Родительский пункт (опционально)</label>
                <select v-model="form.parent_id" class="w-full border p-2 rounded">
                    <option :value="null">Без родителя (корневой уровень)</option>
                    <option v-for="parent in filteredParents" :key="parent.id" :value="parent.id">
                        {{ parent.title }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block">Порядок сортировки</label>
                <input v-model="form.order" type="number" class="w-full border p-2 rounded" />
            </div>

            <div class="flex items-center">
                <input
                    id="is_active"
                    v-model="form.is_active"
                    type="checkbox"
                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                />
                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                    Опубликовать (будет видна на сайте)
                </label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-green-600 text-white p-2 rounded"
            >
                Сохранить пункт меню
            </button>
        </div>
    </form>
</template>
