<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';

    import MainLayout from '@/Layouts/MainLayout.vue';
    import { PaginatedResponse, Review } from '@/types';

    const props = defineProps<{
        reviews: PaginatedResponse<Review>;
        averageRating: number;
    }>();

    defineOptions({
        layout: MainLayout,
    });
</script>

<template>
    <Head title="Отзывы о нас" />

    <div class="max-w-5xl mx-auto py-12 px-4">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Customer reviews</h1>

        <div v-if="averageRating" class="mb-8 p-4 bg-indigo-50 rounded-lg flex items-center">
            <span class="text-4xl font-black text-indigo-600 mr-4">{{
                averageRating.toFixed(1)
            }}</span>
            <div>
                <div class="text-sm text-gray-600">Average site rating</div>
                <div class="text-yellow-500">
                    {{ '⭐'.repeat(Math.round(averageRating)) }}
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div
                v-for="review in reviews.data"
                :key="review.id"
                class="bg-white p-6 rounded-xl shadow-sm border border-gray-100"
            >
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <div class="font-bold text-gray-900">
                            {{ review.user?.name || 'Аноним' }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ new Date(review.created_at).toLocaleDateString() }}
                        </div>
                    </div>
                    <div
                        class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-bold"
                    >
                        {{ review.rating }} ⭐
                    </div>
                </div>
                <p class="text-gray-700 leading-relaxed">
                    {{ review.body }}
                </p>
            </div>
        </div>

        <div v-if="reviews.data.length === 0" class="text-center py-20 text-gray-500">
            Be the first to leave a review in your personal account!
        </div>
    </div>
</template>
