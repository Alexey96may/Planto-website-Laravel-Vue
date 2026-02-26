<script setup>
import { Link } from "@inertiajs/vue3";
import AppImage from "@/Components/AppImage.vue";
import IconBag from "img/icons/bag.svg?component";
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    plant: {
        type: Object,
        default: () => {},
    },
});

const page = usePage();

const addToCart = () => {
    const id = props.plant?.id;

    router.post(route("cart.add"), {
        product_id: id,
        quantity: 1,
    });
};

const isInCart = computed(() => {
    return page.props.cart_ids?.includes(props.plant.id);
});
</script>

<template>
    <figure class="card trendy-card" aria-label="Trendy card">
        <div class="card__img-wrapper" aria-label="Trendy card image">
            <AppImage :src="plant?.image_url" :alt="plant.title" />
        </div>
        <div class="card__info" aria-label="Card information">
            <h3
                class="card__title card__title--white"
                aria-label="Slider card title"
            >
                <Link
                    class="card__link"
                    :href="route('shop.show', plant.id)"
                    :aria-label="plant.title"
                >
                    {{ plant.title }}
                </Link>
            </h3>

            <p class="card__descr card__descr--white">
                {{ plant.description }}
            </p>

            <div class="card__price" aria-label="Plant price">
                {{ plant.price }} $
            </div>

            <div class="card__buttons" aria-label="Buttons">
                <button class="button--rect" aria-label="Explore">
                    <Link
                        class="card__link"
                        :href="route('shop.show', plant.id)"
                        :aria-label="plant.title"
                    >
                        Explore
                    </Link>
                </button>

                <button
                    @click="addToCart"
                    class="button--square"
                    aria-label="Add to cart"
                    :disabled="plant.stock === 0 || isInCart"
                >
                    <IconBag class="button-image" aria-label="Cart"></IconBag>
                </button>
            </div>
        </div>
    </figure>
</template>
