<script setup lang="ts">
    import { computed, onUnmounted, ref } from 'vue';

    interface Props {
        withGlare?: boolean;
        intensity?: number;
        disabled?: boolean;
    }

    const props = withDefaults(defineProps<Props>(), {
        withGlare: true,
        intensity: 6,
        disabled: false,
    });

    const cardRef = ref<HTMLElement | null>(null);
    const mouseX = ref(0);
    const mouseY = ref(0);
    const isHovering = ref(false);
    const isFocused = ref(false);
    let frameId: number | null = null;

    const handleMouseMove = (e: MouseEvent) => {
        if (props.disabled) return;

        if (frameId) cancelAnimationFrame(frameId);

        frameId = requestAnimationFrame(() => {
            if (!cardRef.value) return;
            const rect = cardRef.value.getBoundingClientRect();
            mouseX.value = ((e.clientX - rect.left) / rect.width) * 2 - 1;
            mouseY.value = ((e.clientY - rect.top) / rect.height) * 2 - 1;
        });
    };

    const parallaxStyle = computed(() => {
        if (!isHovering.value && !isFocused.value) return {};
        return {
            '--mx': mouseX.value.toString(),
            '--my': mouseY.value.toString(),
            '--intensity': props.intensity.toString(),
        };
    });

    onUnmounted(() => {
        if (frameId) cancelAnimationFrame(frameId);
    });
</script>

<template>
    <div
        ref="cardRef"
        @mousemove="handleMouseMove"
        @mouseenter="isHovering = true"
        @mouseleave="
            isHovering = false;
            mouseX = 0;
            mouseY = 0;
        "
        @focusin="isFocused = true"
        @focusout="isFocused = false"
        :style="parallaxStyle"
        class="parallax-container group relative overflow-hidden"
    >
        <div
            v-if="withGlare"
            aria-hidden="true"
            class="parallax-glare pointer-events-none absolute inset-0 z-10 opacity-0 transition-opacity duration-500 group-hover:opacity-100"
        ></div>

        <slot />
    </div>
</template>

<style scoped>
    .parallax-container {
        perspective: 1200px;
        transform-style: preserve-3d;
        contain: layout paint;
    }

    @media (prefers-reduced-motion: reduce) {
        :deep(.parallax-target),
        .parallax-glare {
            transform: none !important;
            transition: none !important;
        }
    }

    :deep(.parallax-target) {
        --mx: 0;
        --my: 0;
        --intensity: 6;

        transform: rotateX(calc(var(--my) * -1 * var(--intensity) * 1deg))
            rotateY(calc(var(--mx) * var(--intensity) * 1deg)) translateX(calc(var(--mx) * -10px))
            translateY(calc(var(--my) * -10px)) scale(1.05);
        transition: transform 0.5s ease-out;
        will-change: transform;
    }

    .parallax-container:focus-within :deep(.parallax-target) {
        transform: rotateX(4deg) scale(1.05);
    }

    .parallax-glare {
        width: 150%;
        height: 150%;
        top: -25%;
        left: -25%;
        background: radial-gradient(
            circle at center,
            rgba(255, 255, 255, 0.08) 0%,
            transparent 70%
        );
        transform: translateX(calc(var(--mx) * 10%)) translateY(calc(var(--my) * 10%));
        filter: blur(40px);
    }

    :deep(.parallax-container:not(:hover):not(:focus-within) .parallax-target) {
        transition: transform 0.6s cubic-bezier(0.2, 0, 0.3, 1);
        transform: rotateX(0deg) rotateY(0deg) translateX(0) translateY(0) scale(1);
    }
</style>
