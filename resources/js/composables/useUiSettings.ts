import { ref, watch } from 'vue';

const isEffectsEnabled = ref(true);

export function useUiSettings() {
    if (typeof window !== 'undefined') {
        const saved = localStorage.getItem('ui_effects');
        if (saved !== null) {
            isEffectsEnabled.value = saved !== 'false';
        }
    }

    watch(isEffectsEnabled, (newVal) => {
        if (typeof window !== 'undefined') {
            localStorage.setItem('ui_effects', newVal.toString());
        }
    });

    return {
        isEffectsEnabled,
    };
}
