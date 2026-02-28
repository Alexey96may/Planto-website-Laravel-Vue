<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { CategoryNavigationCreate, NavigationCreate, NavigationForm } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        parentOptions: NavigationCreate[];
        categories: CategoryNavigationCreate[];
    }>();

    const form = useForm<NavigationForm>({
        title: '',
        location: 'header',
        parent_id: null,
        order: 0,
        is_active: true,
        type: 'link',
        category_id: null,
        link: '',
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

        form.post(route('admin.navigation.store'), {
            onSuccess: () => {
                // todo
            },
            onError: (errors) => {
                console.error('Error saving:', errors);
            },
            preserveScroll: true,
        });
    };
</script>

<template>
    <form @submit.prevent="submit" class="max-w-2xl bg-white p-6 rounded shadow">
        <div class="grid gap-4">
            <div>
                <label class="block">Title</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border p-2 rounded"
                    required
                />
            </div>

            <div>
                <label class="block">Where to display?</label>
                <select v-model="form.location" class="w-full border p-2 rounded">
                    <option value="header">Header</option>
                    <option value="footer">Footer</option>
                </select>
            </div>

            <div>
                <label>Item type</label>
                <select v-model="form.type" class="w-full border p-2">
                    <option value="link">Regular link</option>
                    <option value="category">Product category</option>
                </select>
            </div>

            <div v-if="form.type === 'link'">
                <label class="block">Link (URL or /path)</label>
                <input
                    v-model="form.link"
                    placeholder="/about-us"
                    class="w-full border p-2 rounded"
                />
            </div>

            <div v-if="form.type === 'category'">
                <label>Select a category</label>
                <select v-model="form.category_id" class="w-full border p-2">
                    <option :value="null">Select from the list...</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                        {{ cat.title }}
                    </option>
                </select>
            </div>

            <div v-if="filteredParents.length">
                <label class="block">Parental point (optional)</label>
                <select v-model="form.parent_id" class="w-full border p-2 rounded">
                    <option :value="null">Without parent (root level)</option>
                    <option v-for="parent in filteredParents" :key="parent.id" :value="parent.id">
                        {{ parent.title }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block">Sorting order</label>
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
                    Publish (will be visible on the website)
                </label>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-green-600 text-white p-2 rounded"
            >
                {{ form.processing ? 'Saving...' : 'Save menu item' }}
            </button>
            <Link :href="route('admin.navigation.index')" class="text-gray-500 underline">
                Back to list
            </Link>
        </div>
    </form>
</template>
