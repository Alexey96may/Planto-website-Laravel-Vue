<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';

    import { PaginationLink } from '@/types';

    const { links = [], disabled = false } = defineProps<{
        links?: PaginationLink[];
        disabled?: boolean;
    }>();
</script>

<template>
    <div
        v-if="links.length > 3"
        class="mt-12 flex justify-center space-x-2 transition-all duration-300"
        :class="{ 'pointer-events-none opacity-50': disabled }"
    >
        <template v-for="(link, k) in links" :key="k">
            <div
                v-if="!link.url"
                v-html="link.label"
                class="rounded-xl border border-white/5 bg-black/20 px-4 py-3 text-[10px] font-black uppercase tracking-widest text-zinc-600"
            />

            <Link
                v-else
                :href="link.url"
                v-html="link.label"
                class="rounded-xl border px-4 py-3 text-[10px] font-black uppercase tracking-widest transition-all"
                :class="{
                    'border-[#c5d86d] bg-[#c5d86d] text-black shadow-[0_0_20px_rgba(197,216,109,0.3)]':
                        link.active,
                    'border-white/5 bg-black/40 text-zinc-400 hover:scale-105 hover:border-[#c5d86d]/50 hover:text-white active:scale-95':
                        !link.active,
                }"
                preserve-scroll
            />
        </template>
    </div>
</template>
