<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    defineProps<{
        href: string;
        active?: boolean;
    }>();
</script>

<template>
    <Link
        :href="href"
        class="block w-full px-4 py-2.5 text-start text-sm font-medium leading-5 transition duration-200 ease-in-out focus:outline-none"
        :class="
            active
                ? 'bg-emerald-500/10 text-[#c5d86d]'
                : 'text-zinc-400 hover:bg-white/5 hover:text-[#c5d86d] focus:bg-white/5 focus:text-[#c5d86d]'
        "
    >
        <div class="flex items-center gap-3">
            <slot />
        </div>
    </Link>
</template>
