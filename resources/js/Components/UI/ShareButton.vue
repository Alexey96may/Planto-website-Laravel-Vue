<script setup lang="ts">
    import { computed, onMounted, ref } from 'vue';

    import { usePage } from '@inertiajs/vue3';

    import { Check, Share2 } from 'lucide-vue-next';

    const props = defineProps<{
        title: string;
        text: string;
        url?: string;
        variant?: 'icon' | 'full';
    }>();

    const isCopied = ref(false);
    const finalUrl = ref('');

    const currentUrl = computed(() => props.url || usePage().url);

    onMounted(() => {
        finalUrl.value = currentUrl.value || window.location.href;
    });

    const handleShare = async () => {
        const urlToShare = finalUrl.value || currentUrl.value || '';
        if (!urlToShare) return;

        if (typeof navigator !== 'undefined' && navigator.share) {
            try {
                await navigator.share({
                    title: props.title,
                    text: props.text,
                    url: urlToShare,
                });
            } catch (err) {
                console.error(err);
            }
        } else {
            try {
                await navigator.clipboard.writeText(urlToShare);
                isCopied.value = true;
                setTimeout(() => (isCopied.value = false), 2000);
            } catch (err) {
                console.error('Copy failed');
            }
        }
    };

    const buttonClasses = computed(() => {
        const base =
            'relative flex items-center justify-center gap-2 overflow-hidden transition-all outline-none ';
        const variants = {
            icon: 'h-8 w-8 p-0 text-zinc-600 hover:text-emerald-400 focus-visible:ring-2 focus-visible:ring-emerald-500/50 rounded-full',
            full: 'group border text-zinc-400 border-white/5 bg-white/5 px-4 py-2 text-xs font-medium rounded-xl hover:border-emerald-500/30 hover:bg-emerald-500/10 hover:text-emerald-400 focus-visible:ring-2 focus-visible:ring-emerald-500/50 active:scale-95',
        };
        return `${base} ${variants[props.variant || 'icon']}`;
    });
</script>

<template>
    <button
        type="button"
        @click="handleShare"
        :class="buttonClasses"
        :aria-label="variant === 'icon' ? 'Share this page' : undefined"
    >
        <span class="sr-only" aria-live="polite">
            {{ isCopied ? 'Link successfully copied to clipboard' : '' }}
        </span>

        <transition name="fade" mode="out-in">
            <div v-if="!isCopied" class="flex items-center gap-2" key="share">
                <Share2 class="h-4 w-4" aria-hidden="true" />
                <span v-if="variant !== 'icon'">Share</span>
            </div>
            <div v-else class="flex items-center gap-2 text-emerald-400" key="check">
                <Check class="h-4 w-4" aria-hidden="true" />
                <span v-if="variant !== 'icon'">Link Copied!</span>
            </div>
        </transition>

        <div
            v-if="variant === 'full'"
            aria-hidden="true"
            class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/5 to-transparent transition-transform duration-500 group-hover:translate-x-full"
        ></div>
    </button>
</template>
