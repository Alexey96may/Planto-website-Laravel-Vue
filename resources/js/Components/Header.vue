<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import IconLogo from "img/icons/logo.svg?component";
import IconArrowMore from "img/icons/arrow-more.svg?component";
import IconSearch from "img/icons/search.svg?component";
import IconBag from "img/icons/bag.svg?component";
import { computed, ref } from "vue";

const page = usePage();

const menuItems = computed(() => page.props.navigation.header);
const mobileMenuOpen = ref(false);
</script>

<template>
    <Head title="Welcome" />
    <div>
        <header class="header" id="header" aria-label="Header">
            <div class="container header__container">
                <div class="logo" aria-label="Logo field">
                    <IconLogo class="logo__image" aria-label="Logo image" />
                    <span class="logo__text" aria-label="Logo text">{{
                        $page.props.settings.site_name
                    }}</span>
                </div>

                <!-- <nav class="nav" id="nav" aria-label="Navigation">
                    <ul class="nav__list" aria-label="Navigation list">
                        <li class="nav__item">
                            <Link
                                href="/"
                                class="nav__link"
                                aria-label="To the home page"
                            >
                                Home
                            </Link>
                        </li>
                        <li class="nav__item nav__item--plus">
                            <span class="nav__link" aria-label="To plants type"
                                >Plants Type

                                <IconArrowMore
                                    class="nav__item-more"
                                    id="moreArrow"
                                    aria-label="Arrow sprite"
                                />
                            </span>

                            <ul class="nav__item--plus-list">
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant 1</a>
                                </li>
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant 2</a>
                                </li>
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant 3</a>
                                </li>
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant 4</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav__item">
                            <Link
                                href="/shop"
                                class="nav__link"
                                aria-label="To see more plants"
                            >
                                More
                            </Link>
                        </li>
                        <li class="nav__item">
                            <a
                                class="nav__link"
                                href="#review"
                                aria-label="To contacts"
                                >Contact</a
                            >
                        </li>
                    </ul>
                </nav> -->

                <nav class="bg-white border-b border-gray-100 shadow-sm">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <div
                                    class="hidden sm:ml-10 sm:flex sm:space-x-8 h-full"
                                >
                                    <div
                                        v-for="item in menuItems"
                                        :key="item.id"
                                        class="relative flex items-center group"
                                    >
                                        <template
                                            v-if="
                                                item.children &&
                                                item.children.length > 0
                                            "
                                        >
                                            <button
                                                class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 h-full"
                                            >
                                                {{ item.title }}
                                                <svg
                                                    class="ml-1 w-4 h-4"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    />
                                                </svg>
                                            </button>

                                            <div
                                                class="absolute top-full left-0 w-48 bg-white border border-gray-100 shadow-lg rounded-b-md hidden group-hover:block z-50"
                                            >
                                                <Link
                                                    v-for="child in item.children"
                                                    :key="child.id"
                                                    :href="child.link || '#'"
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600"
                                                >
                                                    {{ child.title }}
                                                </Link>
                                            </div>
                                        </template>

                                        <Link
                                            v-else
                                            :href="item.link || '#'"
                                            class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-500 hover:text-gray-700 border-b-2 border-transparent hover:border-indigo-400 h-full"
                                        >
                                            {{ item.title }}
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <Link
                                    v-if="!$page.props.auth.user"
                                    :href="route('login')"
                                    class="text-sm text-gray-700 underline"
                                    >Войти</Link
                                >
                            </div>
                        </div>
                    </div>
                </nav>

                <div class="header__socials" aria-label="Socials field">
                    <a href="#search" aria-label="To search">
                        <IconSearch
                            class="header__social-img"
                            aria-label="Search image"
                        />
                    </a>

                    <Link href="/cart" aria-label="To cart">
                        <IconBag
                            class="header__social-img"
                            aria-label="Bag image"
                        />
                        <span v-if="$page.props.cart_count > 0">
                            {{ $page.props.cart_count }}
                        </span>
                    </Link>

                    <button
                        class="burger"
                        id="burger"
                        aria-label="To open menu"
                    >
                        <span class="burger__line"></span>
                    </button>
                </div>
            </div>
        </header>
    </div>
</template>
