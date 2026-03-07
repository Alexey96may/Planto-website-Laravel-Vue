<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';

    import AppRating from '@/Components/UI/AppRating.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { PaginatedResponse, Review } from '@/types';

    const props = defineProps<{
        reviews: PaginatedResponse<Review>;
        averageRating: number;
    }>();

    defineOptions({
        layout: MainLayout,
    });

    // Helper to get initials for the avatar
    const getInitials = (name: string) => (name ? name.charAt(0).toUpperCase() : 'A');
</script>

<template>
    <Head title="Customer Reviews" />

    <div class="mx-auto max-w-5xl bg-plant-shop px-6 py-16 lg:py-24">
        <header class="mb-16">
            <h1 class="mb-4 text-4xl font-bold tracking-tight text-zinc-200 lg:text-6xl">
                What our <span class="text-emerald-600">customers say</span>
            </h1>
            <p class="text-lg text-zinc-500">Real feedback from our plant-loving community.</p>
        </header>

        <div
            v-if="averageRating"
            class="mb-16 flex flex-col items-center gap-8 rounded-[2.5rem] border border-emerald-100 p-10 sm:flex-row sm:justify-between"
        >
            <div class="flex items-center gap-6">
                <span class="text-7xl font-black text-emerald-600">
                    {{ averageRating.toFixed(1) }}
                </span>
                <div class="flex flex-col gap-1">
                    <div
                        class="mb-1 text-xs font-bold uppercase tracking-widest text-emerald-700/60"
                    >
                        Average Rating
                    </div>
                    <AppRating :rating="averageRating" class="origin-left scale-150" />
                </div>
            </div>

            <div class="text-center sm:text-right">
                <p class="text-sm font-medium text-emerald-600/80">
                    Based on {{ reviews.total }} verified reviews
                </p>
            </div>
        </div>

        <div class="grid gap-8">
            <div
                v-for="(review, index) in reviews.data"
                :key="review.id"
                class="review-card group relative overflow-hidden rounded-[2.5rem] border border-zinc-100 bg-zinc-50 p-8 shadow-sm transition-all hover:shadow-xl lg:p-12"
                :style="{ animationDelay: `${index * 100}ms` }"
            >
                <div class="relative z-10">
                    <div
                        class="mb-8 flex items-center justify-between border-b border-zinc-50 pb-6"
                    >
                        <div class="flex items-center gap-4">
                            <div
                                class="flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-200 text-xl font-bold text-emerald-700 shadow-inner"
                            >
                                {{ getInitials(review.user?.name || 'A') }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-zinc-900">
                                    {{ review.user?.name || 'Anonymous' }}
                                </h3>
                                <time
                                    class="text-xs font-semibold uppercase tracking-widest text-zinc-400"
                                >
                                    {{
                                        new Date(review.created_at).toLocaleDateString('en-US', {
                                            month: 'long',
                                            day: 'numeric',
                                            year: 'numeric',
                                        })
                                    }}
                                </time>
                            </div>
                        </div>
                        <div class="rounded-full bg-plant-shop px-4 py-2 ring-1 ring-zinc-400">
                            <AppRating :rating="review.rating" />
                        </div>
                    </div>

                    <p class="text-lg leading-relaxed text-zinc-700 lg:text-xl">
                        "{{ review.body }}"
                    </p>
                </div>
            </div>
        </div>

        <div v-if="reviews.data.length === 0" class="py-32 text-center">
            <div
                class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-zinc-800 text-4xl"
            >
                🍃
            </div>
            <h2 class="mb-2 text-2xl font-bold text-zinc-300">No reviews yet</h2>
            <p class="text-zinc-50">Be the first to share your experience with our plants!</p>
        </div>
    </div>
</template>

<style scoped>
    .review-card {
        animation: reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        opacity: 0;
        transform: translateY(30px);
    }

    @keyframes reveal {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
