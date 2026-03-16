import { ref, watch } from 'vue';

const isEffectsEnabled = ref(localStorage.getItem('ui_effects') !== 'false');

export function useUiSettings() {
    watch(isEffectsEnabled, (newVal) => {
        localStorage.setItem('ui_effects', newVal.toString());
    });

    return {
        isEffectsEnabled,
    };
}
