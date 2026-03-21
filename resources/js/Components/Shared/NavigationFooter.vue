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
    <nav class="footer__nav" aria-labelledby="footer-nav-title">
        <h3 id="footer-nav-title" class="footer__nav-title">Quick Links</h3>

        <ul class="footer__nav-list">
            <li v-for="item in footerMenuItems" :key="item.id" class="footer__nav-item">
                <Link :href="getHref(item) || '#'" class="footer__nav-link">
                    {{ item.title }}
                </Link>
            </li>
        </ul>
    </nav>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .footer__nav {
        &-title {
            font-size: calc(1rem * (28px / b.$basicFontSize));
            font-weight: 800;
            color: b.$lightTextColor;
            margin-bottom: calc(1rem * (20px / b.$basicFontSize));

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (26px / b.$basicFontSize));
                margin-bottom: calc(1rem * (10px / b.$basicFontSize));
            }
        }

        &-list {
            @include b.flex(flex-start, flex-start, column);
            gap: calc(1rem * (12px / b.$basicFontSize));
            list-style: none;
            padding: 0;

            @media (max-width: b.$mediaMobile) {
                gap: calc(1rem * (8px / b.$basicFontSize));
            }
        }

        &-link {
            font-size: calc(1rem * (20px / b.$basicFontSize));
            font-weight: 500;
            color: b.$lightTextColor;
            opacity: 0.8;
            white-space: nowrap;
            transition: all 0.3s ease;
            text-decoration: none;

            &:hover {
                opacity: 1;
                color: b.$mainTextColor;
                transform: translateX(4px);
                display: inline-block;
            }

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (18px / b.$basicFontSize));
            }
        }
    }
</style>
