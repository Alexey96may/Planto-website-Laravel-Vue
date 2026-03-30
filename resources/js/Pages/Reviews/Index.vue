<script setup lang="ts">
    import { onMounted, onUnmounted, ref, watch } from 'vue';

    import { Link, router } from '@inertiajs/vue3';

    import { Calendar } from 'lucide-vue-next';
    import { route } from 'ziggy-js';

    import CommentsFilter from '@/Components/Shared/CommentsFilter.vue';
    import Pagination from '@/Components/Shared/Pagination.vue';
    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import AppRating from '@/Components/UI/AppRating.vue';
    import ReviewSkeleton from '@/Components/UI/ReviewSkeleton.vue';
    import WindEffect from '@/Components/UI/WindEffect.vue';
    import MainLayout from '@/Layouts/MainLayout.vue';
    import { PaginatedResponse, Review, Seo } from '@/types';

    defineOptions({ layout: MainLayout });

    const props = defineProps<{
        reviews: PaginatedResponse<Review>;
        averageRating: number;
        filters?: { sort: string };
        seo?: Seo;
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
    <SeoMeta :seo="props.seo" />

    <div class="bg-plant-shop">
        <div class="isolate mx-auto max-w-5xl px-6 py-16 lg:py-24">
            <WindEffect aria-hidden="true" :particleCount="35" :windStrength="1.5" />

            <header class="relative z-[3] mb-16">
                <h1 class="mb-4 text-4xl font-bold tracking-tight text-zinc-200 lg:text-6xl">
                    What our <span class="text-emerald-600">customers say</span>
                </h1>
                <p class="text-lg text-zinc-500">Real feedback from our plant-loving community.</p>
            </header>

            <section
                v-if="averageRating"
                aria-label="Reviews summary"
                class="relative z-[3] mb-8 flex flex-col items-center gap-8 rounded-[1rem] border border-emerald-400/50 p-10 backdrop-blur-lg sm:justify-between lg:flex-row lg:rounded-[2rem]"
            >
                <div class="flex flex-col items-center gap-6 sm:flex-row">
                    <span class="text-7xl font-black text-emerald-500" aria-hidden="true">
                        {{ averageRating.toFixed(1) }}
                    </span>
                    <div class="flex flex-col gap-1">
                        <div
                            class="mb-1 text-xs font-bold uppercase tracking-widest text-emerald-700/80"
                        >
                            Average Rating
                        </div>
                        <AppRating
                            :rating="averageRating"
                            class="origin-left scale-150"
                            :aria-label="`Average rating ${averageRating.toFixed(1)} out of 5`"
                        />
                    </div>
                </div>

                <div class="text-center sm:text-right">
                    <p class="text-sm font-medium text-emerald-600/80">
                        Based on {{ reviews.total }} verified reviews
                    </p>
                </div>
            </section>

            <main class="relative">
                <div
                    v-if="reviews.data.length !== 0"
                    class="relative z-20 overflow-hidden rounded-[1rem] border border-emerald-400/50 bg-plant-green px-4 py-8 lg:rounded-[2rem] lg:px-6 lg:py-12"
                >
                    <div class="mb-8 flex flex-col items-end gap-4">
                        <label for="sort-filter" class="sr-only">Sort reviews by</label>
                        <CommentsFilter
                            id="sort-filter"
                            v-model="selectedSort"
                            class="z-[1] w-full md:w-1/3"
                        />
                    </div>

                    <div
                        class="z-10 grid gap-8"
                        role="region"
                        aria-live="polite"
                        :aria-busy="isLoading"
                    >
                        <TransitionGroup name="list-fade">
                            <template v-if="isLoading">
                                <ReviewSkeleton
                                    v-for="i in reviews.data.length || 3"
                                    :key="'skeleton-' + i"
                                    class="review-card relative rounded-[1rem] border border-emerald-400/30 bg-[#1b2316]/60 p-4 md:p-10 xl:rounded-[2rem]"
                                />
                                <span class="sr-only">Loading reviews...</span>
                            </template>

                            <template v-else>
                                <article
                                    v-for="(review, index) in reviews.data"
                                    :key="review.id"
                                    class="review-card group relative rounded-[1rem] border border-emerald-700 bg-plant-shop px-4 py-10 transition-all duration-500 md:p-10 xl:rounded-[2rem]"
                                    :style="{ animationDelay: `${index * 100}ms` }"
                                >
                                    <div class="relative z-10 text-white">
                                        <header
                                            class="mb-8 flex items-center justify-between border-b border-white/20 pb-6"
                                        >
                                            <div
                                                class="flex w-full flex-col items-center gap-4 sm:flex-row sm:items-center"
                                            >
                                                <div
                                                    class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-full border border-[#c5d86d]/20 bg-zinc-800"
                                                    aria-hidden="true"
                                                >
                                                    <AppImage
                                                        v-if="review.user?.avatar_url"
                                                        :src="review.user?.avatar_url"
                                                        :alt="''"
                                                        class="h-full w-full object-cover"
                                                    />
                                                    <span
                                                        v-else
                                                        class="text-xl font-black text-[#c5d86d]"
                                                    >
                                                        {{ getInitials(review.user?.name) }}
                                                    </span>
                                                </div>

                                                <div
                                                    class="flex flex-col items-center gap-2 sm:items-start md:gap-1"
                                                >
                                                    <h3
                                                        class="text-center text-lg font-black uppercase tracking-tight text-white"
                                                    >
                                                        {{ review.user?.name || 'Anonymous' }}
                                                    </h3>

                                                    <div class="flex gap-1 self-center md:hidden">
                                                        <AppRating :rating="review.rating" />
                                                        <span
                                                            class="ml-2 text-xs font-black italic text-white"
                                                        >
                                                            <span class="sr-only">Rated </span
                                                            >{{ review.rating?.toFixed(1) }}
                                                        </span>
                                                    </div>

                                                    <time
                                                        :datetime="review.created_at"
                                                        class="flex justify-center gap-1 text-[10px] font-bold uppercase tracking-[0.2em] text-zinc-400 md:items-center"
                                                    >
                                                        <Calendar
                                                            class="h-3 w-3"
                                                            aria-hidden="true"
                                                        />
                                                        <span>{{
                                                            formatDate(review.created_at)
                                                        }}</span>
                                                    </time>
                                                </div>
                                            </div>

                                            <div
                                                class="hidden items-center gap-1 rounded-full border border-white/5 bg-black/20 px-3 py-1.5 md:flex"
                                            >
                                                <AppRating :rating="review.rating" />
                                                <span
                                                    class="ml-2 text-xs font-black italic text-white"
                                                >
                                                    <span class="sr-only">Rated </span
                                                    >{{ review.rating?.toFixed(1) }}
                                                </span>
                                            </div>
                                        </header>

                                        <blockquote
                                            class="text-sm italic leading-relaxed text-zinc-300 lg:text-xl"
                                        >
                                            <span
                                                class="md:text-md mr-1 font-serif text-[#c5d86d] xl:text-xl"
                                                aria-hidden="true"
                                                >“</span
                                            >
                                            {{ review.body }}
                                        </blockquote>
                                    </div>
                                </article>
                            </template>
                        </TransitionGroup>
                    </div>
                </div>

                <div
                    v-else
                    role="status"
                    class="relative z-[3] rounded-[1rem] border border-emerald-400/50 bg-plant-green py-12 text-center"
                >
                    <div
                        class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-plant-shop text-4xl"
                        aria-hidden="true"
                    >
                        🍃
                    </div>
                    <h2 class="mb-2 text-2xl font-bold text-zinc-300">No reviews yet</h2>
                    <p class="text-zinc-50">
                        <Link
                            :href="
                                $page.props.auth.user
                                    ? route('dashboard') + '#feedbackSection'
                                    : route('register')
                            "
                            class="text-emerald-500 transition-colors duration-200 hover:text-emerald-300"
                        >
                            Be the first
                        </Link>
                        to share your experience with our plants!
                    </p>
                </div>
            </main>

            <Pagination
                :disabled="isLoading"
                class="z-[3]"
                :links="reviews.links"
                aria-label="Reviews pagination"
            />
        </div>
    </div>
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
