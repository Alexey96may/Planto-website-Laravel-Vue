<script setup lang="ts">
    import { computed } from 'vue';

    import { useForm, usePage } from '@inertiajs/vue3';

    import IconLogo from 'img/icons/logo.svg?component';

    import NavigationFooter from '@/Components/Shared/NavigationFooter.vue';
    import { SharedData } from '@/types';

    interface NewsletterForm {
        email: string;
    }

    const form = useForm<NewsletterForm>({
        email: '',
    });

    const submit = () => {
        form.post(route('newsletter.store'), {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
                alert('Вы успешно подписаны!'); //todo
            },
        });
    };

    const page = usePage<SharedData>();

    const footerMenuItems = computed(() => page.props.navigation?.footer || []);
</script>

<template>
    <footer class="footer relative z-[3]" id="footer" aria-label="Footer">
        <div class="footer__container container">
            <div class="footer__top" aria-label="Footer top side">
                <div class="footer__info" aria-label="Footer information">
                    <div class="logo" aria-label="Logo field">
                        <IconLogo class="logo__image" aria-label="Logo image"></IconLogo>
                        <span class="logo__text" aria-label="Logo text">{{
                            $page.props.settings?.site_name
                        }}</span>
                    </div>

                    <p class="footer__descr">
                        {{ $page.props.settings?.footer_main_text || 'Site info will be here!' }}
                    </p>
                </div>

                <NavigationFooter />

                <div class="footer__subscribe" aria-label="Subscribe field">
                    <h3 class="footer__subscribe-title">For Every Update.</h3>

                    <form class="footer__form" @submit.prevent="submit" aria-label="Subscribe form">
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Your email"
                            class="footer__form-text"
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                        <input
                            class="button footer__form-button"
                            type="submit"
                            aria-label="To Subscribe"
                            :value="form.processing ? 'Sending...' : 'Subscribe'"
                            :disabled="form.processing"
                        />
                        <span v-if="form.errors.email" class="text-sm text-red-500">
                            {{ form.errors.email }}
                        </span>
                    </form>
                </div>
            </div>

            <div class="footer__bottom" aria-label="Footer bottom side">
                <ul class="footer__socials" aria-label="Socials">
                    <li
                        class="footer__social"
                        aria-label="Facebook social"
                        v-if="$page.props.settings?.link_fb"
                    >
                        <a
                            :href="$page.props.settings?.link_fb"
                            :aria-label="$page.props.settings?.link_fb"
                            >FB</a
                        >
                    </li>
                    <li
                        class="footer__social"
                        aria-label="X social"
                        v-if="$page.props.settings?.link_x"
                    >
                        <a
                            :href="$page.props.settings?.link_x"
                            :aria-label="$page.props.settings?.link_x"
                            >X</a
                        >
                    </li>
                    <li
                        class="footer__social"
                        aria-label="Linkedin social"
                        v-if="$page.props.settings?.link_li"
                    >
                        <a
                            :href="$page.props.settings?.link_li"
                            :aria-label="$page.props.settings?.link_li"
                            >LI</a
                        >
                    </li>
                </ul>

                <div class="copyright" aria-label="Copyright">
                    {{ $page.props.settings?.footer_rights || '© All rights reserved' }}
                </div>
            </div>
        </div>
    </footer>
</template>

<style lang="scss" scoped>
    @use '../../../scss/bootstrap' as b;

    .footer {
        background-color: #1b2316;
        padding: calc(1rem * (90px / b.$basicFontSize)) 0 calc(1rem * (88px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            padding: calc(1rem * (60px / b.$basicFontSize)) 0 calc(1rem * (58px / b.$basicFontSize));
        }
    }

    .footer__container {
        @include b.flex(flex-start, flex-start, column);
        gap: calc(1rem * (84px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            gap: calc(1rem * (76px / b.$basicFontSize));
        }
    }

    .footer__top {
        @include b.flex(space-between, flex-start);
        width: 100%;
        flex-wrap: wrap;
        column-gap: calc(1rem * (40px / b.$basicFontSize));
        row-gap: calc(1rem * (80px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            flex-direction: column;
            row-gap: calc(1rem * (60px / b.$basicFontSize));
        }
    }

    .footer__info {
        max-width: calc(1rem * (580px / b.$basicFontSize));

        .logo {
            gap: calc(1rem * (24px / b.$basicFontSize));
            margin-bottom: calc(1rem * (43px / b.$basicFontSize));
        }

        .logo__text {
            font-size: calc(1rem * (58px / b.$basicFontSize));
            font-weight: 900;

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (36px / b.$basicFontSize));
            }
        }

        .logo__image {
            width: calc(1rem * (94px / b.$basicFontSize));
            height: calc(1rem * (94px / b.$basicFontSize));
            aspect-ratio: 1;

            @media (max-width: b.$mediaMobile) {
                width: calc(1rem * (62px / b.$basicFontSize));
                height: calc(1rem * (62px / b.$basicFontSize));
            }
        }
    }

    .footer__descr {
        font-size: calc(1rem * (28px / b.$basicFontSize));
        color: b.$lightTextColor;
        font-weight: 500;

        @media (max-width: b.$mediaMobile) {
            font-size: calc(1rem * (18px / b.$basicFontSize));
        }
    }

    .footer__subscribe {
        @media (max-width: b.$mediaTablet) {
            width: 100%;
        }
    }

    .footer__form {
        position: relative;
    }

    .footer__form-text {
        padding: calc(1rem * (22px / b.$basicFontSize)) calc(1rem * (200px / b.$basicFontSize))
            calc(1rem * (22px / b.$basicFontSize)) calc(1rem * (29px / b.$basicFontSize));
        border: 2px solid white;
        border-radius: calc(1rem * (8px / b.$basicFontSize));
        background-color: transparent;
        color: b.$lightTextColor;
        height: calc(1rem * (72px / b.$basicFontSize));
        width: clamp(360px, 25vw, calc(1rem * (562px / b.$basicFontSize)));

        @media (max-width: b.$mediaTablet) {
            width: 100%;
        }

        @media (max-width: b.$mediaMobile) {
            height: calc(1rem * (62px / b.$basicFontSize));
            font-size: calc(1rem * (20px / b.$basicFontSize));
        }

        &::placeholder {
            font-size: calc(1rem * (24px / b.$basicFontSize));
            color: b.$lightTextColor;
            font-weight: 500;
            opacity: b.$opacity;

            @media (max-width: b.$mediaMobile) {
                font-size: calc(1rem * (20px / b.$basicFontSize));
            }
        }
    }

    .footer__form-button {
        @include b.position(
            absolute,
            -1,
            top,
            right,
            calc(1rem * (4px / b.$basicFontSize)),
            calc(1rem * (4px / b.$basicFontSize))
        );
        width: calc(1rem * (165px / b.$basicFontSize));
        height: calc(1rem * (64px / b.$basicFontSize));
        font-size: calc(1rem * (22px / b.$basicFontSize));
        border-radius: calc(1rem * (8px / b.$basicFontSize));
        background-color: b.$lightBgColor;
        color: black;
        font-weight: 700;
        text-transform: uppercase;
        cursor: pointer;
        opacity: 1;

        @media (max-width: b.$mediaMobile) {
            font-size: calc(1rem * (18px / b.$basicFontSize));
            width: calc(1rem * (140px / b.$basicFontSize));
            height: calc(1rem * (54px / b.$basicFontSize));
        }
    }

    .footer__subscribe-title {
        font-size: calc(1rem * (28px / b.$basicFontSize));
        font-weight: 800;
        color: b.$lightTextColor;
        margin-bottom: calc(1rem * (50px / b.$basicFontSize));

        @media (max-width: b.$mediaMobile) {
            font-size: calc(1rem * (26px / b.$basicFontSize));
            margin-bottom: calc(1rem * (30px / b.$basicFontSize));
        }
    }

    .footer__bottom {
        @include b.flex(space-between, center, row, wrap);
        width: 100%;
        gap: b.$mainGap;
    }

    .footer__socials {
        @include b.flex(space-between, center);
        gap: clamp(0.5rem, 4vw, calc(1rem * (61px / b.$basicFontSize)));
    }

    .footer__social {
        a {
            font-size: calc(1rem * (28px / b.$basicFontSize));
            color: b.$lightTextColor;
            font-weight: 800;
        }
    }

    .copyright {
        font-size: calc(1rem * (24px / b.$basicFontSize));
        font-weight: 500;
        color: b.$lightTextColor;
        width: clamp(360px, 25vw, calc(1rem * (562px / b.$basicFontSize)));

        @media (max-width: b.$mediaTablet) {
            width: auto;
        }

        @media (max-width: b.$mediaMobile) {
            font-size: calc(1rem * (18px / b.$basicFontSize));
        }
    }
</style>
