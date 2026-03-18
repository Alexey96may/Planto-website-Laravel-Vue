<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';

    import { Seo } from '@/types';

    interface SeoProps {
        seo: Seo;
    }

    const props = withDefaults(defineProps<SeoProps>(), {
        seo: () => ({
            title: 'Planto Shop',
            description: 'Welcome to the future of plants.',
            robots: 'index, follow',
            image: '/images/og-default.png',
            canonical: window.location.origin + window.location.pathname,
        }),
    });
</script>

<template>
    <Head>
        <title>{{ seo.title }}</title>

        <meta name="description" :content="seo.description" />
        <meta v-if="seo.keywords" name="keywords" :content="seo.keywords" />
        <meta name="robots" :content="seo.robots || 'index, follow'" />

        <link rel="canonical" :href="seo.canonical" />

        <meta property="og:title" :content="seo.title" />
        <meta property="og:description" :content="seo.description" />
        <meta property="og:image" :content="seo.image || '/images/og-default.png'" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="seo.canonical" />
    </Head>
</template>
