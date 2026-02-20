<script setup>
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import IconStar from "img/icons/star.svg?component";
import IconStarHalf from "img/icons/star-half.svg?component";
import IconPlay from "img/icons/play.svg?component";
import IconArrowRight from "img/icons/arrow-right.svg?component";
import { computed } from "vue";

const comment = computed(() => usePage().props.comments[0]) || null;

const starNumber = computed(() => {
    const val = Math.floor(Number(comment.value?.rating) || 0);
    return Math.max(0, val);
});

const isHalfStar = computed(() => {
    const val = Number(comment.value?.rating) || 0;
    return val % 1 !== 0;
});
</script>

<template>
    <section class="hero" id="hero" aria-label="Hero section">
        <div class="container hero__container">
            <div class="hero__info" aria-label="Hero information">
                <div class="hero__info-main" aria-label="Hero main information">
                    <h2 class="hero__title">Breath Natureal</h2>

                    <p class="hero__descr">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit,
                        sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua.
                    </p>

                    <div class="hero__buttons" aria-label="Hero buttons">
                        <button
                            class="button--rect"
                            aria-label="Press to explore"
                            id="heroExploreButton"
                        >
                            <Link href="/shop" aria-label="To see more plants">
                                Explore
                            </Link>
                        </button>

                        <div
                            class="hero__video-prevue"
                            aria-label="Hero video field"
                            title="Live Demonstration"
                        >
                            <button
                                class="button--circ"
                                aria-label="Play video"
                            >
                                <IconPlay
                                    class="hero__video--img"
                                    aria-label="Play icon"
                                ></IconPlay>
                            </button>

                            <span
                                class="hero__video-descr"
                                aria-label="Video description"
                                >Live Demonstration</span
                            >
                        </div>
                    </div>
                </div>

                <figure
                    class="comment hero__comment"
                    aria-label="Main comment"
                    v-if="comment"
                >
                    <div
                        class="comment__author author"
                        aria-label="Comment author"
                    >
                        <div class="author__photo" aria-label="Author`s photo">
                            <img
                                :src="
                                    comment?.user?.avatar_url ||
                                    defaultAvatarLink
                                "
                                alt="Author photo"
                            />
                        </div>

                        <div class="author__info" aria-label="Author`s info">
                            <p class="author__name" aria-label="Author`s name">
                                {{ comment?.user?.name }}
                            </p>

                            <div
                                class="authors__assessment"
                                aria-label="Author`s assessment"
                            >
                                <IconStar
                                    v-for="n in starNumber"
                                    :key="'full-' + n"
                                    class="authors__star"
                                    aria-label="Full star"
                                ></IconStar>

                                <IconStarHalf
                                    v-if="isHalfStar"
                                    class="authors__star"
                                    aria-label="Half star"
                                ></IconStarHalf>
                            </div>
                        </div>
                    </div>

                    <div class="comment__message" aria-label="Author`s message">
                        <p>
                            {{ comment?.body }}
                        </p>
                    </div>
                </figure>
            </div>

            <div class="slider-mini hero__slider-mini" aria-label="Slider">
                <div class="slider-mini__cards" aria-label="Slider cards">
                    <figure
                        class="card slider-mini__card"
                        aria-label="Slider card"
                    >
                        <div
                            class="card__img-wrapper"
                            aria-label="Slider card image"
                        >
                            <img
                                src="@/../images/plants/Plant7.png"
                                alt="Plant"
                            />
                        </div>

                        <span class="card__tag" aria-label="Slider card tag"
                            ><a
                                href="#"
                                class="card__tag-link"
                                aria-label="To trendy house plant"
                                >Trendy House Plant</a
                            ></span
                        >
                        <h3 class="card__title" aria-label="Slider card title">
                            <a
                                href="#"
                                class="card__tag-title"
                                aria-label="To Calathea plant"
                                >Calathea plant</a
                            >
                        </h3>

                        <button
                            class="button--rect card__button"
                            aria-label="Buy now"
                        >
                            Buy Now
                        </button>
                    </figure>
                </div>

                <div
                    class="slider-mini__dir-buttons"
                    aria-label="Slider direction buttons"
                >
                    <button class="button slider-mini__left" aria-label="Left">
                        <IconArrowRight
                            class="slider-mini__left-img"
                            aria-label="Arrow icon"
                        ></IconArrowRight>
                    </button>

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

                <div
                    class="slider-mini__bottom-buttons"
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
