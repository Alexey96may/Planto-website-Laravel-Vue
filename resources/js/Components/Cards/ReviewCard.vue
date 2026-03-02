<script setup>
import IconStar from "img/icons/star.svg?component";
import IconStarHalf from "img/icons/star-half.svg?component";
import { computed } from "vue";

const props = defineProps({
    comment: Object,
});

const starNumber = computed(() => {
    const val = Math.floor(Number(props.comment.rating) || 0);
    return Math.max(0, val);
});

const isHalfStar = computed(() => {
    const val = Number(props.comment.rating) || 0;
    return val % 1 !== 0;
});
</script>

<template>
    <figure class="comment top__comment" aria-label="Review">
        <div class="comment__author author" aria-label="Comment author">
            <div class="author__photo" aria-label="Author`s photo">
                <img
                    :src="comment.user.avatar_url || defaultAvatarLink"
                    alt="Author photo"
                />
            </div>

            <div class="author__info" aria-label="Author`s info">
                <p class="author__name" aria-label="Author`s name">
                    {{ comment.user.name }}
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
                {{ comment.body }}
            </p>
        </div>
    </figure>
</template>
