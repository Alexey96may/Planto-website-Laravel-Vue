<script setup lang="ts">
    import { computed } from 'vue';

    import { Link, usePage } from '@inertiajs/vue3';

    import IconArrowMore from 'img/icons/arrow-more.svg?component';
    import { SharedData } from '@/types';
    import { getHref } from '@/utils/navigation';

    const page = usePage<SharedData>();
    const menuItems = computed(() => page.props.navigation?.header || []);
</script>

<template>
    <nav class="nav" id="nav" aria-label="Navigation">
        <ul class="nav__list flex items-center gap-4 xl:gap-8 transition-all" aria-label="Navigation list">
            <li class="nav__item nav__item--plus" v-for="item in menuItems" :key="item.id">
                <template v-if="item.children && item.children.length > 0">
                    <span aria-label="To plants type"
                        >{{ item.title }}

                        <IconArrowMore
                            class="nav__item-more"
                            id="moreArrow"
                            aria-label="Arrow sprite"
                        />
                    </span>

                    <ul class="nav__item--plus-list">
                        <li
                            class="nav__item--plus-item"
                            v-for="child in item.children"
                            :key="child.id"
                            :href="getHref(item) || '#'"
                        >
                            <Link :href="getHref(item) || '#'">
                                {{ child.title }}
                            </Link>
                        </li>
                    </ul>
                </template>
                <li class="nav__item" v-else>
                    <Link :href="getHref(item) || '#'" class="nav__link" aria-label="To see more plants">
                        {{ item.title }}
                    </Link>
                </li>
            </li>
        </ul>
    </nav>

</template>
