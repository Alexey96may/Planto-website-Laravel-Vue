<script setup lang="ts">
    import { computed, ref } from 'vue';

    import { Head, useForm } from '@inertiajs/vue3';

    import {
        AlertCircle,
        CheckCircle2,
        Globe,
        Layout,
        ListOrdered,
        Phone,
        Save,
        Share2,
        Video,
    } from 'lucide-vue-next';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Settings, SettingsForm } from '@/types';

    const props = defineProps<{
        settings: Settings;
    }>();

    const activeTab = ref('general');

    const getNum = (key: keyof Settings, fallback: number) => {
        return props.settings[key] !== undefined && props.settings[key] !== null
            ? Number(props.settings[key])
            : fallback;
    };

    const form = useForm<SettingsForm>({
        site_name: props.settings.site_name || '',
        contact_phone: props.settings.contact_phone || '',
        contact_email: props.settings.contact_email || '',
        is_open: String(props.settings.is_open) === '1',
        link_fb: props.settings.link_fb || '',
        link_x: props.settings.link_x || '',
        link_li: props.settings.link_li || '',
        footer_main_text: props.settings.footer_main_text || '',
        footer_rights: props.settings.footer_rights || '',
        products_per_page: getNum('products_per_page', 5),
        slider_main_limit: getNum('slider_main_limit', 5),
        slider_features_limit: getNum('slider_features_limit', 3),
        top_plants_limit: getNum('top_plants_limit', 10),
        comments_limit: getNum('comments_limit', 5),
        features_limit: getNum('features_limit', 8),
        reviews_limit: getNum('reviews_limit', 6),
        trendy_limit: getNum('trendy_limit', 8),
        hero_plants_limit: getNum('hero_plants_limit', 3),
        top_days_interval: getNum('top_days_interval', 0),
        section_trendy_title: props.settings.section_trendy_title || '',
        section_selling_title: props.settings.section_selling_title || '',
        section_reviews_title: props.settings.section_reviews_title || '',
        section_o2_title: props.settings.section_o2_title || '',
        section_trendy_link: props.settings.section_trendy_link || '',
        section_selling_link: props.settings.section_selling_link || '',
        section_reviews_link: props.settings.section_reviews_link || '',
        section_o2_link: props.settings.section_o2_link || '',
        live_demo_url: props.settings.live_demo_url || '',
        contact_address: props.settings.contact_address || '',
        hero_main_text: props.settings.hero_main_text || '',
        section_hero_title: props.settings.section_hero_title || '',
    });

    const tabs = [
        { id: 'general', name: 'General', icon: Globe },
        { id: 'contacts', name: 'Contacts', icon: Phone },
        { id: 'social', name: 'Media & Social', icon: Share2 },
        { id: 'display', name: 'Display Limits', icon: ListOrdered },
        { id: 'sections', name: 'Content Blocks', icon: Layout },
    ];

    const limitFields: (keyof SettingsForm)[] = [
        'products_per_page',
        'slider_main_limit',
        'slider_features_limit',
        'hero_plants_limit',
        'top_plants_limit',
        'comments_limit',
        'features_limit',
        'reviews_limit',
        'trendy_limit',
    ];

    const submit = () => {
        form.post(route('admin.settings.update'), {
            preserveScroll: true,
            onSuccess: () => {
                // Здесь можно вызвать Toast
            },
        });
    };
</script>

<template>
    <AdminLayout>
        <Head title="Engine Settings" />

        <div class="mx-auto max-w-6xl">
            <div class="flex flex-col gap-8 2xl:flex-row">
                <aside class="flex-shrink-0 2xl:w-64">
                    <div class="sticky top-8 space-y-2">
                        <h1
                            class="mb-6 text-2xl font-black uppercase italic tracking-tighter text-white"
                        >
                            System <span class="text-[#c5d86d]">Core</span>
                        </h1>

                        <nav class="space-y-1">
                            <button
                                v-for="tab in tabs"
                                :key="tab.id"
                                @click="activeTab = tab.id"
                                :class="[
                                    activeTab === tab.id
                                        ? 'bg-[#c5d86d] text-black shadow-lg shadow-[#c5d86d]/10'
                                        : 'text-zinc-500 hover:bg-white/5 hover:text-white',
                                ]"
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-xs font-black uppercase tracking-widest transition-all"
                            >
                                <component :is="tab.icon" class="h-4 w-4" />
                                {{ tab.name }}
                            </button>
                        </nav>

                        <div class="mt-8 rounded-2xl border border-white/5 bg-zinc-900/50 p-4">
                            <div class="mb-2 flex items-center gap-2">
                                <div
                                    class="h-2 w-2 rounded-full"
                                    :class="
                                        form.is_open ? 'animate-pulse bg-green-500' : 'bg-red-500'
                                    "
                                ></div>
                                <span
                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-400"
                                    >Store Status</span
                                >
                            </div>
                            <p class="text-[9px] font-bold leading-tight text-zinc-600">
                                {{
                                    form.is_open
                                        ? 'Accepting incoming transactions.'
                                        : 'All operations suspended.'
                                }}
                            </p>
                        </div>
                    </div>
                </aside>

                <main class="flex-1">
                    <form @submit.prevent="submit" class="space-y-8 pb-24">
                        <transition name="tab-fade" mode="out-in">
                            <div :key="activeTab">
                                <section
                                    v-show="activeTab === 'general'"
                                    class="animate-in fade-in slide-in-from-bottom-4 space-y-6 rounded-[1rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl duration-500 md:rounded-[2.5rem]"
                                >
                                    <h2
                                        class="mb-8 flex items-center gap-3 text-sm font-black uppercase tracking-[0.3em] text-[#c5d86d]"
                                    >
                                        <Globe class="h-4 w-4" /> Global Identity
                                    </h2>

                                    <div class="space-y-4">
                                        <div class="grid grid-cols-1 gap-6">
                                            <div class="space-y-2">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                    for="site_name"
                                                    >Site Name</label
                                                >
                                                <input
                                                    v-model="form.site_name"
                                                    id="site_name"
                                                    type="text"
                                                    class="w-full rounded-sm border border-white/5 bg-black/50 p-4 font-bold text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50 md:rounded-2xl"
                                                />
                                            </div>
                                            <div
                                                class="flex items-center gap-4 rounded-sm border border-white/5 bg-black/30 p-4 md:rounded-2xl"
                                            >
                                                <input
                                                    type="checkbox"
                                                    id="is_open_check"
                                                    v-model="form.is_open"
                                                    class="h-5 w-5 rounded border-emerald-400 bg-emerald-800 text-emerald-800 focus:ring-[#31be44]"
                                                />
                                                <label
                                                    for="is_open_check"
                                                    class="cursor-pointer text-xs font-bold uppercase tracking-tighter text-zinc-300"
                                                    >Public access enabled</label
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section
                                    v-show="activeTab === 'contacts'"
                                    class="animate-in fade-in slide-in-from-bottom-4 space-y-6 rounded-[1rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl duration-500 md:rounded-[2.5rem]"
                                >
                                    <h2
                                        class="mb-8 flex items-center gap-3 text-sm font-black uppercase tracking-[0.3em] text-[#c5d86d]"
                                    >
                                        <Phone class="h-4 w-4" /> Communications
                                    </h2>
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                for="contact_phone"
                                                >Phone Line</label
                                            >
                                            <input
                                                v-model="form.contact_phone"
                                                type="text"
                                                id="contact_phone"
                                                class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 font-mono text-white"
                                            />
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                for="contact_email"
                                                >Support Email</label
                                            >
                                            <input
                                                v-model="form.contact_email"
                                                id="contact_email"
                                                type="email"
                                                class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 font-bold text-white"
                                            />
                                        </div>
                                        <div class="space-y-2 md:col-span-2">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                for="contact_address"
                                                >Physical HQ Address</label
                                            >
                                            <textarea
                                                v-model="form.contact_address"
                                                id="contact_address"
                                                rows="3"
                                                class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 text-zinc-400 outline-none"
                                            ></textarea>
                                        </div>
                                    </div>
                                </section>

                                <section
                                    v-show="activeTab === 'display'"
                                    class="animate-in fade-in slide-in-from-bottom-4 space-y-6 rounded-[1rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl duration-500 md:rounded-[2.5rem]"
                                >
                                    <h2
                                        class="mb-8 flex items-center gap-3 text-sm font-black uppercase tracking-[0.3em] text-[#c5d86d]"
                                    >
                                        <ListOrdered class="h-4 w-4" /> Quantum Limits
                                    </h2>
                                    <div class="grid grid-cols-2 gap-6 md:grid-cols-3">
                                        <transition-group
                                            enter-active-class="transition duration-500 ease-out"
                                            enter-from-class="transform translate-x-4 opacity-0"
                                            leave-active-class="transition duration-300 ease-in"
                                            leave-to-class="transform -translate-x-10 opacity-0"
                                            move-class="transition duration-500"
                                        >
                                            <div
                                                v-for="field in limitFields"
                                                :key="field"
                                                class="space-y-2"
                                            >
                                                <label
                                                    class="block truncate text-[9px] font-black uppercase tracking-tighter text-zinc-600"
                                                    for="field"
                                                    >{{ field.replace(/_/g, ' ') }}</label
                                                >
                                                <input
                                                    v-model="form[field]"
                                                    id="field"
                                                    type="number"
                                                    class="w-full rounded-xl border border-white/5 bg-black/50 p-3 text-center font-mono font-bold text-[#c5d86d]"
                                                />
                                            </div>
                                        </transition-group>
                                    </div>
                                </section>

                                <section
                                    v-show="activeTab === 'social'"
                                    class="animate-in fade-in slide-in-from-bottom-4 space-y-6 rounded-[1rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl duration-500 md:rounded-[2.5rem]"
                                >
                                    <h2
                                        class="mb-8 flex items-center gap-3 text-sm font-black uppercase tracking-[0.3em] text-[#c5d86d]"
                                    >
                                        <Share2 class="h-4 w-4" /> Social Presence
                                    </h2>

                                    <div class="grid grid-cols-1 gap-6">
                                        <div class="space-y-2">
                                            <label
                                                class="text-[10px] font-black uppercase tracking-widest text-[#c5d86d]"
                                                for="live_demo_url"
                                            >
                                                Live Demo / Video Presentation
                                            </label>
                                            <div class="group relative">
                                                <div
                                                    class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-500 transition-colors group-focus-within:text-[#c5d86d]"
                                                >
                                                    <Video class="h-4 w-4" />
                                                </div>
                                                <input
                                                    v-model="form.live_demo_url"
                                                    id="live_demo_url"
                                                    type="text"
                                                    placeholder="https://youtube.com/watch?v=..."
                                                    class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 pl-12 font-medium text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                                />
                                            </div>
                                            <p
                                                class="text-[9px] font-bold uppercase tracking-tight text-zinc-600"
                                            >
                                                This link will be used for the "Watch Demo" buttons
                                                on the landing page.
                                            </p>
                                        </div>

                                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                            <div class="space-y-2">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                    for="link_fb"
                                                    >Facebook</label
                                                >
                                                <input
                                                    v-model="form.link_fb"
                                                    type="text"
                                                    id="link_fb"
                                                    class="w-full rounded-xl border border-white/5 bg-black/50 p-4 text-xs text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                                />
                                            </div>

                                            <div class="space-y-2">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                    for="link_x"
                                                    >X / Twitter</label
                                                >
                                                <input
                                                    v-model="form.link_x"
                                                    id="link_x"
                                                    type="text"
                                                    class="w-full rounded-xl border border-white/5 bg-black/50 p-4 text-xs text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                                />
                                            </div>

                                            <div class="space-y-2">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                    for="link_li"
                                                    >LinkedIn</label
                                                >
                                                <input
                                                    v-model="form.link_li"
                                                    id="link_li"
                                                    type="text"
                                                    class="w-full rounded-xl border border-white/5 bg-black/50 p-4 text-xs text-white outline-none focus:ring-1 focus:ring-[#c5d86d]/50"
                                                />
                                            </div>
                                        </div>

                                        <div
                                            class="mt-4 grid grid-cols-1 gap-6 border-t border-white/5 pt-6 md:grid-cols-2"
                                        >
                                            <div class="space-y-2">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                    for="footer_main_text"
                                                    >Footer Main Text</label
                                                >
                                                <input
                                                    v-model="form.footer_main_text"
                                                    id="footer_main_text"
                                                    type="text"
                                                    class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 text-sm text-white"
                                                />
                                            </div>
                                            <div class="space-y-2">
                                                <label
                                                    class="text-[10px] font-black uppercase tracking-widest text-zinc-500"
                                                    for="footer_rights"
                                                    >Copyright Line</label
                                                >
                                                <input
                                                    v-model="form.footer_rights"
                                                    id="footer_rights"
                                                    type="text"
                                                    class="w-full rounded-2xl border border-white/5 bg-black/50 p-4 text-sm text-white"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section
                                    v-show="activeTab === 'sections'"
                                    class="animate-in fade-in slide-in-from-bottom-4 space-y-8 rounded-[1rem] border border-white/5 bg-[#161b14] p-8 shadow-2xl duration-500 md:rounded-[2.5rem]"
                                >
                                    <h2
                                        class="mb-4 flex items-center gap-3 text-sm font-black uppercase tracking-[0.3em] text-[#c5d86d]"
                                    >
                                        <Layout class="h-4 w-4" /> Layout Modules
                                    </h2>
                                    <transition-group
                                        enter-active-class="transition duration-500 ease-out"
                                        enter-from-class="transform translate-x-4 opacity-0"
                                        leave-active-class="transition duration-300 ease-in"
                                        leave-to-class="transform -translate-x-10 opacity-0"
                                        move-class="transition duration-500"
                                    >
                                        <div
                                            v-for="section in [
                                                'trendy',
                                                'selling',
                                                'reviews',
                                                'o2',
                                            ]"
                                            :key="section"
                                            class="space-y-4 rounded-3xl border border-white/5 bg-black/20 p-6"
                                        >
                                            <div class="mb-2 flex items-center gap-2">
                                                <span
                                                    class="text-[10px] font-black uppercase tracking-[0.2em] text-zinc-400"
                                                    >{{ section }} Block</span
                                                >
                                            </div>
                                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                                <input
                                                    v-model="
                                                        form[
                                                            `section_${section}_title` as keyof SettingsForm
                                                        ]
                                                    "
                                                    type="text"
                                                    placeholder="Title"
                                                    class="w-full rounded-xl border border-white/5 bg-black p-3 text-sm text-white"
                                                />
                                                <input
                                                    v-model="
                                                        form[
                                                            `section_${section}_link` as keyof SettingsForm
                                                        ]
                                                    "
                                                    type="text"
                                                    placeholder="Route / Link"
                                                    class="w-full rounded-xl border border-white/5 bg-black p-3 text-sm text-zinc-500"
                                                />
                                            </div>
                                        </div>
                                    </transition-group>
                                </section>
                            </div>
                        </transition>

                        <div
                            class="z-50 flex w-full max-w-4xl flex-wrap items-center justify-between gap-6 rounded-xl border border-white/10 bg-white/10 p-4 shadow-2xl backdrop-blur-xl md:rounded-3xl"
                        >
                            <div class="flex items-center gap-4 px-4">
                                <div
                                    v-if="form.isDirty"
                                    class="flex items-center gap-2 text-amber-400"
                                >
                                    <AlertCircle class="h-4 w-4" />
                                    <span class="text-[10px] font-black uppercase tracking-widest"
                                        >Unsaved Changes</span
                                    >
                                </div>
                                <div v-else class="flex items-center gap-2 text-[#c5d86d]">
                                    <CheckCircle2 class="h-4 w-4" />
                                    <span class="text-[10px] font-black uppercase tracking-widest"
                                        >System Synced</span
                                    >
                                </div>
                            </div>

                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex items-center gap-2 rounded-2xl bg-[#c5d86d] px-8 py-3 text-[10px] font-black uppercase tracking-[0.2em] text-black transition-all hover:bg-[#d4e685] disabled:opacity-50"
                            >
                                <Save class="h-4 w-4" />
                                {{ form.processing ? 'Syncing...' : 'Commit Changes' }}
                            </button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped lang="scss">
    .v-move {
        transition: transform 0.5s ease;
    }

    .v-leave-active {
        position: absolute;
        width: 100%;
    }

    .tab-fade-enter-active,
    .tab-fade-leave-active {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .tab-fade-enter-from {
        opacity: 0;
        transform: translateY(10px);
    }

    .tab-fade-leave-to {
        opacity: 0;
        transform: translateY(-10px);
    }
</style>
