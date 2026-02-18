<script setup>
import { Link } from "@inertiajs/vue3";
import IconArrowRight from "img/icons/arrow-right.svg?component";
</script>

<template>
    <section class="best" id="best" aria-label="Review section">
        <div class="container best__container">
            <h2 class="title best__title">Our Best o2</h2>

            <div class="slider review__slider" aria-label="Slider">
                <div class="slider__cards" aria-label="Slider cards">
                    <figure class="card slider__card" aria-label="Slider card">
                        <div
                            class="card__img-wrapper"
                            aria-label="Slider card image"
                        >
                            <img
                                src="@/../images/plants/Plant7.png"
                                alt="Plant"
                            />
                        </div>

                        <div class="card__info" aria-label="Card information">
                            <h3
                                class="card__title"
                                aria-label="Slider card title"
                            >
                                <a
                                    href="#"
                                    class="card__tag-title"
                                    aria-label="To Calathea plant"
                                    >We Have Small And Best O2 Plants
                                    Collection’s</a
                                >
                            </h3>

                            <div
                                class="card__descr-wrapper"
                                aria-label="Card description area"
                            >
                                <p class="card__descr">
                                    Lorem ipsum dolor sit amet, consectetur
                                    adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua
                                </p>

                                <p class="card__descr">
                                    Lorem ipsum dolor sit amet, consectetur
                                    adipiscing elit, sed do eiusmod tempor
                                    incididunt ut labore et dolore magna aliqua
                                </p>
                            </div>

                            <div
                                class="card__buying"
                                aria-label="Plant buying info"
                            >
                                <button
                                    class="button--rect card__button"
                                    aria-label="Explore"
                                >
                                    <Link
                                        href="/shop"
                                        aria-label="To see more plants"
                                    >
                                        Explore
                                    </Link>
                                </button>

                                <div
                                    class="card__number"
                                    aria-label="Plant card number"
                                >
                                    <button
                                        class="button slider-mini__left"
                                        aria-label="Left"
                                    >
                                        <IconArrowRight
                                            class="slider-mini__left-img"
                                            aria-label="Arrow icon"
                                        ></IconArrowRight>
                                    </button>

                                    <span class="card__number-info">
                                        <span class="card__number-prev">01</span
                                        >/<span class="card__number-next"
                                            >04</span
                                        >
                                    </span>

                                    <button
                                        class="button slider-mini__right"
                                        aria-label="Right"
                                    >
                                        <IconArrowRight
                                            class="slider-mini__right-img"
                                            aria-label="Arrow icon"
                                        ></IconArrowRight>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </figure>
                </div>

                <div
                    class="slider__bottom-buttons"
                    aria-label="Slider bottom buttons"
                >
                    <button
                        class="bottom-button bottom-button--active"
                        aria-label="To active slide"
                    ></button>
                    <button
                        class="bottom-button"
                        aria-label="To second slide"
                    ></button>
                    <button
                        class="bottom-button"
                        aria-label="To third slide"
                    ></button>
                </div>
            </div>
        </div>
    </section>
</template>
