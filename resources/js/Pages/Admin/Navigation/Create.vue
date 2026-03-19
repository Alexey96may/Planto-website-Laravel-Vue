<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, useForm } from '@inertiajs/vue3';

    import {
        ArrowLeft,
        ChevronRight,
        Eye,
        Globe,
        Hash,
        Layers,
        Link as LinkIcon,
        PlusCircle,
        Tag,
        Type,
    } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { CategoryNavigationCreate, NavigationCreate, NavigationForm, Seo } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        parentOptions: NavigationCreate[];
        categories: CategoryNavigationCreate[];
        seo?: Seo;
    }>();

    const form = useForm<NavigationForm>({
        title: '',
        location: 'header',
        parent_id: null,
        order: 0,
        is_active: true,
        type: 'link',
        category_id: null,
        link: '',
    });

    const filteredParents = computed<NavigationCreate[]>(() => {
        return props.parentOptions.filter((p: NavigationCreate) => p.location === form.location);
    });

    const submit = () => {
        if (form.type === 'link') {
            form.category_id = null;
        } else if (form.type === 'category') {
            form.link = '';
        }

        form.post(route('admin.navigation.store'), {
            preserveScroll: true,
            onError: (errors) => console.error('Error saving:', errors),
        });
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

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
                Add <span class="text-[#c5d86d]">New Point</span>
            </h1>
        </div>

        <div
            class="relative rounded-[2.5rem] border border-white/5 bg-[#161b14] p-6 shadow-2xl sm:p-10"
        >
            <form @submit.prevent="submit" class="space-y-8">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="title"
                        >
                            <Type class="h-3 w-3 text-[#c5d86d]" /> Display Label
                        </label>
                        <input
                            v-model="form.title"
                            id="title"
                            type="text"
                            required
                            placeholder="e.g. Summer Collection"
                            class="w-full rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition placeholder:text-zinc-700 focus:ring-2 focus:ring-[#c5d86d]"
                        />
                    </div>

                    <div class="space-y-2">
                        <label
                            class="ml-1 flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="location"
                        >
                            <Globe class="h-3 w-3 text-[#c5d86d]" /> Menu Location
                        </label>
                        <select
                            v-model="form.location"
                            id="location"
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
                        <Layers class="h-3 w-3 text-[#c5d86d]" /> Navigation Type
                    </label>
                    <div class="grid grid-cols-2 gap-4">
                        <button
                            type="button"
                            @click="form.type = 'link'"
                            :class="
                                form.type === 'link'
                                    ? 'bg-[#c5d86d] text-black shadow-[0_0_20px_rgba(197,216,109,0.2)]'
                                    : 'bg-white/5 text-zinc-400 hover:bg-white/10'
                            "
                            class="flex items-center justify-center gap-2 rounded-2xl p-4 font-bold transition-all"
                        >
                            <LinkIcon class="h-4 w-4" /> URL Link
                        </button>
                        <button
                            type="button"
                            @click="form.type = 'category'"
                            :class="
                                form.type === 'category'
                                    ? 'bg-[#c5d86d] text-black shadow-[0_0_20px_rgba(197,216,109,0.2)]'
                                    : 'bg-white/5 text-zinc-400 hover:bg-white/10'
                            "
                            class="flex items-center justify-center gap-2 rounded-2xl p-4 font-bold transition-all"
                        >
                            <Tag class="h-4 w-4" /> Category
                        </button>
                    </div>
                </div>

                <div class="rounded-[2rem] border border-white/5 bg-black/20 p-6">
                    <div
                        v-if="form.type === 'category'"
                        class="animate-in fade-in slide-in-from-top-1 space-y-2"
                    >
                        <label
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="category_id"
                            >Link to Category</label
                        >
                        <select
                            v-model="form.category_id"
                            id="category_id"
                            class="w-full rounded-xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        >
                            <option :value="null">-- Select Category --</option>
                            <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                                {{ cat.title }}
                            </option>
                        </select>
                    </div>

                    <div
                        v-if="form.type === 'link'"
                        class="animate-in fade-in slide-in-from-top-1 space-y-2"
                    >
                        <label
                            class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-500"
                            for="link"
                            >Custom URL or Path</label
                        >
                        <input
                            v-model="form.link"
                            id="link"
                            type="text"
                            placeholder="/shop/new-arrivals"
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
                            <ChevronRight class="h-3 w-3 text-[#c5d86d]" /> Nest Under (Optional)
                        </label>
                        <select
                            v-model="form.parent_id"
                            id="parent_id"
                            class="w-full appearance-none rounded-2xl border border-white/5 bg-[#0f120e] p-4 text-white outline-none transition focus:ring-2 focus:ring-[#c5d86d]"
                        >
                            <option :value="null">Root Level</option>
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
                            <Hash class="h-3 w-3 text-[#c5d86d]" /> Order ID
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
                    class="flex items-center gap-4 rounded-2xl border border-white/5 bg-white/5 p-4 transition-colors"
                    :class="{ 'border-[#c5d86d]/20 bg-[#c5d86d]/5': form.is_active }"
                >
                    <div class="flex h-5 items-center">
                        <input
                            id="is_active"
                            v-model="form.is_active"
                            type="checkbox"
                            class="h-6 w-6 rounded-lg border-white/10 bg-emerald-800 text-emerald-800 transition focus:ring-[#2d932c] focus:ring-offset-0"
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
                        {{ form.is_active ? 'Published & Active' : 'Hidden / Draft' }}
                    </label>
                </div>

                <div class="flex flex-col gap-4 pt-4">
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="group flex items-center justify-center gap-3 rounded-2xl bg-[#c5d86d] py-5 font-black text-[#0f120e] transition-all hover:bg-[#d4e685] hover:shadow-lg hover:shadow-[#c5d86d]/20 active:scale-[0.98] disabled:opacity-50"
                    >
                        <PlusCircle v-if="!form.processing" class="h-6 w-6" />
                        <span
                            v-else
                            class="h-5 w-5 animate-spin rounded-full border-2 border-[#0f120e]/30 border-t-[#0f120e]"
                        ></span>
                        {{ form.processing ? 'SAVING DATA...' : 'CREATE MENU ITEM' }}
                    </button>

                    <Link
                        :href="route('admin.navigation.index')"
                        class="text-center text-xs font-bold uppercase tracking-[0.2em] text-zinc-600 transition-colors hover:text-white"
                    >
                        Cancel and return
                    </Link>
                </div>
            </form>
        </div>
    </div>
</template>
