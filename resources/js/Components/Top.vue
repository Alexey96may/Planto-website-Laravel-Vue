<script setup>
import IconBag from "img/icons/bag.svg?component";
import PlantTopCard from "@/Components/TopCard.vue";

const props = defineProps({
    topPlants: {
        type: Array,
    },
});
</script>

<template>
    <section class="top" id="top" aria-label="Top selling section">
        <div class="container top__container">
            <h2 class="title top__title">Our Top Selling</h2>

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
