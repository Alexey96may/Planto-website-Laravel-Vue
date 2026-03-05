<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';

    import IconBag from 'img/icons/bag.svg?component';
    import IconLogo from 'img/icons/logo.svg?component';
    import IconUser from 'img/icons/user.svg?component';
    import { route } from 'ziggy-js';

    import NavigationHeader from '@/Components/Shared/NavigationHeader.vue';
</script>

<template>
    <div>
        <header class="header" id="header" aria-label="Header">
            <div class="container header__container">
                <div class="logo mr-auto lg:mr-0" aria-label="Logo field">
                    <IconLogo class="logo__image" aria-label="Logo image" />
                    <span class="logo__text" aria-label="Logo text">{{
                        $page.props.settings?.site_name
                    }}</span>
                </div>

                <NavigationHeader />

                <div class="header__socials" aria-label="Socials field">
                    <Link
                        :href="$page.props.auth.user ? route('dashboard') : route('login')"
                        :aria-label="$page.props.auth.user ? 'To Dashboard' : 'To Login'"
                        class="text-blue-600 font-medium hover:underline"
                    >
                        <IconUser class="header__social-img" aria-label="Profile" />
                    </Link>

                    <Link :href="route('cart.index')" aria-label="To cart">
                        <IconBag class="header__social-img" aria-label="Bag image" />
                        <span v-if="$page.props.cart_count > 0">
                            {{ $page.props.cart_count }}
                        </span>
                    </Link>
                </div>
            </div>
        </header>
    </div>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .header {
        padding: 2.37rem 0;

        @media (max-width: b.$mediaMobile) {
            padding: 1.2rem 0;
        }
    }

    .header__container {
        @include b.flex(space-between, center);
        gap: clamp(1rem, 3vw, 3rem);
    }

    .header__socials {
        @include b.flex(space-between, center);
        gap: clamp(b.$mainGap, 3vw, 59px);
        z-index: 9;

        a {
            display: block;
            line-height: 1;
        }
    }

    .header__social-img {
        width: 1.08rem;
        height: 1.08rem;
        vertical-align: middle;
        fill: #ffffff;
        opacity: 0.75;
        transition: all b.$basicTransTime ease;

        &:hover,
        &:focus-visible {
            opacity: 1;

            @media (hover: none) {
                opacity: 0.75;
            }
        }
    }
</style>
