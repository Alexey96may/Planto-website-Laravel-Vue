<script setup lang="ts">
    import { computed, ref, watch } from 'vue';

    import { Head, usePage } from '@inertiajs/vue3';

    import { Seo, Settings } from '@/types';

    interface SeoProps {
        seo?: Seo;
        forceRobots?: string;
    }

    const props = defineProps<SeoProps>();
    const page = usePage();

    const getSeoData = () => {
        const fromController = page.props.seo as Seo;
        const settings = page.props.settings as Settings;

        return {
            title:
                props.seo?.title || fromController?.title || settings?.site_name || 'Planto Shop',
            description:
                props.seo?.description ||
                fromController?.description ||
                'Welcome to the future of plants.',
            robots:
                props.forceRobots || props.seo?.robots || fromController?.robots || 'index, follow',
            image: props.seo?.image || fromController?.image || '/images/og-default.png',
            canonical: props.seo?.canonical || fromController?.canonical || '',
            keywords: props.seo?.keywords || fromController?.keywords || '',
        };
    };

    const displayTitle = ref(getSeoData().title);
    const currentSeo = computed(() => getSeoData());

    watch(
        () => currentSeo.value.title,
        (newTitle) => {
            setTimeout(() => {
                displayTitle.value = newTitle;
            }, 50);
        },
    );
</script>

<template>
    <Head>
        <title>{{ currentSeo.title }}</title>
        <meta name="description" :content="currentSeo.description" />
        <meta v-if="currentSeo.keywords" name="keywords" :content="currentSeo.keywords" />
        <meta name="robots" :content="currentSeo.robots" />
        <link v-if="currentSeo.canonical" rel="canonical" :href="currentSeo.canonical" />

        <meta property="og:title" :content="currentSeo.title" />
        <meta property="og:description" :content="currentSeo.description" />
        <meta property="og:image" :content="currentSeo.image" />
        <meta property="og:type" content="website" />
        <meta property="og:url" :content="currentSeo.canonical || ''" />
    </Head>
</template>
