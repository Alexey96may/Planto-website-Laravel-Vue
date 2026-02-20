<script setup>
import IconStar from "img/icons/star.svg?component";
import IconStarHalf from "img/icons/star-half.svg?component";
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <section class="review" id="review" aria-label="Review section">
        <div class="container review__container">
            <h2 class="title review__title">
                <Link :href="route('reviews.index')">Customer Review</Link>
            </h2>

            <div class="review__cards" aria-label="Cards field with Review">
                <figure class="comment top__comment" aria-label="Review">
                    <div
                        class="comment__author author"
                        aria-label="Comment author"
                    >
                        <div class="author__photo" aria-label="Author`s photo">
                            <img
                                src="@/../images/users/user3.png"
                                alt="Author image"
                            />
                        </div>

                        <div class="author__info" aria-label="Author`s info">
                            <p class="author__name" aria-label="Author`s name">
                                Maxn Raval
                            </p>

                            <div
                                class="authors__assessment"
                                aria-label="Author`s assessment"
                            >
                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStarHalf
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStarHalf>
                            </div>
                        </div>
                    </div>

                    <div class="comment__message" aria-label="Author`s message">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam,
                        </p>
                    </div>
                </figure>

                <figure class="comment top__comment" aria-label="Review">
                    <div
                        class="comment__author author"
                        aria-label="Comment author"
                    >
                        <div class="author__photo" aria-label="Author`s photo">
                            <img
                                src="@/../images/users/user5.png"
                                alt="Author image"
                            />
                        </div>

                        <div class="author__info" aria-label="Author`s info">
                            <p class="author__name" aria-label="Author`s name">
                                venely k
                            </p>

                            <div
                                class="authors__assessment"
                                aria-label="Author`s assessment"
                            >
                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStarHalf
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStarHalf>
                            </div>
                        </div>
                    </div>

                    <div class="comment__message" aria-label="Author`s message">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam,
                        </p>
                    </div>
                </figure>

                <figure class="comment top__comment" aria-label="Review">
                    <div
                        class="comment__author author"
                        aria-label="Comment author"
                    >
                        <div class="author__photo" aria-label="Author`s photo">
                            <img
                                src="@/../images/users/user6.png"
                                alt="Author image"
                            />
                        </div>

                        <div class="author__info" aria-label="Author`s info">
                            <p class="author__name" aria-label="Author`s name">
                                Lii thakur
                            </p>

                            <div
                                class="authors__assessment"
                                aria-label="Author`s assessment"
                            >
                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStar
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStar>

                                <IconStarHalf
                                    class="authors__star"
                                    aria-label="Star icon"
                                ></IconStarHalf>
                            </div>
                        </div>
                    </div>

                    <div class="comment__message" aria-label="Author`s message">
                        <p>
                            Lorem ipsum dolor sit amet, consectetur adipiscing
                            elit, sed do eiusmod tempor incididunt ut labore et
                            dolore magna aliqua. Ut enim ad minim veniam,
                        </p>
                    </div>
                </figure>
            </div>
        </div>
    </section>
</template>
