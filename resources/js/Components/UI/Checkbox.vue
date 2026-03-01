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
        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
    />
</template>
