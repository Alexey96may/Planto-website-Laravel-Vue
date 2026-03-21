<script setup lang="ts">
    import { handleImageError } from '@/utils';

    interface Props {
        src: string;
        alt?: string;
        className?: string;
        isDecorative?: boolean;
    }

    const props = withDefaults(defineProps<Props>(), {
        alt: '',
        isDecorative: false,
    });
</script>

<template>
    <img
        :src="src"
        :alt="isDecorative ? '' : alt || 'Plant image'"
        :role="isDecorative ? 'presentation' : undefined"
        :aria-hidden="isDecorative ? 'true' : undefined"
        :class="className"
        @error="handleImageError"
        loading="lazy"
        class="object-cover"
    />
</template>
