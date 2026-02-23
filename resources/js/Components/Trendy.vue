<script setup>
import { Link } from "@inertiajs/vue3";
import IconBag from "img/icons/bag.svg?component";
import TrendyCard from "@/Components/TrendyCard.vue";

defineProps({
    trendyPlants: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <section class="trendy" id="trendy" aria-label="Trendy plants section">
        <div class="container trendy__container">
            <h2
                class="trendy__title"
                v-if="$page.props.settings.section_trendy_link"
            >
                <Link
                    :href="$page.props.settings.section_trendy_link"
                    :aria-label="$page.props.settings.section_trendy_title"
                >
                    {{ $page.props.settings.section_trendy_title }}
                </Link>
            </h2>
            <h2 class="trendy__title" v-else>
                {{ $page.props.settings.section_trendy_title }}
            </h2>
            <div class="trendy__cards">
                <TrendyCard
                    v-for="plant in trendyPlants"
                    :key="plant.id"
                    :plant="plant"
                ></TrendyCard>
            </div>
        </div>
    </section>
</template>
