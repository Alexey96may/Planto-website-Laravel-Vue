<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    interface Props {
        href: string;
        ariaLabel?: string;
        classes?: string;
    }

    defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :aria-label="ariaLabel"
        class="button--rect card__link inline-flex items-center justify-center text-center"
        :class="classes"
    >
        <slot>Explore</slot>
    </Link>
</template>

<style scoped>
    .button--rect {
        display: inline-block;
        cursor: pointer;
    }
</style>
