<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import PlantTopCard from '@/Components/Cards/TopCard.vue';
    import { Product, SharedData } from '@/types';

    interface Props {
        topPlants?: Product[];
        cartIds: number[];
        processingId?: number | null;
    }

    const { topPlants = [], cartIds = [], processingId = null } = defineProps<Props>();

    const page = usePage<SharedData>();

    const sectionTitle = computed(() => {
        return page.props.settings?.section_selling_title || '';
    });

    const sectionLink = computed(() => {
        return page.props.settings?.section_selling_link || '';
    });

    const emit = defineEmits<{
        (e: 'add-to-cart', product: Product): void;
    }>();
</script>

<template>
    <section class="top" id="top" aria-label="Top selling section">
        <div class="top__container container">
            <h2 class="title top__title">
                <Link v-if="sectionLink" :href="sectionLink" :aria-label="sectionTitle">
                    {{ sectionTitle }}
                </Link>
                <template v-else>
                    {{ sectionTitle }}
                </template>
            </h2>

            <div class="top__cards" aria-label="Cards field">
                <PlantTopCard
                    v-for="(plant, index) in topPlants"
                    :key="plant.id"
                    class="reveal-item"
                    :plant="plant"
                    :is-in-cart="cartIds.includes(plant.id)"
                    :style="{ transitionDelay: `${index * 300}ms` }"
                    :is-processing="processingId === plant.id"
                    @add-to-cart="emit('add-to-cart', $event)"
                ></PlantTopCard>
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .top {
        padding-top: calc(1rem * (143px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            padding-top: calc(1rem * (120px / b.$basicFontSize));
        }
    }

    .top__title {
        margin-bottom: calc(1rem * (76px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            margin-bottom: calc(1rem * (56px / b.$basicFontSize));
        }
    }

    .top__cards {
        @include b.flex(center, center, row, wrap);
        column-gap: calc(1rem * (41px / b.$basicFontSize));
    }

    .reveal-item {
        opacity: 0;
        transform: translate3d(0, 25px, 0);
        transition:
            opacity 0.6s ease-out,
            transform 0.6s cubic-bezier(0.25, 1, 0.5, 1);

        -webkit-font-smoothing: antialiased;
        backface-visibility: hidden;
        will-change: transform, opacity;
        outline: 1px solid transparent;
        background-clip: padding-box;
        -webkit-perspective: 1000px;
    }

    .reveal-visible .reveal-item {
        opacity: 1;
        transform: translate3d(0, 0, 0);
    }
</style>
