import { DefineComponent, createApp, h } from 'vue';
import { onMounted } from 'vue';

import { createInertiaApp } from '@inertiajs/vue3';

import { Howler } from 'howler';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

import globalComponents from '@/plugins/global-components';

import '../css/app.css';
import './bootstrap';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

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

onMounted(() => {
    const resumeAudio = async (): Promise<void> => {
        if (Howler.ctx && Howler.ctx.state === 'suspended') {
            Howler.ctx.resume().then(() => {
                console.log('AudioContext resumed successfully 🔊');
                window.removeEventListener('click', resumeAudio);
                window.removeEventListener('keydown', resumeAudio);
            });
        }
    };

    window.addEventListener('click', resumeAudio);
    window.addEventListener('keydown', resumeAudio);
});
