import { DefineComponent, createSSRApp, h } from 'vue';

import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';

import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

import globalComponents from '@/plugins/global-components';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - Planto Shop`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
            ),
        setup({ App, props, plugin }) {
            const ziggyConfig = (props.initialPage.props as any).ziggy;

            return createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(globalComponents)
                .use(ZiggyVue, {
                    ...(ziggyConfig || {}),
                    location: new URL(
                        ziggyConfig?.location ||
                            (props.initialPage.props as any).url ||
                            'http://localhost',
                    ),
                });
        },
    }),
);
