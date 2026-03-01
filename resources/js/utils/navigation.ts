import { NavigationItem } from '@/types';

export const getHref = (item: NavigationItem): string => {
    if (item.type === 'category' && item.category?.slug) {
        return typeof route !== 'undefined'
            ? route('shop', { category: item.category.slug })
            : `/shop/${item.category.slug}`;
    }
    return item.link ?? '#';
};
