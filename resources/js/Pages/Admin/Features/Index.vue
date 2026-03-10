<script setup lang="ts">
    import { Head, Link, router, useForm } from '@inertiajs/vue3';

    import {
        ArrowUpDown,
        ExternalLink,
        ImageIcon,
        Layers,
        Pencil,
        Plus,
        Trash2,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import AppImage from '@/Components/UI/AppImage.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Feature } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        features: Feature[];
    }>();

    const deleteFeature = (id: number): void => {
        if (confirm('Delete this slider card?')) {
            router.delete(route('admin.features.destroy', id), {
                preserveScroll: true,
            });
        }
    };
</script>

<template>
    <Head title="Slider Cards" />

    <div class="mx-auto max-w-6xl space-y-6">
        <div
            class="mb-8 flex flex-col items-start justify-between gap-4 px-2 md:mb-12 md:flex-row md:items-center"
        >
            <div>
                <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                    Slider <span class="text-[#c5d86d]">Features</span>
                </h1>
                <p class="mt-1 text-sm italic text-zinc-500">
                    Visual highlights for your homepage.
                </p>
            </div>

            <Link
                :href="route('admin.features.create')"
                class="flex w-full items-center justify-center gap-2 rounded-2xl bg-[#c5d86d] px-6 py-3 font-black text-[#0f120e] shadow-lg shadow-[#c5d86d]/10 transition-all hover:bg-[#d4e685] active:scale-95 sm:w-auto"
            >
                <Plus class="h-5 w-5" />
                ADD NEW CARD
            </Link>
        </div>

        <div class="grid grid-cols-1 gap-6 xl:grid-cols-2 2xl:grid-cols-3">
            <div
                v-for="feature in features"
                :key="feature.id"
                class="group relative overflow-hidden rounded-[2rem] border border-white/5 bg-[#161b14]/60 backdrop-blur-sm transition-all duration-300 hover:border-[#c5d86d]/30"
            >
                <div class="relative h-48 w-full overflow-hidden bg-black/40">
                    <AppImage
                        v-if="feature.image_url"
                        :src="feature.image_url"
                        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                    />
                    <div
                        v-else
                        class="flex h-full w-full flex-col items-center justify-center text-zinc-700"
                    >
                        <ImageIcon class="mb-2 h-10 w-10" />
                        <span class="text-xs font-black uppercase">No Preview</span>
                    </div>

                    <div
                        class="absolute left-4 top-4 flex items-center gap-2 rounded-full border border-white/10 bg-[#0f120e]/80 px-3 py-1 text-xs font-bold text-white backdrop-blur-md"
                    >
                        <ArrowUpDown class="h-3 w-3 text-[#c5d86d]" />
                        Order: {{ feature.order }}
                    </div>
                </div>

                <div class="p-6">
                    <h3 class="mb-4 truncate text-lg font-bold italic text-white">
                        {{ feature.title }}
                    </h3>

                    <div class="flex items-center gap-2">
                        <Link
                            :href="route('admin.features.edit', feature.id)"
                            class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-white/5 py-3 text-sm font-bold text-zinc-300 transition-all hover:bg-white/10"
                        >
                            <Pencil class="h-4 w-4" />
                            Edit
                        </Link>

                        <button
                            @click="deleteFeature(feature.id)"
                            class="rounded-xl bg-red-500/10 p-3 text-red-500 transition-all hover:bg-red-500 hover:text-white"
                        >
                            <Trash2 class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="features.length === 0"
                class="rounded-[2rem] border border-dashed border-white/5 bg-[#161b14]/20 py-20 text-center sm:col-span-2 lg:col-span-3"
            >
                <Layers class="mx-auto mb-4 h-12 w-12 text-zinc-700" />
                <p class="italic text-zinc-500">No features added yet.</p>
            </div>
        </div>
    </div>
</template>
