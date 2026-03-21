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
            'inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold leading-5 transition-all duration-200 ease-in-out tracking-wide focus:outline-none';

        const activeClasses =
            'border-[#c5d86d] text-[#c5d86d] drop-shadow-[0_0_8px_rgba(197,216,109,0.3)]';

        const inactiveClasses =
            'border-transparent text-zinc-500 hover:text-zinc-200 hover:border-zinc-700 ' +
            'focus-visible:text-zinc-200 focus-visible:ring-2 focus-visible:ring-[#c5d86d]/20 focus-visible:rounded-sm';

        return `${baseClasses} ${props.active ? activeClasses : inactiveClasses}`;
    });
</script>

<template>
    <Link :href="href" :class="classes" :aria-current="active ? 'page' : undefined">
        <slot />
    </Link>
</template>
