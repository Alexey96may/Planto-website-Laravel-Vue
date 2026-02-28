<script setup lang="ts">
    import { Head, useForm } from '@inertiajs/vue3';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Settings, SettingsForm } from '@/types';

    const props = defineProps<{
        settings: Settings;
    }>();

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

        // Лимиты
        slider_main_limit: getNum('slider_main_limit', 5),
        slider_features_limit: getNum('slider_features_limit', 3),
        top_plants_limit: getNum('top_plants_limit', 10),
        comments_limit: getNum('comments_limit', 5),
        features_limit: getNum('trendy_limit', 8),
        reviews_limit: getNum('reviews_limit', 6),
        trendy_limit: getNum('trendy_limit', 8),
        hero_plants_limit: getNum('hero_plants_limit', 3),
        top_days_interval: getNum('top_days_interval', 0),

        // Заголовки секций
        section_trendy_title: props.settings.section_trendy_title || '',
        section_selling_title: props.settings.section_selling_title || '',
        section_reviews_title: props.settings.section_reviews_title || '',
        section_o2_title: props.settings.section_o2_title || '',

        // Ссылки
        section_trendy_link: props.settings.section_trendy_link || '',
        section_selling_link: props.settings.section_selling_link || '',
        section_reviews_link: props.settings.section_reviews_link || '',
        section_o2_link: props.settings.section_o2_link || '',

        // Медиа и контакты
        live_demo_url: props.settings.live_demo_url || '',
        contact_address: props.settings.contact_address || 'Our Address',
        hero_main_text: props.settings.hero_main_text || 'Main Description',
        section_hero_title: props.settings.section_hero_title || 'Main Title',
    });

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

    const getSectionKey = (section: string, suffix: 'title' | 'link') => {
        return `section_${section}_${suffix}` as keyof SettingsForm;
    };

    const submit = () => {
        // todo Отправляем POST запрос на сохранение
        form.post(route('admin.settings.update'), {
            preserveScroll: true,
            onSuccess: () => alert('Settings are saved!'),
        });
    };
</script>

<template>
    <AdminLayout>
        <Head title="Store Settings" />

        <div class="max-w-4xl mx-auto p-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b bg-gray-50">
                    <h1 class="text-xl font-bold text-gray-800">General Site Settings</h1>
                    <p class="text-sm text-gray-500">
                        This information is displayed globally across the website.
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            Contact Information
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold mb-1">Store Name</label>
                                <input
                                    v-model="form.site_name"
                                    type="text"
                                    class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                                />
                                <p v-if="form.errors.site_name" class="text-red-500 text-xs mt-1">
                                    {{ form.errors.site_name }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1"
                                    >Contact Phone</label
                                >
                                <input
                                    v-model="form.contact_phone"
                                    type="text"
                                    placeholder="+1 (999) 000-00-00"
                                    class="w-full border rounded-lg p-2.5 outline-none"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1"
                                    >Notification Email</label
                                >
                                <input
                                    v-model="form.contact_email"
                                    type="email"
                                    placeholder="admin@example.com"
                                    class="w-full border rounded-lg p-2.5 outline-none"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold mb-1"
                                    >Contact Address</label
                                >
                                <textarea
                                    v-model="form.contact_address"
                                    class="w-full border rounded-lg p-2.5 outline-none"
                                    rows="2"
                                ></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            🎥 Media & Social Links
                        </h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700"
                                    >Live Demo URL (YouTube)</label
                                >
                                <input
                                    type="url"
                                    v-model="form.live_demo_url"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                    placeholder="https://youtube.com/embed/..."
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700"
                                    >Facebook Link</label
                                >
                                <input
                                    v-model="form.link_fb"
                                    type="url"
                                    placeholder="https://www.facebook.com"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700"
                                    >X (Twitter) Link</label
                                >
                                <input
                                    v-model="form.link_x"
                                    type="url"
                                    placeholder="https://www.x.com"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700"
                                    >LinkedIn Link</label
                                >
                                <input
                                    v-model="form.link_li"
                                    type="url"
                                    placeholder="https://www.linkedin.com"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg">
                        <input
                            v-model="form.is_open"
                            type="checkbox"
                            id="is_open"
                            class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                        />
                        <label for="is_open" class="text-sm font-medium cursor-pointer">
                            Store is open and accepting orders
                        </label>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200 space-y-4">
                        <h2 class="text-lg font-semibold text-indigo-700 border-b pb-2">
                            Footer & Content
                        </h2>

                        <div>
                            <label class="block text-sm font-semibold mb-1">Footer Main Text</label>
                            <input
                                v-model="form.footer_main_text"
                                type="text"
                                class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Footer Rights Text</label
                            >
                            <input
                                v-model="form.footer_rights"
                                type="text"
                                class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                            />
                        </div>
                        <div class="pt-4">
                            <label class="block text-sm font-semibold mb-1"
                                >Hero Section Title</label
                            >
                            <input
                                v-model="form.section_hero_title"
                                type="text"
                                class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Hero Section Description</label
                            >
                            <textarea
                                v-model="form.hero_main_text"
                                class="w-full border rounded-lg p-2.5 outline-none"
                                rows="2"
                            ></textarea>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            📊 Display Limits
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div v-for="field in limitFields" :key="field">
                                <label class="block text-sm font-medium text-gray-700 capitalize">{{
                                    field.replace(/_/g, ' ')
                                }}</label>
                                <input
                                    type="number"
                                    v-model="form[field]"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />
                                <p v-if="form.errors[field]" class="text-red-500 text-xs mt-1">
                                    {{ form.errors[field] }}
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700"
                                    >Top Sales Interval (Days)</label
                                >
                                <input
                                    type="number"
                                    v-model="form.top_days_interval"
                                    min="0"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                />
                                <p class="text-xs text-gray-500 mt-1">
                                    Enter number of days. Set to 0 to show all-time top sales.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                        <h2 class="text-lg font-semibold mb-4 text-indigo-700 border-b pb-2">
                            📝 Section Titles & Links
                        </h2>
                        <div class="space-y-6">
                            <div
                                v-for="section in ['trendy', 'selling', 'reviews', 'o2']"
                                :key="section"
                                class="grid grid-cols-1 md:grid-cols-2 gap-4 p-3 bg-gray-50 rounded"
                            >
                                <div>
                                    <label class="block text-sm font-medium text-gray-700"
                                        >{{ section.toUpperCase() }} Section Title</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form[getSectionKey(section, 'title')]"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700"
                                        >Link (URL)</label
                                    >
                                    <input
                                        type="text"
                                        v-model="form[getSectionKey(section, 'link')]"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm sm:text-sm"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving...' : 'Save Changes' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
