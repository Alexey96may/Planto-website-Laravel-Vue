import { onMounted, onUnmounted } from 'vue';

interface RevealOptions {
    threshold?: number;
    rootMargin?: string;
    className?: string;
    selector?: string;
}

export function useScrollReveal(options: RevealOptions = {}) {
    const {
        threshold = 0.1,
        rootMargin = '0px 0px -50px 0px',
        className = 'reveal-visible',
        selector = '.reveal',
    } = options;

    let observer: IntersectionObserver | null = null;

    onMounted(() => {
        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        const target = entry.target as HTMLElement;
                        target.classList.add(className);

                        observer?.unobserve(target);
                    }
                });
            },
            { threshold, rootMargin },
        );

        const elements = document.querySelectorAll(selector);

        elements.forEach((el) => observer?.observe(el));
    });

    onUnmounted(() => {
        if (observer) {
            observer.disconnect();
            observer = null;
        }
    });
}
