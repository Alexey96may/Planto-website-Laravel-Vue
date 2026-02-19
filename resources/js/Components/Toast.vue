<script setup>
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();
const show = ref(false);
const message = ref("");
const type = ref("success");

// Следим за изменениями пропсов flash в Inertia
watch(
    () => page.props.flash,
    (flash) => {
        const flashMessage = flash?.success || flash?.error || flash?.message;
        if (flashMessage) {
            message.value = flashMessage;
            type.value = flash.success ? "success" : "error";
            show.value = true;

            // Скрыть через 3 секунды
            setTimeout(() => {
                show.value = false;
            }, 4000);
        }
    },
    { deep: true, immediate: true },
);
</script>

<template>
    <Teleport to="body">
        <div
            v-if="show"
            class="fixed top-5 right-5 z-[100] p-4 rounded shadow-lg text-white min-w-[250px]"
            :class="type === 'success' ? 'bg-green-500' : 'bg-red-500'"
        >
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <span class="mr-2">{{
                        type === "success" ? "✅" : "❌"
                    }}</span>
                    <span class="font-medium">{{ message }}</span>
                </div>
                <button
                    @click="show = false"
                    class="ml-4 text-white opacity-70 hover:opacity-100"
                >
                    &times;
                </button>
            </div>
        </div>
    </Teleport>
</template>
