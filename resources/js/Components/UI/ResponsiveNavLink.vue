<script setup lang="ts">
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';

    interface Props {
        href: string;
        active?: boolean;
    }

    const props = defineProps<Props>();

    const classes = computed(() => {
        const baseClasses =
            'block w-full px-4 py-3 rounded-2xl text-start text-sm font-bold uppercase tracking-widest transition-all duration-200 focus:outline-none';

        const activeClasses =
            'bg-[#c5d86d]/10 text-[#c5d86d] border border-[#c5d86d]/20 shadow-[0_0_15px_rgba(197,216,109,0.05)]';

        const inactiveClasses =
            'text-zinc-300 hover:text-zinc-200 hover:bg-white/5 border border-transparent';

        return `${baseClasses} ${props.active ? activeClasses : inactiveClasses}`;
    });
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
