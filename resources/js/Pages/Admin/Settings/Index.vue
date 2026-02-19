<script setup>
import { Head, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

// Получаем объект настроек из контроллера
const props = defineProps({
    settings: Object,
});

// Инициализируем форму.
// Если какой-то настройки нет в базе, ставим пустую строку по умолчанию.
const form = useForm({
    site_name: props.settings.site_name || "",
    contact_phone: props.settings.contact_phone || "",
    contact_email: props.settings.contact_email || "",
    is_open: props.settings.is_open === "1" || props.settings.is_open === true,
    link_fb: props.settings.link_fb || "",
    link_x: props.settings.link_x || "",
    link_li: props.settings.link_li || "",
    footer_main_text: props.settings.footer_main_text || "",
    footer_rights: props.settings.footer_rights || "",
    products_per_page: props.settings.products_per_page || 5,
});

const submit = () => {
    // Отправляем POST запрос на сохранение
    form.post(route("admin.settings.update"), {
        preserveScroll: true,
        onSuccess: () => alert("Settings are saved!"),
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Настройки магазина" />

        <div class="max-w-4xl mx-auto p-6">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b bg-gray-50">
                    <h1 class="text-xl font-bold text-gray-800">
                        Общие настройки сайта
                    </h1>
                    <p class="text-sm text-gray-500">
                        Эти данные отображаются в футере, шапке и контактах
                    </p>
                </div>

                <form @submit.prevent="submit" class="p-6 space-y-6">
                    <div>
                        <label class="block text-sm font-semibold mb-1"
                            >Название магазина</label
                        >
                        <input
                            v-model="form.site_name"
                            type="text"
                            class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                        />
                        <p
                            v-if="form.errors.site_name"
                            class="text-red-500 text-xs mt-1"
                        >
                            {{ form.errors.site_name }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Контактный телефон</label
                            >
                            <input
                                v-model="form.contact_phone"
                                type="text"
                                placeholder="+7 (999) 000-00-00"
                                class="w-full border rounded-lg p-2.5 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Email для уведомлений</label
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
                                >Ссылка на FB</label
                            >
                            <input
                                v-model="form.link_fb"
                                type="url"
                                placeholder="https://www.facebook.com"
                                class="w-full border rounded-lg p-2.5 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Ссылка на X</label
                            >
                            <input
                                v-model="form.link_x"
                                type="url"
                                placeholder="https://www.x.com"
                                class="w-full border rounded-lg p-2.5 outline-none"
                            />
                        </div>

                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Ссылка на LinkedIn</label
                            >
                            <input
                                v-model="form.link_li"
                                type="url"
                                placeholder="https://www.linkedin.com"
                                class="w-full border rounded-lg p-2.5 outline-none"
                            />
                        </div>
                    </div>

                    <div
                        class="flex items-center space-x-3 p-4 bg-gray-50 rounded-lg"
                    >
                        <input
                            v-model="form.is_open"
                            type="checkbox"
                            id="is_open"
                            class="w-5 h-5 text-green-600 rounded focus:ring-green-500"
                        />
                        <label
                            for="is_open"
                            class="text-sm font-medium cursor-pointer"
                        >
                            Магазин открыт и принимает заказы
                        </label>
                    </div>

                    <div>
                        <label>Товаров на страницу:</label>
                        <input
                            type="number"
                            v-model="form.products_per_page"
                            class="border p-2 rounded"
                            :class="{
                                'border-red-500': form.errors.products_per_page,
                            }"
                        />
                        <p
                            v-if="form.errors.products_per_page"
                            class="text-red-500 text-sm"
                        >
                            {{ form.errors.products_per_page }}
                        </p>
                    </div>

                    <div class="footer-info">
                        <h3>Footer Info</h3>
                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Footer main text</label
                            >
                            <input
                                v-model="form.footer_main_text"
                                type="text"
                                class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1"
                                >Footer rights text</label
                            >
                            <input
                                v-model="form.footer_rights"
                                type="text"
                                class="w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-green-500 outline-none"
                            />
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-bold transition disabled:opacity-50"
                        >
                            {{
                                form.processing
                                    ? "Сохранение..."
                                    : "Сохранить изменения"
                            }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
