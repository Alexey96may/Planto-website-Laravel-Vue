<script setup>
import { Link, router } from "@inertiajs/vue3";

defineProps({
    menuItems: Array,
});

const deleteItem = (id) => {
    if (confirm("Удалить этот пункт и все вложенные меню?")) {
        router.delete(route("admin.navigation.destroy", id));
    }
};
</script>

<template>
    <div class="p-6">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Управление меню</h1>
            <Link
                :href="route('admin.navigation.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Добавить пункт
            </Link>
        </div>

        <table class="w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="border-b text-left bg-gray-50">
                    <th class="p-4">Название</th>
                    <th class="p-4">Локация</th>
                    <th class="p-4">Ссылка</th>
                    <th class="p-4">Порядок</th>
                    <th class="p-4 text-right">Действия</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="item in menuItems" :key="item.id">
                    <tr class="border-b hover:bg-gray-50 font-semibold">
                        <td class="p-4">{{ item.title }}</td>
                        <td class="p-4 text-sm text-gray-500 uppercase">
                            {{ item.location }}
                        </td>
                        <td class="p-4 text-blue-500">
                            {{ item.link || "—" }}
                        </td>
                        <td class="p-4">{{ item.order }}</td>
                        <td class="p-4 text-right">
                            <Link
                                :href="route('admin.navigation.edit', item.id)"
                                class="text-indigo-600 mr-3"
                                >Редактировать</Link
                            >
                            <button
                                @click="deleteItem(item.id)"
                                class="text-red-600"
                            >
                                Удалить
                            </button>
                        </td>
                    </tr>

                    <tr
                        v-for="child in item.children"
                        :key="child.id"
                        class="border-b bg-gray-50/50 italic text-sm"
                    >
                        <td class="p-4 pl-10">↳ {{ child.title }}</td>
                        <td class="p-4 opacity-50">{{ child.location }}</td>
                        <td class="p-4 text-blue-400">
                            {{ child.link || "—" }}
                        </td>
                        <td class="p-4">{{ child.order }}</td>
                        <td class="p-4 text-right">
                            <Link
                                :href="route('admin.navigation.edit', child.id)"
                                class="text-indigo-400 mr-3"
                                >Ред.</Link
                            >
                            <button
                                @click="deleteItem(child.id)"
                                class="text-red-400"
                            >
                                Удал.
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>
