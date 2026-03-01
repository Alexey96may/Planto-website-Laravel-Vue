<script setup>
    import { computed } from 'vue';

    import { Link } from '@inertiajs/vue3';
    import { router } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3';

    import IconBag from 'img/icons/bag.svg?component';

    import AppImage from '@/Components/UI/AppImage.vue';

    const props = defineProps({
        plant: {
            type: Object,
            default: () => {},
        },
        current_page: {
            type: String,
            default: '',
        },
    });

    const page = usePage();

    const addToCart = () => {
        const id = props.plant?.id;

        router.post(route('cart.add'), {
            product_id: id,
            quantity: 1,
        });
    };

    const isInCart = computed(() => {
        return page.props.cart_ids?.includes(props.plant.id);
    });
</script>

<template>
    <figure class="card top__card" aria-label="Top card">
        <div class="card__img-wrapper" aria-label="Top card image">
            <AppImage :src="plant.image_url" :alt="plant.title"></AppImage>
        </div>

        <h3 class="card__title" aria-label="Card title">
            <Link
                :href="'/shop/plant-' + plant.id"
                :data="{ page: current_page }"
                class="card__tag-title"
                aria-label="To {{ plant.title }}"
            >
                {{ plant.title }}
            </Link>
        </h3>

        <p class="card__descr card__descr--white" aria-label="Plant description">
            {{ plant.description }}
        </p>

        <div class="card__buying" aria-label="Plant buying info">
            <div class="card__price" aria-label="Plant price">{{ plant.price }} $</div>

            <button
                class="button--square"
                aria-label="Add to cart"
                @click="addToCart"
                :disabled="isInCart || plant.stock === 0"
            >
                <IconBag class="button-image" aria-label="Cart"></IconBag>
            </button>
        </div>
    </figure>
</template>
