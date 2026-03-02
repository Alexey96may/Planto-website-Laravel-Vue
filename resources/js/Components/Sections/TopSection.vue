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
        <div class="container top__container">
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
                    v-for="plant in topPlants"
                    :key="plant.id"
                    :plant="plant"
                    :is-in-cart="cartIds.includes(plant.id)"
                    :is-processing="processingId === plant.id"
                    @add-to-cart="emit('add-to-cart', $event)"
                ></PlantTopCard>
            </div>
        </div>
    </section>
</template>
