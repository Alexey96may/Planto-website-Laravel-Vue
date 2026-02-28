<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import {
        CategoryNavigationCreate,
        NavigationCreate,
        NavigationEdit,
        NavigationForm,
    } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        item: NavigationEdit;
        parentOptions: NavigationCreate[];
        categories: CategoryNavigationCreate[];
    }>();

    const form = useForm<NavigationForm>({
        title: props.item.title,
        type: props.item.type || 'link',
        category_id: props.item.category_id,
        link: props.item.link,
        location: props.item.location,
        parent_id: props.item.parent_id,
        order: props.item.order,
        is_active: Boolean(props.item.is_active),
    });

    const filteredParents = computed<NavigationCreate[]>(() => {
        return props.parentOptions.filter((p: NavigationCreate) => p.location === form.location);
    });

    const submit = () => {
        if (form.type === 'link') {
            form.category_id = null;
        } else if (form.type === 'category') {
            form.link = '';
        }

        form.put(route('admin.navigation.update', props.item.id), {
            preserveScroll: true,
            onSuccess: () => {
                // todo Здесь можно добавить вызов Toast-уведомления
            },
        });
    };
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-6">Editing: {{ item.title }}</h1>

        <form @submit.prevent="submit" class="max-w-2xl bg-white p-6 rounded shadow">
            <div v-if="form.errors.parent_id" class="mb-4 text-red-600 text-sm">
                {{ form.errors.parent_id }}
            </div>

            <div class="grid gap-4">
                <div>
                    <label class="block font-medium">Title</label>
                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border p-2 rounded"
                        required
                    />
                </div>

                <div>
                    <label class="block font-medium">Where to display?</label>
                    <select v-model="form.location" class="w-full border p-2 rounded">
                        <option value="header">Header</option>
                        <option value="footer">Footer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Link type</label>
                    <select
                        v-model="form.type"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    >
                        <option value="link">Custom link</option>
                        <option value="category">Product category</option>
                    </select>
                </div>

                <div v-if="form.type === 'category'">
                    <label class="block text-sm font-medium text-gray-700">Select a category</label>
                    <select
                        v-model="form.category_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    >
                        <option :value="null">-- Not selected --</option>
                        <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                            {{ cat.title }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        The link will be generated automatically based on the category slug.
                    </p>
                </div>

                <div v-if="form.type === 'link'">
                    <label class="block text-sm font-medium text-gray-700">URL links</label>
                    <input
                        v-model="form.link"
                        type="text"
                        placeholder="/contacts"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                    />
                </div>

                <div v-if="filteredParents.length">
                    <label class="block font-medium text-gray-700">Parental point</label>
                    <select v-model="form.parent_id" class="w-full border p-2 rounded">
                        <option :value="null">Without parent (root level)</option>
                        <option
                            v-for="parent in filteredParents"
                            :key="parent.id"
                            :value="parent.id"
                        >
                            {{ parent.title }}
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        * You cannot make the same menu item a parent.
                    </p>
                </div>

                <div>
                    <label class="block font-medium">Sorting order</label>
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
                        >Show on website</label
                    >
                </div>

                <div class="flex justify-between items-center mt-6">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700"
                    >
                        Refresh
                    </button>

                    <Link :href="route('admin.navigation.index')" class="text-gray-500 underline">
                        Back to list
                    </Link>
                </div>
            </div>
        </form>
    </div>
</template>
