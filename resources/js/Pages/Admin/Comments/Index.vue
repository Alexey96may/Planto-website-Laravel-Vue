<script setup lang="ts">
    import { router } from '@inertiajs/vue3';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Review } from '@/types';

    defineOptions({
        layout: AdminLayout,
    });

    const props = defineProps<{
        comments: Review[];
    }>();

    const toggleStatus = (id: number) => {
        router.patch(
            route('admin.comments.update', id),
            {},
            {
                preserveScroll: true,
            },
        );
    };

    const deleteComment = (id: number) => {
        if (confirm('Are you sure you want to delete this comment?')) {
            router.delete(route('admin.comments.destroy', id), {
                preserveScroll: true,
            });
        }
    };
</script>

<template>
    <div class="admin-panel">
        <h1>Review Moderation</h1>
        <table class="w-full mt-4 border">
            <thead>
                <tr class="bg-gray-100">
                    <th>Author</th>
                    <th>Text</th>
                    <th>Rating</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="comment in comments" :key="comment.id" class="border-t">
                    <td>{{ comment.user?.name || 'Guest' }}</td>
                    <td class="max-w-xs truncate">{{ comment.body }}</td>
                    <td>{{ comment.rating }} ⭐</td>
                    <td>
                        <span :class="comment.is_active ? 'text-green-600' : 'text-red-600'">
                            {{ comment.is_active ? 'Active' : 'Hidden' }}
                        </span>
                    </td>
                    <td class="space-x-2">
                        <button
                            @click="toggleStatus(comment.id)"
                            class="bg-blue-500 text-white px-2 py-1 rounded"
                        >
                            {{ comment.is_active ? 'Hide' : 'Approve' }}
                        </button>
                        <button
                            @click="deleteComment(comment.id)"
                            class="bg-red-500 text-white px-2 py-1 rounded"
                        >
                            Delete
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
