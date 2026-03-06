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

    .trendy__container {
        .trendy-card:nth-child(2) {
            .card__img-wrapper {
                left: unset;
                right: 0;
            }

            .card__info {
                margin-left: 0;
                margin-right: min(46.5vw, calc(1rem * (640px / b.$basicFontSize)));

                @media (max-width: b.$mediaTablet) {
                    margin-right: 0;
                }
            }
        }
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

    .trendy-card {
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        max-width: unset;
        width: 100%;
        aspect-ratio: unset;
        max-height: unset;
        margin-top: 0;
        padding: min(5.2rem, 8vw) calc(1rem * (50px / b.$basicFontSize)) min(5.2rem, 8vw)
            calc(1rem * (120px / b.$basicFontSize));

        @media (max-width: b.$mediaTablet) {
            padding: calc(1rem * (40px / b.$basicFontSize));
            gap: calc(1rem * (60px / b.$basicFontSize));
        }

        @media (max-width: b.$mediaMobile) {
            flex-direction: column;
            justify-content: center;
            padding: calc(1rem * (40px / b.$basicFontSize));
            gap: calc(1rem * (34px / b.$basicFontSize));
        }

        &::before {
            content: '';
            filter: none;
            background: url('../../../images/hero-bigCard_bg.png') center / 100% 100% no-repeat;
            border-radius: calc(1rem * (24px / b.$basicFontSize));
            backdrop-filter: blur(5px);
            overflow: hidden;

            @media (max-width: b.$mediaMobile) {
                border-radius: 0rem;
                background: url('../../../images/hero-bigCard_bg-mobile.png') center / 100% 100%
                    no-repeat;
                border-radius: 9vw;
            }
        }

        .card__info {
            @include b.flex(flex-start, flex-start, column);
            margin-left: min(46.5vw, calc(1rem * (734px / b.$basicFontSize)));
            gap: calc(1rem * (24px / b.$basicFontSize));

            @media (max-width: b.$mediaTablet) {
                margin-left: 0;
                flex-basis: 65%;
            }

            @media (max-width: b.$mediaMobile) {
                flex-basis: unset;
            }
        }

        .card__img-wrapper {
            @include b.position(absolute, -1, top, left, 0, 0);
            max-width: unset;
            left: 0;
            top: unset;
            bottom: 0;
            translate: 0 0;
            margin-top: 0;
            width: auto;
            height: auto;
            margin-bottom: 0;
            aspect-ratio: unset;

            @media (max-width: 1154px) {
                bottom: auto;
            }

            @media (max-width: b.$mediaTablet) {
                position: static;
                flex-basis: 35%;
            }

            @media (max-width: b.$mediaMobile) {
                margin-top: -35%;
                flex-basis: unset;
                max-width: 300px;
            }

            img {
                max-width: min(46.5vw, calc(1rem * (728px / b.$basicFontSize)));
                object-fit: contain;
                width: auto;
                height: auto;

                @media (max-width: b.$mediaTablet) {
                    width: 100%;
                }
            }
        }

        .card__title {
            padding: 0;
            margin: 0;
            white-space: wrap;
        }

        .card__descr {
            font-size: calc(1rem * (20px / b.$basicFontSize));
            padding: 0;
        }

        .card__price {
            padding: 0;
        }
    }
</style>
