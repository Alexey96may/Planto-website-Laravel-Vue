<script setup>
import IconLogo from "img/icons/logo.svg?component";
import { Link, usePage, useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("newsletter.store"), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            alert("Вы успешно подписаны!");
        },
    });
};

const page = usePage();
const footerMenuItems = computed(() => page.props.navigation.footer);
const currentYear = new Date().getFullYear();

const getHref = (item) => {
    if (item.type === "category" && item.category) {
        // console.log(item);
        // Генерируем ссылку на страницу магазина с фильтром по слагу категории
        return route("shop", { category: item.category.slug });
    }
    return item.link; // Для обычных ссылок
};
</script>

<template>
    <footer class="footer" id="footer" aria-label="Footer">
        <div class="container footer__container">
            <div class="footer__top" aria-label="Footer top side">
                <div class="footer__info" aria-label="Footer information">
                    <div class="logo" aria-label="Logo field">
                        <IconLogo
                            class="logo__image"
                            aria-label="Logo image"
                        ></IconLogo>
                        <span class="logo__text" aria-label="Logo text">{{
                            $page.props.settings.site_name
                        }}</span>
                    </div>

                    <p class="footer__descr">
                        {{
                            $page.props.settings.footer_main_text ||
                            "Site info will be here!"
                        }}
                    </p>
                </div>

                <nav class="footer__nav" aria-label="Footer navigation">
                    <ul class="space-y-2">
                        <li v-for="item in footerMenuItems" :key="item.id">
                            <Link
                                :href="getHref(item) || '#'"
                                class="text-gray-400 hover:text-white transition-colors text-sm"
                            >
                                {{ item.title }}
                            </Link>
                        </li>
                    </ul>
                    <!-- <ul class="footer__nav-list" aria-label="Navigation list">
                        <li class="footer__nav-item">Quick Link’s</li>
                        <li class="footer__nav-item">
                            <a href="#" aria-label="to ">Home</a>
                        </li>
                        <li class="footer__nav-item">
                            <a href="#top" aria-label="to Type’s Of plant’s"
                                >Type’s Of plant’s</a
                            >
                        </li>
                        <li class="footer__nav-item">
                            <a href="#review" aria-label="to Contact"
                                >Contact</a
                            >
                        </li>
                        <li class="footer__nav-item">
                            <a href="#" aria-label="to Privacy">Privacy</a>
                        </li>
                    </ul> -->
                </nav>

                <div class="footer__subscribe" aria-label="Subscribe field">
                    <h3 class="footer__subscribe-title">For Every Update.</h3>

                    <form
                        class="footer__form"
                        @submit.prevent="submit"
                        aria-label="Subscribe form"
                    >
                        <input
                            v-model="form.email"
                            type="email"
                            placeholder="Ваш email"
                            class="footer__form-text"
                            :class="{ 'border-red-500': form.errors.email }"
                        />
                        <input
                            class="button footer__form-button"
                            type="submit"
                            aria-label="To Subscribe"
                            :value="
                                form.processing ? 'Отправка...' : 'Подписаться'
                            "
                            :disabled="form.processing"
                        />
                        <span
                            v-if="form.errors.email"
                            class="text-red-500 text-sm"
                        >
                            {{ form.errors.email }}
                        </span>
                    </form>

                    <!-- <form
                        class="footer__form"
                        action="#"
                        method="get"
                        aria-label="Subscribe form"
                    >
                        <input
                            type="email"
                            class="footer__form-text"
                            name="footerFormText"
                            id="footerFormText"
                            placeholder="Enter Email"
                            aria-label="Email form"
                        />
                        <input
                            class="button footer__form-button"
                            type="button"
                            aria-label="To Subscribe"
                            value="Subscribe"
                        />
                    </form> -->
                </div>
            </div>

            <div class="footer__bottom" aria-label="Footer bottom side">
                <ul class="footer__socials" aria-label="Socials">
                    <li
                        class="footer__social"
                        aria-label="Facebook social"
                        v-if="$page.props.settings.link_fb"
                    >
                        <a
                            :href="$page.props.settings.link_fb"
                            :aria-label="$page.props.settings.link_fb"
                            >FB</a
                        >
                    </li>
                    <li
                        class="footer__social"
                        aria-label="X social"
                        v-if="$page.props.settings.link_x"
                    >
                        <a
                            :href="$page.props.settings.link_x"
                            :aria-label="$page.props.settings.link_x"
                            >X</a
                        >
                    </li>
                    <li
                        class="footer__social"
                        aria-label="Linkedin social"
                        v-if="$page.props.settings.link_li"
                    >
                        <a
                            :href="$page.props.settings.link_li"
                            :aria-label="$page.props.settings.link_li"
                            >LI</a
                        >
                    </li>
                </ul>

                <div class="copyright" aria-label="Copyright">
                    {{
                        $page.props.settings.footer_rights ||
                        "© All rights reserved"
                    }}
                </div>
            </div>
        </div>
    </footer>
</template>
