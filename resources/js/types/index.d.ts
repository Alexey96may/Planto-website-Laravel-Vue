import { PageProps } from '@inertiajs/core';

import type { AuthProps, Navigation, Settings, User } from '@/types';

export interface SharedData extends PageProps {
    auth: AuthProps;
    cart_count: number;
    cart_ids: number[];
    settings: Settings;
    navigation: Navigation;
    flash: {
        success: string | null;
        error: string | null;
        message: string | null;
    };
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        $page: {
            props: SharedData;
        };
    }
}
