<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import TrendyCard from '@/Components/Cards/TrendyCard.vue';
    import { Product, SharedData } from '@/types';

    interface Props {
        trendyPlants?: Product[];
        cartIds: number[];
        processingId?: number | null;
    }

    const { trendyPlants = [], cartIds = [], processingId = null } = defineProps<Props>();

    const page = usePage<SharedData>();

    const sectionTitle = computed(() => {
        return page.props.settings?.section_trendy_title || '';
    });

    const sectionLink = computed(() => {
        return page.props.settings?.section_trendy_link || '';
    });

    const emit = defineEmits<{
        (e: 'add-to-cart', product: Product): void;
    }>();
</script>

<template>
    <section class="trendy" id="trendy" aria-label="Trendy plants section">
        <div class="trendy__container container">
            <h2 class="trendy__title">
                <Link :href="sectionLink" :aria-label="sectionTitle" v-if="sectionLink">
                    {{ sectionTitle }}
                </Link>
                <template v-else> {{ sectionTitle }}</template>
            </h2>

            <div class="trendy__cards">
                <TrendyCard
                    v-for="plant in trendyPlants"
                    :key="plant.id"
                    :plant="plant"
                    :is-in-cart="cartIds.includes(plant.id)"
                    :is-processing="processingId === plant.id"
                    @add-to-cart="emit('add-to-cart', $event)"
                ></TrendyCard>
            </div>
        </div>
    </section>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .trendy {
        padding-top: calc(1rem * (69px / b.$basicFontSize));
        padding-bottom: calc(1rem * (99px / b.$basicFontSize));
    }

    .trendy__title {
        font-size: calc(1rem * (55px / b.$basicFontSize));
        white-space: nowrap;
        color: b.$lightTextColor;
        font-weight: 600;
        text-align: center;
        border-image: url('../../../images/border-title.png');
        border-image-slice: 1 fill;
        padding: 6px 32px;
        width: fit-content;
        margin: 0 auto calc(1rem * (109px / b.$basicFontSize));
        border-color: transparent;
        border-width: 6px;
        border-style: solid;

        @media (max-width: b.$mediaMobile) {
            font-size: calc(1rem * (32px / b.$basicFontSize));
            padding: 6px 18px;
        }
    }

    .trendy__cards {
        @include b.flex(flex-start, flex-start, column);
        gap: calc(1rem * (139px / b.$basicFontSize));
    }
</style>
