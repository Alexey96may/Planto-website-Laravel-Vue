<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import { useSound } from '@/composables/useSound';

    interface Props {
        href: string;
        ariaLabel?: string;
        classes?: string;
    }

    defineProps<Props>();

    const { playClick } = useSound();
</script>

<template>
    <Link
        :href="href"
        :aria-label="ariaLabel"
        class="button--rect card__link inline-flex items-center justify-center text-center"
        :class="classes"
        @click="playClick"
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
