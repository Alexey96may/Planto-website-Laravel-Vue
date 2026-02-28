<script setup lang="ts">
    import { ref } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import ImageUploader from '@/Components/Shared/ImageUploader.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { FeatureForm } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const form = useForm<FeatureForm>({
        title: '',
        description: '',
        link: '',
        order: 0,
        image: null,
    });

    const uploader = ref<InstanceType<typeof ImageUploader> | null>(null);

    const submit = () => {
        form.post(route('admin.features.store'), {
            onSuccess: () => {
                form.reset();
                uploader.value?.clearImage();
            },
        });
    };
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded">
        <h1 class="text-xl font-bold mb-4">New card</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block">Headline</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border p-2 rounded"
                    required
                />
                <div v-if="form.errors.title" class="text-red-500 text-sm">
                    {{ form.errors.title }}
                </div>
            </div>

            <div>
                <label class="block">Description (Text or HTML)</label>
                <textarea
                    v-model="form.description"
                    class="w-full border p-2 rounded"
                    rows="4"
                ></textarea>
            </div>

            <ImageUploader
                ref="uploader"
                v-model="form.image"
                label="Feature image"
                :error="form.errors.image"
            />

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block">Link (URL)</label>
                    <input v-model="form.link" type="text" class="w-full border p-2 rounded" />
                </div>
                <div class="w-1/2">
                    <label class="block">Order (Number)</label>
                    <input v-model="form.order" type="number" class="w-full border p-2 rounded" />
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-green-500 text-white px-6 py-2 rounded hover:bg-green-600 disabled:bg-gray-400"
            >
                {{ form.processing ? 'Creating...' : 'Create card' }}
            </button>
        </form>
    </div>
</template>
