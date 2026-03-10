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
            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold leading-5 transition duration-200 ease-in-out focus:outline-none tracking-wide';

        const activeClasses =
            'border-[#c5d86d] text-[#c5d86d] drop-shadow-[0_0_8px_rgba(197,216,109,0.5)]';

        const inactiveClasses =
            'border-transparent text-zinc-500 hover:text-zinc-200 hover:border-zinc-700 focus:text-zinc-200 focus:border-zinc-700';

        return `${baseClasses} ${props.active ? activeClasses : inactiveClasses}`;
    });
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
