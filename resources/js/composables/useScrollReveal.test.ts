import { defineComponent, nextTick } from 'vue';

import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it, vi } from 'vitest';

import { useScrollReveal } from './useScrollReveal';

const observeSpy = vi.fn();
const disconnectSpy = vi.fn();
let observerCallback: any;

vi.stubGlobal(
    'IntersectionObserver',
    vi.fn(function (cb) {
        observerCallback = cb;
        return {
            observe: observeSpy,
            disconnect: disconnectSpy,
            unobserve: vi.fn(),
        };
    }),
);

const TestComponent = defineComponent({
    setup() {
        useScrollReveal({ selector: '.test-item' });
        return {};
    },
    template: '<div class="test-item"></div>',
});

describe('useScrollReveal', () => {
    beforeEach(() => {
        vi.clearAllMocks();
        document.body.innerHTML = '';
    });

    it('should start observing elements on mount', async () => {
        mount(TestComponent, { attachTo: document.body });

        await new Promise((resolve) => setTimeout(resolve, 0));

        expect(observeSpy).toHaveBeenCalled();
    });

    it('should add class when element is intersecting', async () => {
        const wrapper = mount(TestComponent, { attachTo: document.body });
        const element = document.querySelector('.test-item') as HTMLElement;

        observerCallback([{ isIntersecting: true, target: element }]);

        await new Promise((resolve) => setTimeout(resolve, 0));
        expect(element.classList.contains('reveal-visible')).toBe(true);
    });
});
