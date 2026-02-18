<script setup>
import { Link } from "@inertiajs/vue3";
import IconBag from "img/icons/bag.svg?component";
</script>

<template>
    <section class="trendy" id="trendy" aria-label="Trendy plants section">
        <div class="container trendy__container">
            <h2 class="trendy__title">Our Trendy plants</h2>
            <div class="trendy__cards">
                <figure class="card trendy-card" aria-label="Trendy card">
                    <div
                        class="card__img-wrapper"
                        aria-label="Trendy card image"
                    >
                        <img src="@/../images/plants/Plant1.png" alt="Plant" />
                    </div>

                    <div class="card__info" aria-label="Card information">
                        <h3
                            class="card__title card__title--white"
                            aria-label="Slider card title"
                        >
                            <a
                                href="#"
                                class="card__link"
                                aria-label="For Small Decs Ai Plat"
                                >For Small Decs Ai Plat</a
                            >
                        </h3>

                        <p class="card__descr card__descr--white">
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua
                        </p>

                        <div class="card__price" aria-label="Plant price">
                            Rs. 599/-
                        </div>

                        <div class="card__buttons" aria-label="Buttons">
                            <button class="button--rect" aria-label="Explore">
                                <Link
                                    href="/shop"
                                    aria-label="To see more plants"
                                >
                                    Explore
                                </Link>
                            </button>

                            <button
                                class="button--square"
                                aria-label="Add to cart"
                            >
                                <IconBag
                                    class="button-image"
                                    aria-label="Cart"
                                ></IconBag>
                            </button>
                        </div>
                    </div>
                </figure>

                <figure class="card trendy-card" aria-label="Trendy card">
                    <div
                        class="card__img-wrapper"
                        aria-label="Trendy card image"
                    >
                        <img src="@/../images/plants/Plant2.png" alt="Plant" />
                    </div>

                    <div class="card__info" aria-label="Card information">
                        <h3
                            class="card__title card__title--white"
                            aria-label="Slider card title"
                        >
                            <a
                                href="#"
                                class="card__link"
                                aria-label="For Fresh Decs Ai Plat"
                                >For Fresh Decs Ai Plat</a
                            >
                        </h3>

                        <p class="card__descr card__descr--white">
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua
                        </p>

                        <div class="card__price" aria-label="Plant price">
                            Rs. 579/-
                        </div>

                        <div class="card__buttons" aria-label="Buttons">
                            <button class="button--rect" aria-label="Explore">
                                <Link
                                    href="/shop"
                                    aria-label="To see more plants"
                                >
                                    Explore
                                </Link>
                            </button>

                            <button
                                class="button--square"
                                aria-label="Add to cart"
                            >
                                <IconBag
                                    class="button-image"
                                    aria-label="Cart"
                                ></IconBag>
                            </button>
                        </div>
                    </div>
                </figure>
            </div>
        </div>
    </section>
</template>
