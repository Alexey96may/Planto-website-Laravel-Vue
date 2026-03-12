<script setup lang="ts">
    import { onMounted, onUnmounted, ref, watch } from 'vue';

    import { Head, router } from '@inertiajs/vue3';

    import { Calendar } from 'lucide-vue-next';

    import CommentsFilter from '@/Components/Shared/CommentsFilter.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import AppRating from '@/Components/UI/AppRating.vue';
    import ReviewSkeleton from '@/Components/UI/ReviewSkeleton.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { PaginatedResponse, Review } from '@/types';

    defineOptions({
        layout: MainLayout,
    });

    const props = defineProps<{
        reviews: PaginatedResponse<Review>;
        averageRating: number;
        filters?: { sort: string };
    }>();

    const isLoading = ref(false);
    let unregisterStart: () => void;
    let unregisterFinish: () => void;

    onMounted(() => {
        unregisterStart = router.on('start', () => (isLoading.value = true));
        unregisterFinish = router.on('finish', () => (isLoading.value = false));
    });

    onUnmounted(() => {
        if (unregisterStart) unregisterStart();
        if (unregisterFinish) unregisterFinish();
    });

    const selectedSort = ref(props.filters?.sort || 'newest');

    watch(selectedSort, (newValue) => {
        router.get(
            route('reviews.index'),
            { sort: newValue },
            {
                preserveState: true,
                preserveScroll: true,
                only: ['reviews', 'filters'],
            },
        );
    });

    const getInitials = (name: string) => (name ? name.charAt(0).toUpperCase() : 'A');

    const formatDate = (dateStr?: string) => {
        if (!dateStr) return '';
        return new Date(dateStr).toLocaleDateString();
    };
</script>

<template>
    <Head title="Customer Reviews" />

    <div class="mx-auto max-w-5xl bg-plant-shop px-6 py-16 lg:py-24">
        <header class="mb-16">
            <div>
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-zinc-200 lg:text-6xl">
                    What our <span class="text-emerald-600">customers say</span>
                </h1>
                <p class="text-lg text-zinc-500">Real feedback from our plant-loving community.</p>
            </div>
        </header>

        <div
            v-if="averageRating"
            class="mb-8 flex flex-col items-center gap-8 rounded-[1rem] border border-emerald-100 p-10 sm:justify-between sm:rounded-[2.5rem] lg:flex-row"
        >
            <div class="flex flex-col items-center gap-6 sm:flex-row">
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

        <CommentsFilter v-model="selectedSort" class="mb-8 ml-auto w-full md:w-1/3" />

        <div class="grid gap-8">
            <TransitionGroup name="list-fade">
                <template v-if="isLoading">
                    <ReviewSkeleton v-for="i in reviews.data.length || 3" :key="'skeleton-' + i" />
                </template>

                <template v-else>
                    <div
                        v-for="(review, index) in reviews.data"
                        :key="review.id"
                        class="review-card group relative overflow-hidden rounded-[1rem] border border-white/25 bg-[#161b14] p-6 shadow-2xl transition-all hover:border-[#c5d86d]/20 sm:p-10 xl:rounded-[2rem]"
                        :style="{ animationDelay: `${index * 100}ms` }"
                    >
                        <div
                            class="absolute -right-4 -top-6 select-none font-serif text-[12rem] text-[#c5d86d]/5"
                        >
                            “
                        </div>

                        <div class="relative z-10">
                            <div
                                class="mb-8 flex items-center justify-between border-b border-white/5 pb-6"
                            >
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-full border border-[#c5d86d]/20 bg-zinc-800"
                                    >
                                        <AppImage
                                            v-if="review.user?.avatar_url"
                                            :src="review.user?.avatar_url"
                                            class="h-full w-full object-cover"
                                        />
                                        <span v-else class="text-xl font-black text-[#c5d86d]">
                                            {{ getInitials(review.user?.name) }}
                                        </span>
                                    </div>

                                    <div class="flex flex-col gap-2 md:gap-1">
                                        <h3
                                            class="text-lg font-black uppercase tracking-tight text-white"
                                        >
                                            {{ review.user?.name || 'Anonymous' }}
                                        </h3>

                                        <div class="flex items-center gap-1 md:hidden">
                                            <AppRating :rating="review.rating" />
                                            <span
                                                class="ml-2 text-xs font-black italic text-white"
                                                >{{ review.rating?.toFixed(1) }}</span
                                            >
                                        </div>

                                        <time
                                            class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-400"
                                        >
                                            <Calendar class="h-3 w-3"></Calendar>
                                            <span>{{ formatDate(review.created_at) }}</span>
                                        </time>
                                    </div>
                                </div>

                                <div
                                    class="hidden items-center gap-1 rounded-full border border-white/5 bg-black/20 px-3 py-1.5 md:flex"
                                >
                                    <AppRating :rating="review.rating" />

                                    <span class="ml-2 text-xs font-black italic text-white">{{
                                        review.rating?.toFixed(1)
                                    }}</span>
                                </div>
                            </div>

                            <p class="text-sm italic leading-relaxed text-zinc-300 lg:text-xl">
                                <span class="mr-1 font-serif text-2xl text-[#c5d86d]">“</span>
                                {{ review.body }}
                            </p>
                        </div>
                    </div>
                </template>
            </TransitionGroup>
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

    <Pagination :disabled="isLoading" :links="reviews.links" />
</template>

<style scoped>
    .list-fade-enter-active,
    .list-fade-leave-active {
        transition: all 0.4s ease;
    }

    .list-fade-enter-from {
        opacity: 0;
        transform: translateY(20px);
    }

    .list-fade-leave-to {
        opacity: 0;
        transform: translateY(-20px);
        position: absolute;
        width: 100%;
    }

    .review-card {
        will-change: transform, opacity;
    }
</style>
