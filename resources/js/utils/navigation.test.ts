import { describe, expect, it, vi } from 'vitest';

import { NavigationItem } from '@/types';

import { getHref } from './navigation';

vi.stubGlobal(
    'route',
    vi.fn((name: string, params?: any) => {
        if (name === 'shop') return `/shop/${params.category}`;
        return `/${name}`;
    }),
);

const createNavigationItem = (overrides: Partial<NavigationItem> = {}): NavigationItem => {
    return {
        id: 1,
        title: 'Default Title',
        location: 'header',
        order: 1,
        parent_id: null,
        is_active: 1,
        type: 'link',
        category_id: null,
        link: '/default',
        created_at: '2024-01-01',
        updated_at: '2024-01-01',
        children: null,
        category: null,
        ...overrides,
    };
};

describe('getHref helper', () => {
    it('should return item.link when type is "link"', () => {
        const item: NavigationItem = createNavigationItem({ type: 'link', link: '/about' });
        expect(getHref(item)).toBe('/about');
    });

    it('should return shop route when type is "category"', () => {
        const item = createNavigationItem({
            type: 'category',
            category: { slug: 'indoor-plants' } as any,
        });
        expect(getHref(item)).toBe('/shop/indoor-plants');
    });

    it('should return "#" if link is missing and type is "link"', () => {
        const item: any = { type: 'link', title: 'Empty' };
        expect(getHref(item)).toBe('#');
    });
});
