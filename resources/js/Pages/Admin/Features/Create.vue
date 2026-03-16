<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        AlignLeft,
        ArrowLeft,
        Hash,
        Link as LinkIcon,
        Plus,
        Sparkles,
        Type,
        Wand2,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

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
    <Head title="Create New Feature" />

    <div class="mx-auto max-w-3xl">
        <div class="mb-8 flex flex-col gap-4 px-2">
            <Link
                :href="route('admin.features.index')"
                class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-zinc-500 transition-colors hover:text-[#c5d86d]"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to list
            </Link>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white">
                Add <span class="text-[#c5d86d]">New Card</span>
            </h1>
        </div>

        <div
            class="relative overflow-hidden rounded-[2.5rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl sm:p-10"
        >
            <Wand2 class="absolute -right-4 -top-4 h-24 w-24 -rotate-12 text-[#c5d86d]/5" />

            <form @submit.prevent="submit" class="relative z-10 space-y-8">
                <div class="space-y-2">
                    <label
                        class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                        for="title"
                    >
                        <Type class="h-3 w-3 text-[#c5d86d]" /> Headline
                    </label>
                    <input
                        v-model="form.title"
                        id="title"
                        type="text"
                        required
                        class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition placeholder:text-zinc-700 focus:ring-2 focus:ring-[#c5d86d]"
                        placeholder="e.g. Autumn Collection 2026"
                    />
                    <p v-if="form.errors.title" class="mt-1 text-xs italic text-red-500">
                        {{ form.errors.title }}
                    </p>
                </div>

                <div class="space-y-2">
                    <label
                        for="description"
                        class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                    >
                        <AlignLeft class="h-3 w-3 text-[#c5d86d]" /> Content description
                    </label>
                    <textarea
                        v-model="form.description"
                        id="description"
                        rows="4"
                        class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition placeholder:text-zinc-700 focus:ring-2 focus:ring-[#c5d86d]"
                        placeholder="Describe what makes this feature special..."
                    ></textarea>
                </div>

                <div class="rounded-[2rem] border border-white/5 bg-black/20 p-2">
                    <ImageUploader
                        ref="uploader"
                        v-model="form.image"
                        label="Slider Background Image"
                        :error="form.errors.image"
                    />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="link"
                        >
                            <LinkIcon class="h-3 w-3 text-[#c5d86d]" /> Action URL
                        </label>
                        <input
                            v-model="form.link"
                            id="link"
                            type="text"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                            placeholder="https://yourstore.com/collection"
                        />
                    </div>
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="order"
                        >
                            <Hash class="h-3 w-3 text-[#c5d86d]" /> Display Order
                        </label>
                        <input
                            v-model="form.order"
                            type="number"
                            id="order"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 font-mono text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        />
                    </div>
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-[#c5d86d] py-5 font-black text-[#0f120e] transition-all hover:bg-[#d4e685] hover:shadow-lg hover:shadow-[#c5d86d]/20 active:scale-[0.98] disabled:opacity-50"
                    >
                        <Plus
                            v-if="!form.processing"
                            class="h-6 w-6 transition-transform group-hover:rotate-90"
                        />
                        <span
                            v-else
                            class="h-5 w-5 animate-spin rounded-full border-2 border-[#0f120e]/30 border-t-[#0f120e]"
                        ></span>
                        {{ form.processing ? 'PROCESSING...' : 'CREATE SLIDER CARD' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
