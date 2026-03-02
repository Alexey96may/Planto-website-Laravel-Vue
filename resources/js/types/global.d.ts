import { route as routeFn } from 'ziggy-js';

import FlashMessage from '@/Components/Shared/Toast.vue';

declare global {
    var route: typeof routeFn;
}

declare module '*.svg?component' {
    import { DefineComponent } from 'vue';
    const component: DefineComponent<{}, {}, any>;
    export default component;
}

declare module '@vue/runtime-core' {
    export interface GlobalComponents {
        FlashMessage: typeof FlashMessage;
    }
}
