import { onBeforeUnmount, ref, watch } from 'vue';

import { usePage } from '@inertiajs/vue3';

import { FlashPayload, SharedData } from '@/types';

type NotificationType = 'success' | 'error' | 'warning' | 'info';

export function useFlash() {
    const page = usePage<SharedData>();

    const show = ref(false);
    const message = ref('');
    const type = ref<NotificationType>('info');
    let timeoutId: ReturnType<typeof setTimeout> | null = null;

    const clearExistingTimer = () => {
        if (timeoutId) {
            clearTimeout(timeoutId);
            timeoutId = null;
        }
    };

    const notify = (msg: string, t: NotificationType = 'info') => {
        clearExistingTimer();
        message.value = msg;
        type.value = t;
        show.value = true;

        timeoutId = setTimeout(() => {
            show.value = false;
        }, 4000);
    };

    watch(
        () => page.props.flash as FlashPayload,
        (flash) => {
            if (!flash) return;

            const flashMessage = flash.success || flash.error || flash.message || flash.warning;

            if (flashMessage) {
                const flashType = flash.success
                    ? 'success'
                    : flash.error
                      ? 'error'
                      : flash.warning
                        ? 'warning'
                        : 'info';

                notify(flashMessage, flashType);
            }
        },
        { deep: true, immediate: true },
    );

    onBeforeUnmount(clearExistingTimer);

    return {
        show,
        message,
        type,
        notify,
    };
}
