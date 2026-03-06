<script setup lang="ts">
    import { ref } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import ImageUploader from '@/Components/UI/ImageUploader.vue';
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
    <div class="mx-auto max-w-2xl rounded bg-white p-6 shadow">
        <h1 class="mb-4 text-xl font-bold">New card</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block">Headline</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full rounded border p-2"
                    required
                />
                <div v-if="form.errors.title" class="text-sm text-red-500">
                    {{ form.errors.title }}
                </div>
            </div>

            <div>
                <label class="block">Description (Text or HTML)</label>
                <textarea
                    v-model="form.description"
                    class="w-full rounded border p-2"
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
                    <input v-model="form.link" type="text" class="w-full rounded border p-2" />
                </div>
                <div class="w-1/2">
                    <label class="block">Order (Number)</label>
                    <input v-model="form.order" type="number" class="w-full rounded border p-2" />
                </div>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="rounded bg-green-500 px-6 py-2 text-white hover:bg-green-600 disabled:bg-gray-400"
            >
                {{ form.processing ? 'Creating...' : 'Create card' }}
            </button>
        </form>
    </div>
</template>
