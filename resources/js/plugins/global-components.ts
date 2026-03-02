import { App } from 'vue';

import FlashMessage from '@/Components/Shared/Toast.vue';

const components = {
    FlashMessage,
};

export default {
    install(app: App) {
        Object.entries(components).forEach(([name, component]) => {
            app.component(name, component);
        });
    },
};
