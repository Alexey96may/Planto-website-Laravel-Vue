<script setup lang="ts">
    import { computed } from 'vue';

    import IconBag from 'img/icons/bag.svg?component';

    import { useSound } from '@/composables/useSound';

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
                'opacity-50 cursor-normal bg-orange-700/50 pointer-events-none': props.disabled,
                'opacity-50 cursor-normal bg-emerald-700/50 pointer-events-none':
                    props.isProcessing,
                'bg-emerald-700/40 pointer-events-none': props.isInCart,
            },
        ];
    });

    const { playClick } = useSound();
</script>

<template>
    <button
        class="transition-all duration-200"
        :class="allIconClasses"
        :disabled="isInCart || disabled || isProcessing"
        :aria-label="areaLabel"
        @click="handleClick"
        @mousedown="playClick"
    >
        <IconBag
            class="button-image"
            :class="{ 'text-green-500': isInCart }"
            aria-label="Cart icon"
        />
    </button>
</template>
