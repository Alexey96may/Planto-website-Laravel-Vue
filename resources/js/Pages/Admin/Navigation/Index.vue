<script setup lang="ts">
    import { ref } from 'vue';

    import { Link, router } from '@inertiajs/vue3';

    import draggable from 'vuedraggable';
    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { NavigationItem } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

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
                onSuccess: () => {
                    // Здесь лучше вызвать твой глобальный Toast или уведомление
                },
            },
        );
    };

    //todo confirm
    const deleteItem = (id: number): void => {
        if (confirm('Удалить этот пункт и все вложенные меню?')) {
            router.delete(route('admin.navigation.destroy', id), {
                preserveScroll: true,
                // Можно добавить хук, чтобы показать лоадер, если нужно
            });
            router.delete(route('admin.navigation.destroy', id));
        }
    };
</script>

<template>
    <div class="p-6">
        <div class="flex justify-between mb-6">
            <h1 class="text-2xl font-bold">Menu management</h1>
            <Link
                :href="route('admin.navigation.create')"
                class="bg-blue-600 text-white px-4 py-2 rounded"
            >
                + Add item
            </Link>
        </div>

        <div class="flex justify-between mb-4">
            <h1 class="text-2xl font-bold">Sorting the menu</h1>
            <button
                @click="saveOrder"
                :disabled="isSaving"
                class="bg-green-600 text-white px-4 py-2 rounded"
            >
                <span v-if="isSaving">Saving...</span>
                <span v-else>Save order</span>
            </button>
        </div>

        <draggable v-model="list" item-key="id" tag="div" class="space-y-2" handle=".drag-handle">
            <template #item="{ element }">
                <div class="flex items-center bg-white p-4 shadow rounded border">
                    <div class="drag-handle cursor-move mr-4 text-gray-400">☰</div>
                    <div class="flex-1">
                        {{ element.title }}
                        <span class="text-xs text-gray-400 ml-2 uppercase"
                            >({{ element.location }})</span
                        >
                    </div>
                </div>
            </template>
        </draggable>

        <table class="w-full bg-white rounded-lg shadow">
            <thead>
                <tr class="border-b text-left bg-gray-50">
                    <th class="p-4">Name</th>
                    <th class="p-4">Location</th>
                    <th class="p-4">Link</th>
                    <th class="p-4">Order</th>
                    <th class="p-4">Active</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="item in menuItems" :key="item.id">
                    <tr class="border-b hover:bg-gray-50 font-semibold">
                        <td class="p-4">{{ item.title }}</td>
                        <td class="p-4 text-sm text-gray-500 uppercase">
                            {{ item.location }}
                        </td>
                        <td class="p-4 text-blue-500" v-if="item.link">
                            {{ item.link }}
                        </td>
                        <td class="p-4 text-blue-500" v-else>/shop</td>
                        <td class="p-4">{{ item.order }}</td>
                        <td class="p-4" v-if="item.is_active">✓</td>
                        <td class="p-4" v-else>-</td>
                        <td class="p-4 text-right">
                            <Link
                                :href="route('admin.navigation.edit', item.id)"
                                class="text-indigo-600 mr-3"
                                >Edit</Link
                            >
                            <button @click="deleteItem(item.id)" class="text-red-600">
                                Delete
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
                        <td class="p-4 text-blue-500" v-if="item.link">
                            {{ item.link }}
                        </td>
                        <td class="p-4 text-blue-500" v-else>/shop</td>
                        <td class="p-4">{{ child.order }}</td>
                        <td class="p-4" v-if="item.is_active">✓</td>
                        <td class="p-4" v-else>-</td>
                        <td class="p-4">{{ item.is_active && '' }}</td>
                        <td class="p-4 text-right">
                            <Link
                                :href="route('admin.navigation.edit', child.id)"
                                class="text-indigo-400 mr-3"
                                >Edit</Link
                            >
                            <button @click="deleteItem(child.id)" class="text-red-400">
                                Delete
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>
