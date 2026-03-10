<script setup lang="ts">
    import { computed } from 'vue';

    interface Props {
        checked: boolean | any[];
        value?: any;
    }

    const emit = defineEmits<{
        (e: 'update:checked', value: boolean | any[]): void;
    }>();

    const props = defineProps<Props>();

    const proxyChecked = computed({
        get(): boolean | any[] {
            return props.checked;
        },
        set(val: boolean | any[]) {
            emit('update:checked', val);
        },
    });
</script>

<template>
    <input
        type="checkbox"
        :value="value"
        v-model="proxyChecked"
        class="h-5 w-5 cursor-pointer rounded border-zinc-700 bg-zinc-800/50 text-emerald-600 shadow-sm transition-all duration-200 focus:ring-2 focus:ring-emerald-500/30 focus:ring-offset-0 active:scale-90 disabled:opacity-50"
    />
</template>
