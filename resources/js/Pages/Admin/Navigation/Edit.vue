<script setup lang="ts">
    import { computed } from 'vue';

    import { Head, Link, useForm } from '@inertiajs/vue3';

    import {
        ArrowLeft,
        ChevronRight,
        Eye,
        Globe,
        Hash,
        Layers,
        Link as LinkIcon,
        Save,
        Tag,
        Type,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import {
        CategoryNavigationCreate,
        NavigationCreate,
        NavigationEdit,
        NavigationForm,
    } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        item: NavigationEdit;
        parentOptions: NavigationCreate[];
        categories: CategoryNavigationCreate[];
    }>();

    const form = useForm<NavigationForm>({
        title: props.item.title,
        type: props.item.type || 'link',
        category_id: props.item.category_id,
        link: props.item.link,
        location: props.item.location,
        parent_id: props.item.parent_id,
        order: props.item.order,
        is_active: Boolean(props.item.is_active),
    });

    const filteredParents = computed<NavigationCreate[]>(() => {
        return props.parentOptions.filter(
            (p: NavigationCreate) => p.location === form.location && p.id !== props.item.id,
        );
    });

    const submit = () => {
        if (form.type === 'link') {
            form.category_id = null;
        } else if (form.type === 'category') {
            form.link = '';
        }

        form.put(route('admin.navigation.update', props.item.id), {
            preserveScroll: true,
        });
    };
</script>

<template>
    <Head :title="`Edit Menu: ${item.title}`" />

    <div class="mx-auto max-w-3xl">
        <div class="mb-8 flex flex-col gap-4 px-2">
            <Link
                :href="route('admin.navigation.index')"
                class="flex items-center gap-2 text-sm font-bold uppercase tracking-widest text-zinc-500 transition-colors hover:text-[#c5d86d]"
            >
                <ArrowLeft class="h-4 w-4" />
                Back to structure
            </Link>
            <h1 class="text-4xl font-black uppercase italic tracking-tighter text-white">
                Edit <span class="text-[#c5d86d]">Menu Item</span>
            </h1>
        </div>

        <div
            class="relative overflow-hidden rounded-[2.5rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl sm:p-10"
        >
            <form @submit.prevent="submit" class="relative z-10 space-y-8">
                <div
                    v-if="form.errors.parent_id"
                    class="rounded-2xl border border-red-500/20 bg-red-500/10 p-4 text-sm italic text-red-400"
                >
                    {{ form.errors.parent_id }}
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="title"
                        >
                            <Type class="h-3 w-3 text-[#c5d86d]" /> Label Name
                        </label>
                        <input
                            v-model="form.title"
                            id="title"
                            type="text"
                            required
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        />
                    </div>

                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="location"
                        >
                            <Globe class="h-3 w-3 text-[#c5d86d]" /> Display Area
                        </label>
                        <select
                            id="location"
                            v-model="form.location"
                            class="w-full appearance-none rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        >
                            <option value="header">Site Header</option>
                            <option value="footer">Site Footer</option>
                        </select>
                    </div>
                </div>

                <div class="space-y-3">
                    <label
                        class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                    >
                        <Layers class="h-3 w-3 text-[#c5d86d]" /> Content Source
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <button
                            type="button"
                            @click="form.type = 'link'"
                            :class="
                                form.type === 'link'
                                    ? 'bg-[#c5d86d] text-black'
                                    : 'bg-white/5 text-zinc-400 hover:bg-white/10'
                            "
                            class="flex items-center justify-center gap-2 rounded-2xl p-4 font-bold transition-all"
                        >
                            <LinkIcon class="h-4 w-4" /> Custom URL
                        </button>
                        <button
                            type="button"
                            @click="form.type = 'category'"
                            :class="
                                form.type === 'category'
                                    ? 'bg-[#c5d86d] text-black'
                                    : 'bg-white/5 text-zinc-400 hover:bg-white/10'
                            "
                            class="flex items-center justify-center gap-2 rounded-2xl p-4 font-bold transition-all"
                        >
                            <Tag class="h-4 w-4" /> Category
                        </button>
                    </div>
                </div>

                <div
                    class="animate-in fade-in slide-in-from-top-2 rounded-[2rem] border border-white/5 bg-black/20 p-6"
                >
                    <div v-if="form.type === 'category'" class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="category_id"
                            >Select Product Category</label
                        >
                        <select
                            v-model="form.category_id"
                            id="category_id"
                            class="w-full rounded-xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        >
                            <option :value="null">-- Choose Category --</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.title }}
                            </option>
                        </select>
                        <p class="mt-2 text-[10px] italic text-zinc-600">
                            The URL will be generated based on the category slug.
                        </p>
                    </div>

                    <div v-if="form.type === 'link'" class="space-y-2">
                        <label
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="link"
                            >Manual Destination (URL)</label
                        >
                        <input
                            v-model="form.link"
                            id="link"
                            type="text"
                            placeholder="/example-page"
                            class="w-full rounded-xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="parent_id"
                        >
                            <ChevronRight class="h-3 w-3 text-[#c5d86d]" /> Parent Item
                        </label>
                        <select
                            v-model="form.parent_id"
                            id="parent_id"
                            class="w-full appearance-none rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        >
                            <option :value="null">Root Level (No Parent)</option>
                            <option
                                v-for="parent in filteredParents"
                                :key="parent.id"
                                :value="parent.id"
                            >
                                {{ parent.title }}
                            </option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="order"
                        >
                            <Hash class="h-3 w-3 text-[#c5d86d]" /> Order
                        </label>
                        <input
                            v-model="form.order"
                            id="order"
                            type="number"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 font-mono text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        />
                    </div>
                </div>

                <div
                    class="flex items-center gap-4 rounded-2xl border border-white/5 bg-white/5 p-4"
                >
                    <div class="flex h-5 items-center">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="h-5 w-5 rounded-lg border-white/10 bg-emerald-800 text-emerald-800 focus:ring-[#2d932c]"
                        />
                    </div>
                    <label
                        for="is_active"
                        class="flex cursor-pointer select-none items-center gap-2 text-sm font-bold uppercase tracking-tighter text-zinc-300"
                    >
                        <Eye
                            class="h-4 w-4"
                            :class="form.is_active ? 'text-[#c5d86d]' : 'text-zinc-600'"
                        />
                        {{ form.is_active ? 'Visible on Website' : 'Hidden from Menu' }}
                    </label>
                </div>

                <div class="pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group flex w-full items-center justify-center gap-3 rounded-2xl bg-[#c5d86d] py-5 font-black text-[#0f120e] transition-all hover:bg-[#d4e685] hover:shadow-lg hover:shadow-[#c5d86d]/20 active:scale-[0.98] disabled:opacity-50"
                    >
                        <Save v-if="!form.processing" class="h-6 w-6" />
                        <span
                            v-else
                            class="h-5 w-5 animate-spin rounded-full border-2 border-[#0f120e]/30 border-t-[#0f120e]"
                        ></span>
                        {{ form.processing ? 'SAVING CHANGES...' : 'UPDATE MENU ITEM' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
