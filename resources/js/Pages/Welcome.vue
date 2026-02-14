<script setup>
import { Head, Link } from "@inertiajs/vue3";
import IconLogo from "img/icons/logo.svg?component";
import IconArrowMore from "img/icons/arrow-more.svg?component";
import IconSearch from "img/icons/search.svg?component";
import IconBag from "img/icons/bag.svg?component";

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById("screenshot-container")?.classList.add("!hidden");
    document.getElementById("docs-card")?.classList.add("!row-span-1");
    document.getElementById("docs-card-content")?.classList.add("!flex-row");
    document.getElementById("background")?.classList.add("!hidden");
}
</script>

<template>
    <Head title="Welcome" />
    <div>
        <header class="header" id="header" aria-label="Header">
            <div class="container header__container">
                <div class="logo" aria-label="Logo field">
                    <IconLogo class="logo__image" aria-label="Logo image" />
                    <span class="logo__text" aria-label="Logo text"
                        >Planto.</span
                    >
                </div>

                <nav class="nav" id="nav" aria-label="Navigation">
                    <ul class="nav__list" aria-label="Navigation list">
                        <li class="nav__item">
                            <a class="nav__link" href="#" aria-label="To home"
                                >Home</a
                            >
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
                                    <a href="#top">Calathea plant</a>
                                </li>
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant</a>
                                </li>
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant</a>
                                </li>
                                <li class="nav__item--plus-item">
                                    <a href="#top">Calathea plant</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav__item">
                            <a
                                class="nav__link"
                                href="#best"
                                aria-label="To see more"
                                >More</a
                            >
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
                </nav>

                <div class="header__socials" aria-label="Socials field">
                    <a href="#search" aria-label="To search">
                        <IconSearch
                            class="header__social-img"
                            aria-label="Search image"
                        />
                    </a>

                    <a href="#cart" aria-label="To cart">
                        <IconBag
                            class="header__social-img"
                            aria-label="Bag image"
                        />
                    </a>

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
