<script setup>
import { Head, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

defineProps({ users: Array });

const changeRole = (user, newRole) => {
    router.patch(
        route("admin.users.updateRole", user.id),
        {
            role: newRole,
        },
        {
            preserveScroll: true,
        },
    );
};
</script>

<template>
    <AdminLayout>
        <Head title="Пользователи" />

        <div class="p-6">
            <h1 class="text-2xl font-bold mb-6">Управление пользователями</h1>

            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 border-b">
                        <tr>
                            <th class="p-4 font-semibold text-sm">Имя</th>
                            <th class="p-4 font-semibold text-sm">Email</th>
                            <th class="p-4 font-semibold text-sm">Роль</th>
                            <th class="p-4 font-semibold text-sm">
                                Дата регистрации
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="hover:bg-gray-50"
                        >
                            <td class="p-4">{{ user.name }}</td>
                            <td class="p-4 text-gray-600">{{ user.email }}</td>
                            <td class="p-4">
                                <select
                                    :value="user.role"
                                    @change="
                                        changeRole(user, $event.target.value)
                                    "
                                    class="border rounded p-1 text-sm focus:ring-2 focus:ring-green-500"
                                >
                                    <option value="user">User</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </td>
                            <td class="p-4 text-sm text-gray-500">
                                {{
                                    new Date(
                                        user.created_at,
                                    ).toLocaleDateString()
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>
