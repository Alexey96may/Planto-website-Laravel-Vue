<script setup lang="ts">
    import { ref } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import ImageUploader from '@/Components/Shared/ImageUploader.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Feature, FeatureForm } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        feature: Feature;
    }>();

    const form = useForm<FeatureForm>({
        _method: 'put',
        title: props.feature.title,
        description: props.feature.description,
        link: props.feature.link,
        order: props.feature.order,
        image: null,
    });

    const uploader = ref<InstanceType<typeof ImageUploader> | null>(null);

    const submit = () => {
        form.post(route('admin.features.update', props.feature.id), {
            onSuccess: () => {
                uploader.value?.clearImage();
            },
        });
    };

    const handleFileSelect = (file: File | null) => {
        form.image = file;
    };
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded">
        <h1 class="text-xl font-bold mb-4">Edit: {{ feature.title }}</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block">Title</label>
                <input v-model="form.title" type="text" class="w-full border p-2 rounded" />
            </div>

            <div>
                <label class="block">Description</label>
                <textarea v-model="form.description" class="w-full border p-2 rounded"></textarea>
            </div>

            <ImageUploader
                ref="uploader"
                v-model="form.image"
                label="Change photo:"
                :error="form.errors.image"
                :existingImage="feature.image_url"
                @on-file-select="handleFileSelect"
            ></ImageUploader>

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
                class="bg-blue-500 text-white px-6 py-2 rounded"
            >
                {{ form.processing ? 'Refreshing...' : 'Refresh card' }}
            </button>
        </form>
    </div>
</template>
