<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import PlantBestCard from '@/Components/Cards/BestCard.vue';
    import { Feature, SharedData } from '@/types';

    const page = usePage<SharedData>();

    const sectionTitle = computed(() => {
        return page.props.settings?.section_o2_title || '';
    });

    const sectionLink = computed(() => {
        return page.props.settings?.section_o2_link || '';
    });

    const { features = [] } = defineProps<{ features: Feature[] }>();
</script>

<template>
    <section class="best" id="best" aria-label="Review section" v-if="features.length">
        <div class="container best__container">
            <h2 class="title best__title" v-if="sectionLink">
                <component
                    :is="sectionLink ? 'Link' : 'span'"
                    :href="sectionLink"
                    :aria-label="sectionLink ? sectionTitle : undefined"
                >
                    {{ sectionTitle }}
                </component>
            </h2>

            <div class="slider review__slider" aria-label="Slider">
                <div class="slider__cards" aria-label="Slider cards">
                    <PlantBestCard
                        v-for="feature in features"
                        :key="feature.id"
                        :feature="feature"
                    />
                </div>

                <div class="slider__bottom-buttons" aria-label="Slider bottom buttons">
                    <button
                        class="bottom-button bottom-button--active"
                        aria-label="To active slide"
                    ></button>
                    <button class="bottom-button" aria-label="To second slide"></button>
                    <button class="bottom-button" aria-label="To third slide"></button>
                </div>
            </div>
        </div>
    </section>
</template>
