<script setup lang="ts">
    import { computed } from 'vue';

    import IconBag from 'img/icons/bag.svg?component';

    import { useSound } from '@/composables/useSound';

    interface Props {
        isInCart?: boolean;
        isProcessing?: boolean;
        disabled?: boolean;
        classes?: string;
        ariaLabel?: string;
    }

    const props = withDefaults(defineProps<Props>(), {
        isInCart: false,
        isProcessing: false,
        disabled: false,
        classes: '',
        ariaLabel: 'Add to Cart',
    });

    const emit = defineEmits<{
        (e: 'click'): void;
    }>();

    const handleClick = () => {
        if (!props.isInCart && !props.disabled && !props.isProcessing) {
            emit('click');
        }
    };

    const computedAriaLabel = computed(() => {
        if (props.isProcessing) return 'Adding to cart...';
        if (props.isInCart) return 'Already in cart';
        return props.ariaLabel;
    });

    const { playClick } = useSound();
</script>

<template>
    <button
        class="transition-all duration-200"
        :class="[
            classes,
            {
                'pointer-events-none opacity-50 hover:opacity-50': disabled || isProcessing,
                'cursor-default bg-emerald-700/50': isInCart,
                'hover:brightness-110 active:scale-90': !disabled && !isProcessing && !isInCart,
                'focus-visible:ring-2 focus-visible:ring-emerald-500 focus-visible:ring-offset-2':
                    !disabled,
            },
        ]"
        :disabled="isInCart || disabled || isProcessing"
        :aria-label="computedAriaLabel"
        :aria-busy="isProcessing"
        :aria-pressed="isInCart"
        @click="handleClick"
        @mousedown="!disabled && !isProcessing && !isInCart ? playClick() : null"
    >
        <IconBag class="button-image" :class="{ 'text-green-500': isInCart }" aria-hidden="true" />

        <span
            v-if="isProcessing"
            class="absolute inset-0 flex items-center justify-center"
            aria-hidden="true"
        >
        </span>
    </button>
</template>
