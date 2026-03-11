import { ref, watch } from 'vue';

import { usePage } from '@inertiajs/vue3';

import { FlashPayload, SharedData } from '@/types';

type NotificationType = 'success' | 'error' | 'warning' | 'info';

const show = ref(false);
const message = ref('');
const type = ref<NotificationType>('info');
const isCountingDown = ref(false);
const undoRequested = ref(false);
const timerDuration = ref(5000);

const lastFlashId = ref<string | null>(null);
let timeoutId: ReturnType<typeof setTimeout> | null = null;

export function useFlash() {
    const page = usePage<SharedData>();

    const clearExistingTimer = () => {
        if (timeoutId) {
            clearTimeout(timeoutId);
            timeoutId = null;
        }
    };

    const notify = (msg: string, t: NotificationType = 'info') => {
        clearExistingTimer();

        lastFlashId.value = null;
        isCountingDown.value = false;
        undoRequested.value = false;
        message.value = msg;
        type.value = t;
        show.value = true;

        timeoutId = setTimeout(() => {
            show.value = false;
        }, 4000);
    };

    const notifyWithUndo = (msg: string, duration: number = 5000): Promise<boolean> => {
        clearExistingTimer();

        lastFlashId.value = null;

        message.value = msg;
        type.value = 'warning';
        show.value = true;
        isCountingDown.value = true;
        undoRequested.value = false;
        timerDuration.value = duration;

        return new Promise((resolve) => {
            const start = Date.now();
            const interval = setInterval(() => {
                if (undoRequested.value) {
                    clearInterval(interval);
                    show.value = false;
                    isCountingDown.value = false;
                    lastFlashId.value = null;
                    resolve(false);
                } else if (Date.now() - start >= duration) {
                    clearInterval(interval);
                    show.value = false;
                    isCountingDown.value = false;
                    resolve(true);
                }
            }, 50);
        });
    };

    watch(
        () => page.props.flash as FlashPayload,
        (flash) => {
            if (!flash) return;
            const flashMsg = flash.success || flash.error || flash.message || flash.warning;

            if (flashMsg) {
                const currentId = JSON.stringify(flash);
                if (currentId === lastFlashId.value) return;

                lastFlashId.value = currentId;

                const flashType = flash.success
                    ? 'success'
                    : flash.error
                      ? 'error'
                      : flash.warning
                        ? 'warning'
                        : 'info';
                notify(flashMsg, flashType);

                page.props.flash.success = null;
                page.props.flash.error = null;
                page.props.flash.message = null;
                page.props.flash.warning = null;
            }
        },
        { deep: true, immediate: true },
    );

    return {
        show,
        message,
        type,
        isCountingDown,
        undoRequested,
        timerDuration,
        notify,
        notifyWithUndo,
    };
}
