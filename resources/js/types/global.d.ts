import { route as routziggyRouteeFn } from 'ziggy-js';

import FlashMessage from '@/Components/Shared/Toast.vue';

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
    }
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
