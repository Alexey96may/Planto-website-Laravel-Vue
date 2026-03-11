<script setup lang="ts">
    import { ref } from 'vue';

    import { Head, useForm } from '@inertiajs/vue3';

    import { Check, Hash, LayoutList, Pencil, Plus, RefreshCcw, Trash2, X } from 'lucide-vue-next';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { useFlash } from '@/composables/useFlash';
    import { Category, CategoryForm } from '@/types';

    const props = defineProps<{
        categories: Category[];
    }>();

    const { notifyWithUndo } = useFlash();

    const isEditing = ref<Category | null>(null);

    const form = useForm<CategoryForm>({
        title: '',
        slug: '',
    });

    const submit = () => {
        if (isEditing.value) {
            form.put(route('admin.categories.update', isEditing.value.id), {
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
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const cancelEdit = () => {
        isEditing.value = null;
        form.reset();
    };

    let isDeleting = ref<number | null>(null);

    const deleteCategory = async (category: Category) => {
        if (isDeleting.value === category.id) return;

        isDeleting.value = category.id;

        try {
            const confirmed = await notifyWithUndo('Purging category from core...', 5000);

            if (confirmed) {
                form.delete(route('admin.categories.destroy', category.id), {
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
    <AdminLayout>
        <Head title="Categories" />

        <div class="mx-auto max-w-5xl space-y-6">
            <div class="flex items-center justify-between px-2">
                <h1 class="text-3xl font-black uppercase italic tracking-tighter text-white">
                    Categories <span class="text-[#c5d86d]">Lab</span>
                </h1>
                <div
                    class="hidden items-center gap-2 rounded-full border border-white/5 bg-white/5 px-4 py-2 text-xs font-bold uppercase tracking-widest text-zinc-500 sm:flex"
                >
                    <LayoutList class="h-3.5 w-3.5" />
                    Items: {{ categories.length }}
                </div>
            </div>

            <div
                class="relative overflow-hidden rounded-[2rem] border-2 bg-[#161b14] p-6 shadow-2xl transition-all duration-500 sm:p-8"
                :class="
                    isEditing ? 'border-[#c5d86d]/40 ring-4 ring-[#c5d86d]/5' : 'border-white/5'
                "
            >
                <div class="mb-6 flex items-center gap-3">
                    <div
                        :class="
                            isEditing
                                ? 'bg-[#c5d86d] text-[#0f120e]'
                                : 'bg-[#c5d86d]/10 text-[#c5d86d]'
                        "
                        class="rounded-xl p-2.5 transition-colors"
                    >
                        <Plus v-if="!isEditing" class="h-5 w-5" />
                        <RefreshCcw v-else class="animate-spin-slow h-5 w-5" />
                    </div>
                    <h2 class="text-xl font-bold italic tracking-tight text-white">
                        {{ isEditing ? 'MODIFY CATEGORY' : 'NEW CATEGORY' }}
                    </h2>
                </div>

                <form
                    @submit.prevent="submit"
                    class="grid grid-cols-1 items-end gap-6 md:grid-cols-2"
                >
                    <div class="space-y-4 md:col-span-1">
                        <div>
                            <label
                                class="mb-2 ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                >Category Title</label
                            >
                            <div class="group relative">
                                <input
                                    v-model="form.title"
                                    type="text"
                                    required
                                    class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                                    placeholder="e.g. Rare Succulents"
                                />
                            </div>
                            <p v-if="form.errors.title" class="mt-2 text-xs italic text-red-500">
                                {{ form.errors.title }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-4 md:col-span-1">
                        <div v-if="isEditing">
                            <label
                                class="mb-2 ml-1 block text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                                >URL Slug (System)</label
                            >
                            <div class="relative">
                                <input
                                    v-model="form.slug"
                                    type="text"
                                    class="w-full rounded-2xl border border-white/10 bg-[#0f120e]/50 p-4 text-zinc-400 outline-none transition focus:ring-1 focus:ring-[#c5d86d]"
                                />
                                <Hash
                                    class="absolute right-4 top-1/2 h-4 w-4 -translate-y-1/2 text-zinc-700"
                                />
                            </div>
                        </div>
                        <div v-else class="hidden md:block">
                            <div
                                class="flex h-[76px] items-center px-4 text-sm italic text-zinc-600"
                            >
                                Slug will be generated automatically...
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row md:col-span-2">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-[#c5d86d] py-4 font-black text-[#0f120e] transition-all hover:bg-[#d4e685] hover:shadow-lg hover:shadow-[#c5d86d]/20 active:scale-[0.98] disabled:opacity-50"
                        >
                            <Check v-if="!form.processing" class="h-5 w-5" />
                            {{ isEditing ? 'SAVE CHANGES' : 'CREATE CATEGORY' }}
                        </button>

                        <button
                            v-if="isEditing"
                            @click="cancelEdit"
                            type="button"
                            class="flex flex-1 items-center justify-center gap-2 rounded-2xl bg-white/5 px-8 py-4 font-bold text-zinc-400 transition-all hover:bg-white/10 sm:flex-none"
                        >
                            <X class="h-4 w-4" />
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <div
                class="overflow-hidden rounded-[2rem] border border-white/5 bg-[#161b14]/40 backdrop-blur-sm"
            >
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-left">
                        <thead>
                            <tr class="border-b border-white/5 bg-white/[0.01]">
                                <th
                                    class="p-6 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    Collection Name
                                </th>
                                <th
                                    class="hidden p-6 text-[10px] font-black uppercase tracking-widest text-zinc-500 sm:table-cell"
                                >
                                    Slug
                                </th>
                                <th
                                    class="p-6 text-right text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                >
                                    Control
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            <tr
                                v-for="category in categories"
                                :key="category.id"
                                class="group transition-colors duration-300 hover:bg-white/[0.02]"
                            >
                                <td class="p-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/5 bg-white/5 font-mono font-bold text-[#c5d86d] transition-all duration-500 group-hover:scale-110 group-hover:bg-[#c5d86d]/10"
                                        >
                                            #
                                        </div>
                                        <span
                                            class="font-bold text-zinc-200 transition-colors group-hover:text-white"
                                        >
                                            {{ category.title }}
                                        </span>
                                    </div>
                                </td>
                                <td
                                    class="hidden p-6 font-mono text-xs italic text-zinc-600 sm:table-cell"
                                >
                                    {{ category.slug }}
                                </td>
                                <td class="p-6">
                                    <div class="flex justify-end gap-2">
                                        <button
                                            @click="editCategory(category)"
                                            class="rounded-xl bg-white/5 p-3 text-zinc-500 transition-all hover:bg-white/10 hover:text-[#c5d86d]"
                                        >
                                            <Pencil class="h-4 w-4" />
                                        </button>
                                        <button
                                            @click="deleteCategory(category)"
                                            class="rounded-xl bg-white/5 p-3 text-zinc-600 transition-all hover:bg-red-500/10 hover:text-red-500"
                                        >
                                            <Trash2 class="h-4 w-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }

    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }
</style>
