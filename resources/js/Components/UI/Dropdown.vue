<script setup lang="ts">
    import { computed, onMounted, onUnmounted, ref } from 'vue';

    defineSlots<{
        trigger(): any;
        content(): any;
    }>();

    const props = defineProps({
        align: {
            type: String,
            default: 'right',
        },
        width: {
            type: String,
            default: '48',
        },
        contentClasses: {
            type: String,
            default: 'bg-[#242b1f] border border-white/10 shadow-2xl backdrop-blur-xl',
        },
    });

    const open = ref(false);

    const closeOnEscape = (e: KeyboardEvent) => {
        if (open.value && e.key === 'Escape') {
            open.value = false;
        }
    };

    onMounted(() => document.addEventListener('keydown', closeOnEscape));
    onUnmounted(() => document.removeEventListener('keydown', closeOnEscape));

    const widthClass = computed(() => {
        const widths: Record<string, string> = {
            '48': 'w-48',
            '56': 'w-56',
            '64': 'w-64',
        };
        return widths[props.width.toString()] || 'w-48';
    });

    const alignmentClasses = computed(() => {
        if (props.align === 'left') {
            return 'origin-top-left left-0';
        } else if (props.align === 'right') {
            return 'origin-top-right right-0';
        } else {
            return 'origin-top';
        }
    });
</script>

<template>
    <div class="relative">
        <div @click="open = !open" class="cursor-pointer">
            <slot name="trigger" />
        </div>

        <div v-show="open" class="fixed inset-0 z-40" @click="open = false"></div>

        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 scale-95 translate-y-[-10px]"
            enter-to-class="opacity-100 scale-100 translate-y-0"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="opacity-100 scale-100 translate-y-0"
            leave-to-class="opacity-0 scale-95 translate-y-[-10px]"
        >
            <div
                v-show="open"
                class="absolute z-50 mt-2 rounded-2xl shadow-[0_20px_50px_rgba(0,0,0,0.5)]"
                :class="[widthClass, alignmentClasses]"
                @click="open = false"
            >
                <div
                    class="overflow-hidden rounded-2xl ring-1 ring-white/10"
                    :class="contentClasses"
                >
                    <slot name="content" />
                </div>
            </div>
        </Transition>
    </div>
</template>
