<script setup>
import IconBag from "img/icons/bag.svg?component";
import PlantTopCard from "@/Components/TopCard.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    topPlants: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <section class="top" id="top" aria-label="Top selling section">
        <div class="container top__container">
            <h2
                class="title top__title"
                v-if="$page.props.settings.section_selling_link"
            >
                <Link
                    :href="$page.props.settings.section_selling_link"
                    :aria-label="$page.props.settings.section_selling_title"
                >
                    {{ $page.props.settings.section_selling_title }}
                </Link>
            </h2>
            <h2 class="title top__title" v-else>
                {{ $page.props.settings.section_selling_title }}
            </h2>

            <div class="top__cards" aria-label="Cards field">
                <PlantTopCard
                    v-for="plant in topPlants"
                    :key="plant.id"
                    :plant="plant"
                ></PlantTopCard>
            </div>
        </div>
    </section>
</template>
