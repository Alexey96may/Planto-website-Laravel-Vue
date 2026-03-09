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
            <div class="header__container container">
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
                        class="font-medium text-blue-600 hover:underline"
                    >
                        <IconUser class="header__social-img" aria-label="Profile" />
                    </Link>

                    <Link :href="route('cart.index')" aria-label="To cart" class="relative">
                        <IconBag class="header__social-img" aria-label="Bag image" />
                        <span
                            v-if="$page.props.cart_count > 0"
                            class="absolute -bottom-2 -right-2 rounded-[25%] bg-emerald-900/80 px-[4px] py-[1px] text-xs text-white"
                        >
                            <template v-if="$page.props.cart_count > 9">9+</template>
                            <template v-else>{{ $page.props.cart_count }}</template>
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
