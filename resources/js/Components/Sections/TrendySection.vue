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
        <div class="container trendy__container">
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
