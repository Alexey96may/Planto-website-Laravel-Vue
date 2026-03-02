<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';

    interface Props {
        href: string;
        active?: boolean;
    }

    const props = withDefaults(defineProps<Props>(), {
        active: false,
    });

    const classes = computed<string>(() => {
        const baseClasses =
            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none';

        const activeClasses = 'border-indigo-400 text-gray-900 focus:border-indigo-700';
        const inactiveClasses =
            'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300';

        return `${baseClasses} ${props.active ? activeClasses : inactiveClasses}`;
    });
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
