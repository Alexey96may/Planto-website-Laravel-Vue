<script setup lang="ts">
    import { computed } from 'vue';

    import { handleImageError } from '@/utils';

    interface ImageMetadata {
        src: string;
        format: string;
        width?: number;
        height?: number;
    }

    interface Props {
        src: string | ImageMetadata[];
        alt?: string;
        className?: string;
        isDecorative?: boolean;
    }

    const props = withDefaults(defineProps<Props>(), {
        alt: '',
        isDecorative: false,
    });

    const isOptimized = computed(() => typeof props.src === 'object' && props.src !== null);

    const optimizedSources = computed((): ImageMetadata[] => {
        return isOptimized.value ? (props.src as ImageMetadata[]) : [];
    });

    const fallbackSrc = computed((): string => {
        if (!isOptimized.value) return props.src as string;

        const fallback = optimizedSources.value.find(
            (i) => i.format === 'jpg' || i.format === 'png' || i.format === 'jpeg',
        );

        return fallback ? fallback.src : optimizedSources.value[0].src;
    });
</script>

<template>
    <picture v-if="isOptimized">
        <source
            v-for="source in optimizedSources"
            :key="source.src"
            :srcset="source.src"
            :type="`image/${source.format}`"
        />
        <img
            :src="fallbackSrc"
            :alt="isDecorative ? '' : alt || 'Plant image'"
            :role="isDecorative ? 'presentation' : undefined"
            :aria-hidden="isDecorative ? 'true' : undefined"
            :class="className"
            @error="handleImageError"
            loading="lazy"
            class="object-cover"
        />
    </picture>

    <img
        v-else
        :src="src as string"
        :alt="isDecorative ? '' : alt || 'Plant image'"
        :class="className"
        @error="handleImageError"
        loading="lazy"
        class="object-cover"
    />
</template>
