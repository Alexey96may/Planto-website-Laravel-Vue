<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, Link, router } from '@inertiajs/vue3';

    import {
        CheckCircle2,
        Circle,
        GripVertical,
        Link2,
        MapPin,
        Pencil,
        Plus,
        Save,
        Trash2,
    } from 'lucide-vue-next';
    import draggable from 'vuedraggable';
    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import { NavigationItem } from '@/types';

    defineOptions({ layout: AdminLayout });

    const props = defineProps<{
        menuItems: NavigationItem[];
    }>();

    const list = ref([...props.menuItems]);
    const isSaving = ref(false);

    const saveOrder = () => {
        isSaving.value = true;
        const orders = list.value.map((item, index) => ({
            id: item.id,
            position: index,
        }));

        router.post(
            route('admin.navigation.reorder'),
            { orders },
            {
                preserveScroll: true,
                onFinish: () => (isSaving.value = false),
            },
        );
    };

    const { notifyWithUndo } = useFlash();

    let isDeleting = ref<number | null>(null);

    const deleteItem = async (id: number): Promise<void> => {
        if (isDeleting.value === id) return;

        isDeleting.value = id;
        try {
            const confirmed = await notifyWithUndo(
                'Purging Navigation item from the core...',
                5000,
            );

            if (confirmed) {
                router.delete(route('admin.navigation.destroy', id), {
                    preserveScroll: true,
                    onFinish: () => {
                        isDeleting.value = null;
                    },
                    onError: () => {
                        isDeleting.value = null;
                    },
                });
            } else {
                isDeleting.value = null;
            }
        } catch (e) {
            isDeleting.value = null;
        }
    };
</script>

<template>
    <Head title="Menu Management" />

    <div class="mx-auto max-w-5xl space-y-6">
        <div
            class="flex flex-col flex-wrap items-start justify-between gap-4 px-2 xl:flex-row xl:items-center"
        >
            <div>
                <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                    Menu <span class="text-[#c5d86d]">Architect</span>
                </h1>
                <p class="mt-1 text-sm italic text-zinc-500">Drag to reorder, click to edit.</p>
            </div>

            <div class="flex w-full flex-wrap items-center gap-3">
                <button
                    @click="saveOrder"
                    :disabled="isSaving"
                    class="flex flex-1 items-center justify-center gap-2 rounded-2xl border border-[#c5d86d]/20 bg-white/5 px-6 py-3 font-black text-[#c5d86d] transition-all hover:bg-white/10 disabled:opacity-50 sm:flex-none"
                >
                    <Save class="h-4 w-4" :class="{ 'animate-pulse': isSaving }" />
                    {{ isSaving ? 'SAVING...' : 'SAVE ORDER' }}
                </button>

                <Link
                    :href="route('admin.navigation.create')"
                    class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#c5d86d] px-6 py-3 font-black text-[#0f120e] shadow-lg shadow-[#c5d86d]/10 transition-all hover:bg-[#d4e685] sm:flex-none"
                >
                    <Plus class="h-5 w-5" />
                    ADD ITEM
                </Link>
            </div>
        </div>

        <div
            class="overflow-x-auto rounded-[1.5rem] border border-white/5 bg-[#161b14]/60 backdrop-blur-sm lg:rounded-[2.5rem]"
        >
            <div
                class="hidden grid-cols-12 gap-4 border-b border-white/5 bg-white/[0.02] p-6 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500 md:grid"
            >
                <div class="col-span-1"></div>
                <div class="col-span-4">Label & Path</div>
                <div class="col-span-3 text-center">Location</div>
                <div class="col-span-2 text-center">Status</div>
                <div class="col-span-2 text-right">Actions</div>
            </div>

            <draggable
                v-model="list"
                item-key="id"
                handle=".drag-handle"
                ghost-class="opacity-0"
                drag-class="scale-[1.02]"
                class="divide-y divide-white/5"
            >
                <template #item="{ element }">
                    <div class="group">
                        <div
                            class="grid grid-cols-1 items-center gap-y-4 p-5 transition-colors hover:bg-white/[0.03] md:grid-cols-12 md:gap-4 md:p-6"
                        >
                            <div
                                class="col-span-1 flex items-center justify-between md:justify-start"
                            >
                                <div
                                    class="drag-handle cursor-grab rounded-xl bg-white/5 p-2 text-zinc-600 hover:text-[#c5d86d] active:cursor-grabbing"
                                >
                                    <GripVertical class="h-5 w-5" />
                                </div>
                                <div class="md:hidden">
                                    <CheckCircle2
                                        v-if="element.is_active"
                                        class="h-5 w-5 text-emerald-500"
                                    />
                                    <Circle v-else class="h-5 w-5 text-zinc-800" />
                                </div>
                            </div>

                            <div class="col-span-1 flex flex-col md:col-span-4">
                                <div
                                    class="text-lg font-bold uppercase italic tracking-tight text-white md:text-base md:normal-case"
                                >
                                    {{ element.title }}
                                </div>
                                <div
                                    class="mt-1 flex w-fit items-center gap-1 rounded bg-black/30 px-2 py-0.5 font-mono text-[10px] text-zinc-500 md:bg-transparent md:p-0"
                                >
                                    <Link2 class="h-3 w-3" />
                                    {{ element.link || '/shop' }}
                                </div>
                            </div>

                            <div class="col-span-1 flex md:col-span-3 md:justify-center">
                                <span
                                    class="flex items-center gap-2 rounded-lg border border-white/5 bg-black/40 px-3 py-1.5 text-[10px] font-black uppercase tracking-widest text-zinc-400"
                                >
                                    <MapPin class="h-3 w-3 text-[#c5d86d]" />
                                    {{ element.location }}
                                </span>
                            </div>

                            <div class="col-span-2 hidden justify-center md:flex">
                                <CheckCircle2
                                    v-if="element.is_active"
                                    class="h-5 w-5 text-emerald-500"
                                />
                                <Circle v-else class="h-5 w-5 text-zinc-800" />
                            </div>

                            <div class="col-span-1 mt-2 flex gap-2 md:col-span-2 md:mt-0">
                                <Link
                                    :href="route('admin.navigation.edit', element.id)"
                                    class="flex flex-1 justify-center rounded-xl border border-white/5 bg-white/5 p-3 text-zinc-500 transition-all hover:text-[#c5d86d] md:flex-none"
                                >
                                    <Pencil class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="deleteItem(element.id)"
                                    class="flex flex-1 justify-center rounded-xl border border-white/5 bg-white/5 p-3 text-zinc-600 transition-all hover:text-red-500 md:flex-none"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>

                        <div
                            v-if="element.children?.length"
                            class="ml-4 border-t border-white/[0.02] bg-black/20 md:ml-0"
                        >
                            <div
                                v-for="child in element.children"
                                :key="child.id"
                                class="grid grid-cols-1 items-center gap-3 border-b border-white/[0.02] p-4 pl-8 last:border-0 md:grid-cols-12 md:gap-4 md:pl-20"
                            >
                                <div class="col-span-1 flex items-center gap-3 md:col-span-5">
                                    <span class="italic text-zinc-600">↳</span>
                                    <div>
                                        <div class="text-sm font-bold italic text-zinc-300">
                                            {{ child.title }}
                                        </div>
                                        <div
                                            class="mt-0.5 font-mono text-[9px] italic text-zinc-600"
                                        >
                                            {{ child.link || '/shop' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-1 opacity-60 md:col-span-3">
                                    <span
                                        class="flex items-center gap-1 text-[9px] font-bold uppercase tracking-tighter text-zinc-500"
                                    >
                                        <MapPin class="h-2 w-2" /> {{ child.location }}
                                    </span>
                                </div>

                                <div class="col-span-1 flex justify-end gap-4 md:col-span-4">
                                    <Link
                                        :href="route('admin.navigation.edit', child.id)"
                                        class="text-xs font-black uppercase tracking-widest text-zinc-600 hover:text-[#c5d86d]"
                                        >Edit</Link
                                    >
                                    <button
                                        @click="deleteItem(child.id)"
                                        class="text-xs font-black uppercase tracking-widest text-zinc-700 hover:text-red-500"
                                    >
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>
        </div>

        <div
            v-if="list.length === 0"
            class="rounded-[2.5rem] border border-dashed border-white/5 bg-[#161b14]/20 py-20 text-center"
        >
            <MapPin class="mx-auto mb-4 h-12 w-12 text-zinc-800" />
            <p class="italic text-zinc-600">No navigation items found. Start by adding one.</p>
        </div>
    </div>
</template>
