<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';

    import IconArrowRight from 'img/icons/arrow-right.svg?component';

    import AppExploreButton from '@/Components/UI/AppExploreButton.vue';
    import AppImage from '@/Components/UI/AppImage.vue';
    import { Feature } from '@/types';

    const {
        feature = {} as Feature,
        index = 0,
        total = 0,
    } = defineProps<{
        feature: Feature;
        index: number;
        total: number;
    }>();

    const emit = defineEmits(['next', 'prev']);

    const formatNum = (num: number) => (num + 1).toString().padStart(2, '0');
    const totalNum = computed(() => total.toString().padStart(2, '0'));
</script>

<template>
    <figure
        class="best__card flex h-full w-full gap-4 overflow-visible rounded-[2rem] pb-8 pr-8 shadow-sm transition-shadow hover:shadow-md lg:gap-6 lg:gap-8"
    >
        <div class="relative -mt-24 mb-6 aspect-square w-1/2 flex-shrink-0 lg:h-96">
            <AppImage
                :src="feature.image_url"
                :alt="feature.title"
                class="h-full w-full object-contain drop-shadow-2xl"
            />
        </div>

        <div class="flex flex-grow flex-col gap-4 pt-8">
            <h3 class="mb-4">
                <Link
                    :href="feature.link"
                    class="text-xl font-semibold transition-colors hover:text-emerald-600"
                >
                    {{ feature.title }}
                </Link>
            </h3>

            <div
                class="card__inform mb-8 max-h-44 flex-grow text-sm leading-relaxed text-zinc-300"
                v-if="feature.description"
                v-html="feature.description"
            ></div>

            <div class="mt-auto flex items-center justify-between pt-6">
                <AppExploreButton :href="feature.link" class="button--rect scale-90" />

                <div class="flex items-center gap-3 text-sm font-medium">
                    <button @click="emit('prev')" class="transition-colors hover:text-emerald-500">
                        <IconArrowRight class="h-5 w-5 rotate-180 fill-zinc-300" />
                    </button>

                    <span class="whitespace-nowrap text-zinc-100">
                        <span class="text-zinc-400">{{ formatNum(index) }}</span> / {{ totalNum }}
                    </span>

                    <button @click="emit('next')" class="transition-colors hover:text-emerald-500">
                        <IconArrowRight class="h-5 w-5 fill-zinc-300" />
                    </button>
                </div>
            </div>
        </div>
    </figure>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .best__card {
        background: url('../../../images/slider_bg.png') center / 100% 100% no-repeat;
        border-radius: 0;

        @media (max-width: b.$mediaMobile) {
            background: url('../../../images/slider_bg-mobile.png') center / 100% 100% no-repeat;
        }
    }

    .card__inform {
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1rem;
        padding: 0 !important;

        p {
            padding: 0 !important;
        }
    }

    .card__descr {
        padding: 0;
    }

    .relative.aspect-square {
        flex: 1 1 50%;
    }

    figure,
    div,
    h3 {
        transition:
            box-shadow 0.3s ease,
            border-color 0.3s ease !important;
    }

    .best__card {
        // Твои стили фона...
        position: relative;
        z-index: 1;

        // Убедимся, что контент внутри не обрезан при вылете пыльцы
        overflow: visible !important;
    }

    // Подготовим элементы к тяжелой анимации
    .relative,
    h3,
    .mb-8,
    .mt-auto {
        will-change: transform, opacity, filter;
    }
</style>
