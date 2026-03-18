<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        AlignLeft,
        ArrowLeft,
        Hash,
        Link as LinkIcon,
        Save,
        Sparkles,
        Type,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import ImageUploader from '@/Components/UI/ImageUploader.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Feature, FeatureForm, Seo } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        seo?: Seo;
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
    <SeoMeta :seo="props.seo" />

    <div class="mx-auto max-w-3xl">
        <div class="mb-8 flex flex-col gap-4 px-2">
            <Link
                :href="route('admin.features.index')"
                class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-zinc-500 transition-colors hover:text-[#c5d86d]"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to cards
            </Link>
            <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                Edit <span class="text-[#c5d86d]">Feature</span>
            </h1>
        </div>

        <div
            class="relative overflow-hidden rounded-[2.5rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl sm:p-10"
        >
            <Sparkles class="absolute right-6 top-6 h-12 w-12 text-[#c5d86d]/5" />

            <form @submit.prevent="submit" class="space-y-8">
                <div class="space-y-2">
                    <label
                        class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        for="title"
                    >
                        <Type class="h-3 w-3" /> Card Title
                    </label>
                    <input
                        v-model="form.title"
                        id="title"
                        type="text"
                        class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white shadow-inner outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        placeholder="Enter catchy title..."
                    />
                    <p v-if="form.errors.title" class="mt-1 text-xs italic text-red-500">
                        {{ form.errors.title }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label
                        class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        for="description"
                    >
                        <AlignLeft class="h-3 w-3" /> Description
                    </label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        rows="3"
                        class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white shadow-inner outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        placeholder="Brief summary for the slider..."
                    ></textarea>
                </div>

                <div class="rounded-3xl border border-white/5 bg-black/20 p-6">
                    <ImageUploader
                        ref="uploader"
                        v-model="form.image"
                        label="Card Visual Image"
                        :error="form.errors.image"
                        :existingImage="feature.image_url"
                        @on-file-select="handleFileSelect"
                    />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="link"
                        >
                            <LinkIcon class="h-3 w-3" /> Redirect URL
                        </label>
                        <input
                            v-model="form.link"
                            id="link"
                            type="text"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white shadow-inner outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                            placeholder="https://..."
                        />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="order"
                        >
                            <Hash class="h-3 w-3" /> Sorting Order
                        </label>
                        <input
                            v-model="form.order"
                            id="order"
                            type="number"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 font-mono text-[#c5d86d] text-white shadow-inner outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        />
                    </div>
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex w-full items-center justify-center gap-3 rounded-2xl bg-[#c5d86d] py-5 font-black text-[#0f120e] transition-all hover:bg-[#d4e685] hover:shadow-lg hover:shadow-[#c5d86d]/20 active:scale-[0.98] disabled:opacity-50"
                    >
                        <Save v-if="!form.processing" class="h-5 w-5" />
                        <span
                            v-else
                            class="h-5 w-5 animate-spin rounded-full border-2 border-[#0f120e]/30 border-t-[#0f120e]"
                        ></span>
                        {{ form.processing ? 'SAVING CHANGES...' : 'UPDATE FEATURE CARD' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
