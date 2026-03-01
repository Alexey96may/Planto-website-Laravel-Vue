<script setup lang="ts">
    import { handleImageError } from '@/utils';

    defineProps<{
        src: string;
        alt?: string;
        className?: string;
    }>();
</script>

<template>
    <img
        :src="src"
        :alt="alt || 'Image'"
        :class="className"
        @error="handleImageError"
        class="object-cover"
    />
</template>
