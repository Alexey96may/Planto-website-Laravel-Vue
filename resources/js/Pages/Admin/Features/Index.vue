<script setup lang="ts">
    import { Link, router, useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Feature } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        features: Feature[];
    }>();

    const deleteForm = useForm({});

    const deleteFeature = (id: number): void => {
        if (confirm('Delete this card?')) {
            router.delete(route('admin.features.destroy', id), {
                preserveScroll: true,
                onSuccess: () => {
                    // Можно вызвать уведомление или лог //todo
                    console.log('Removed successfully!');
                },
            });
        }
    };
</script>

<template>
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Slider cards</h1>
            <Link
                :href="route('admin.features.create')"
                class="bg-blue-500 text-white px-4 py-2 rounded"
            >
                + Add a card
            </Link>
        </div>

        <table class="w-full border-collapse">
            <thead>
                <tr class="border-b">
                    <th class="text-left p-2">Photo</th>
                    <th class="text-left p-2">Title</th>
                    <th class="text-left p-2">Order</th>
                    <th class="text-left p-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="feature in features" :key="feature.id" class="border-b hover:bg-gray-50">
                    <td class="p-2">
                        <img
                            v-if="feature.image_url"
                            :src="feature.image_url"
                            class="w-16 h-10 object-cover rounded"
                        />
                        <span v-else class="text-gray-400 text-xs">No photo</span>
                    </td>
                    <td class="p-2">{{ feature.title }}</td>
                    <td class="p-2">{{ feature.order }}</td>
                    <td class="p-2">
                        <Link
                            :href="route('admin.features.edit', feature.id)"
                            class="text-blue-600 mr-3"
                            >Change</Link
                        >
                        <button
                            @click="deleteFeature(feature.id)"
                            :disabled="deleteForm.processing"
                            class="text-red-600"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
