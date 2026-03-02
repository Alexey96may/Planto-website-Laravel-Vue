<script setup lang="ts">
    import { computed } from 'vue';

    import IconBag from 'img/icons/bag.svg?component';

    interface Props {
        isInCart?: boolean;
        isProcessing?: boolean;
        disabled?: boolean;
        classes?: string;
        areaLabel?: string;
    }

    const props = withDefaults(defineProps<Props>(), {
        isInCart: false,
        isProcessing: false,
        disabled: false,
        classes: '',
        areaLabel: 'Add to Cart',
    });

    const emit = defineEmits<{
        (e: 'click'): void;
    }>();

    const handleClick = () => {
        if (!props.isInCart && !props.disabled && !props.isProcessing) {
            emit('click');
        }
    };

    const allIconClasses = computed(() => {
        return [
            props.classes,
            {
                'opacity-50 cursor-not-allowed': props.disabled || props.isProcessing,
                'text-green-500': props.isInCart,
            },
        ];
    });
</script>

<template>
    <button
        class="transition-all duration-200"
        :class="allIconClasses"
        :disabled="isInCart || disabled || isProcessing"
        :aria-label="areaLabel"
        @click="handleClick"
    >
        <IconBag
            class="button-image"
            :class="{ 'text-green-500': isInCart }"
            aria-label="Cart icon"
        />
    </button>
</template>
