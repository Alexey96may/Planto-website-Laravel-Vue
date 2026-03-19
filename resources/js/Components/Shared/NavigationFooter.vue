<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, useForm, usePage } from '@inertiajs/vue3';

    import { SharedData } from '@/types';
    import { getHref } from '@/utils/navigation';

    interface NewsletterForm {
        email: string;
    }

    const form = useForm<NewsletterForm>({
        email: '',
    });

    const page = usePage<SharedData>();

    const footerMenuItems = computed(() => page.props.navigation?.footer || []);
</script>

<template>
    <nav class="footer__nav" aria-label="Footer navigation">
        <ul class="space-y-2"></ul>
        <ul class="footer__nav-list" aria-label="Navigation list">
            <li class="footer__nav-item">Quick Link’s</li>
            <li v-for="item in footerMenuItems" :key="item.id" :aria-label="item.title">
                <Link :href="getHref(item) || '#'" :aria-label="'To ' + item.title">
                    {{ item.title }}
                </Link>
            </li>
        </ul>
    </nav>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;
    .footer__nav-list {
        @include b.flex(flex-start, flex-start, column);
        gap: calc(1rem * (26px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            gap: calc(1rem * (20px / b.$basicFontSize));
        }
    }

    .footer__nav-item {
        font-size: calc(1rem * (28px / b.$basicFontSize));
        font-weight: 800;
        color: b.$lightTextColor;

        &:first-child {
            margin-bottom: calc(1rem * (20px / b.$basicFontSize));

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (26px / b.$basicFontSize));
                margin-bottom: calc(1rem * (5px / b.$basicFontSize));
            }
        }

        a {
            font-size: calc(1rem * (24px / b.$basicFontSize));
            font-weight: 500;
            color: b.$lightTextColor;
            white-space: nowrap;

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (20px / b.$basicFontSize));
            }
        }
    }
</style>
