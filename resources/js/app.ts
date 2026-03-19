import { DefineComponent, createApp, h, onMounted } from 'vue';

import { createInertiaApp, router } from '@inertiajs/vue3';

import { Howler } from 'howler';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

import globalComponents from '@/plugins/global-components';

import '../css/app.css';
import './bootstrap';

const resumeAudio = async (): Promise<void> => {
    if (typeof window !== 'undefined' && Howler.ctx && Howler.ctx.state === 'suspended') {
        await Howler.ctx.resume();
        window.removeEventListener('click', resumeAudio);
        window.removeEventListener('keydown', resumeAudio);
    }
};

if (typeof window !== 'undefined') {
    window.addEventListener('click', resumeAudio);
    window.addEventListener('keydown', resumeAudio);
}

createInertiaApp({
    title: (title) => (title ? `${title} — Planto` : 'Planto'),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue as any)
            .use(globalComponents)
            .mount(el);
    },
    progress: {
        color: '#22c55e',
        includeCSS: true,
        showSpinner: false,
    },
});
