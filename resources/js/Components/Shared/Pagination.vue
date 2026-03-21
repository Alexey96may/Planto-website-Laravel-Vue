<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import { PaginationLink } from '@/types';

    const { links = [], disabled = false } = defineProps<{
        links?: PaginationLink[];
        disabled?: boolean;
    }>();
</script>

<template>
    <nav
        v-if="links.length > 3"
        class="mt-12 flex flex-wrap justify-center gap-2 transition-all duration-300 sm:gap-3"
        :class="{ 'pointer-events-none opacity-50': disabled }"
        aria-label="Pagination"
    >
        <template v-for="(link, k) in links" :key="k">
            <div
                v-if="!link.url"
                v-html="link.label"
                class="cursor-default rounded-xl border border-white/5 bg-black/20 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-600"
                aria-disabled="true"
            />

            <Link
                v-else
                :href="link.url"
                v-html="link.label"
                class="rounded-xl border px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                :class="{
                    'border-emerald-500 bg-emerald-400 text-black shadow-[0_0_25px_rgba(52,211,153,0.4)]':
                        link.active,
                    'border-white/5 bg-black/40 text-zinc-400 hover:scale-105 hover:border-emerald-400/50 hover:bg-black/60 hover:text-white active:scale-95':
                        !link.active,
                }"
                :aria-current="link.active ? 'page' : undefined"
                :aria-label="`Go to page ${link.label}`"
                preserve-scroll
            />
        </template>
    </nav>
</template>

<style scoped>
    .shadow-\[0_0_25px_rgba\(52\,211\,153\,0\.4\)\] {
        transition: shadow 0.3s ease-in-out;
    }
    :deep(span) {
        display: inline-block;
        line-height: 1;
    }
</style>
