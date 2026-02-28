<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, useForm } from '@inertiajs/vue3';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Category, CategoryForm } from '@/types';

    const props = defineProps<{
        categories: Category[];
    }>();

    const isEditing = ref<Category | null>(null);

    const form = useForm<CategoryForm>({
        title: '',
        slug: '',
    });

    const submit = () => {
        if (isEditing.value) {
            form.put(route('admin.categories.update', isEditing.value), {
                onSuccess: () => cancelEdit(),
            });
        } else {
            form.post(route('admin.categories.store'), {
                onSuccess: () => form.reset(),
            });
        }
    };

    const editCategory = (category: Category) => {
        isEditing.value = category;
        form.title = category.title;
        form.slug = category.slug;
    };

    const cancelEdit = () => {
        isEditing.value = null;
        form.reset();
    };

    const deleteCategory = (category: Category) => {
        if (confirm(`Delete category "${category.title}"?`)) {
            form.delete(route('admin.categories.destroy', category.id));
        }
    };
</script>

<template>
    <AdminLayout>
        <Head title="Category management" />

        <div class="p-8 max-w-4xl mx-auto">
            <h1 class="text-2xl font-bold mb-6">Product categories</h1>

            <div class="bg-white p-6 rounded-xl shadow-sm border mb-8">
                <h2 class="font-semibold mb-4 text-gray-700">
                    {{ isEditing ? 'Edit category' : 'Add a new category' }}
                </h2>
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1"
                                >Title</label
                            >
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full border rounded-lg p-2"
                                placeholder="For example: Cacti"
                            />
                            <div v-if="form.errors.title" class="text-red-500 text-xs mt-1">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <div v-if="isEditing" class="flex-grow">
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1"
                                >Slug (URL)</label
                            >
                            <input
                                v-model="form.slug"
                                type="text"
                                class="w-full border rounded-lg p-2 bg-gray-50"
                            />
                            <div v-if="form.errors.slug" class="text-red-500 text-xs mt-1">
                                {{ form.errors.slug }}
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button
                            type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition"
                        >
                            {{ isEditing ? 'Refresh' : 'Create' }}
                        </button>
                        <button
                            v-if="isEditing"
                            @click="cancelEdit"
                            type="button"
                            class="px-6 py-2 text-gray-500 hover:underline"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-xl shadow-sm border overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="p-4 font-bold text-gray-600">Title</th>
                            <th class="p-4 font-bold text-gray-600">Slug</th>
                            <th class="p-4 text-right font-bold text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="category in categories"
                            :key="category.id"
                            class="border-b last:border-0 hover:bg-gray-50 transition"
                        >
                            <td class="p-4 font-medium text-sky-500">
                                {{ category.title }}
                            </td>
                            <td class="p-4 text-gray-400 text-sm italic">
                                {{ category.slug }}
                            </td>
                            <td class="p-4 text-right space-x-3">
                                <button
                                    @click="editCategory(category)"
                                    class="text-blue-600 hover:underline text-sm"
                                >
                                    Change
                                </button>
                                <button
                                    @click="deleteCategory(category)"
                                    class="text-red-500 hover:underline text-sm"
                                >
                                    Delete
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
