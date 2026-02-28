<script setup lang="ts">
    import { onMounted } from 'vue';

    import { useImageUpload } from '@/composables/useImageUpload';

    const props = defineProps<{
        modelValue: File | null;
        label?: string;
        existingImage?: string | null;
        error?: string;
    }>();

    const emit = defineEmits<{
        (e: 'update:modelValue', value: File | null): void;
        (e: 'on-file-select', file: File | null): void;
    }>();

    const {
        isDragging,
        imagePreview,
        isCompressing,
        fileInput,
        onDrop,
        onDragOver,
        onDragLeave,
        triggerFileInput,
        handleFileChange,
        clearImage,
    } = useImageUpload((file) => {
        emit('on-file-select', file);
        emit('update:modelValue', file);
    });

    defineExpose({ clearImage });

    const removeImage = () => {
        clearImage();
        emit('on-file-select', null);
        emit('update:modelValue', null);
    };

    onMounted(() => {
        if (props.existingImage) {
            imagePreview.value = props.existingImage;
        }
    });
</script>

<template>
    <div class="w-full">
        <label
            v-if="label"
            class="block text-sm font-medium text-gray-700 mb-1"
            :class="error ? 'text-red-600' : 'text-gray-700'"
            >{{ label }}</label
        >

        <div
            class="relative border-2 border-dashed rounded-lg p-4 transition-all duration-200 flex flex-col items-center justify-center min-h-[150px] cursor-pointer"
            :class="[
                isDragging ? 'border-indigo-500 bg-indigo-50' : 'bg-gray-50',
                error ? 'border-red-500 bg-red-50' : 'border-gray-300 hover:border-indigo-400',
            ]"
            @dragover.prevent="onDragOver"
            @dragleave.prevent="onDragLeave"
            @drop.prevent="onDrop"
            @click="triggerFileInput"
        >
            <input
                type="file"
                ref="fileInput"
                class="hidden"
                accept="image/*"
                @change="handleFileChange"
            />

            <div v-if="imagePreview" class="relative">
                <img :src="imagePreview" class="max-h-40 rounded-lg shadow-sm" />
                <button
                    @click.stop="removeImage"
                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow-lg hover:bg-red-600"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <div v-else-if="existingImage" class="relative text-center">
                <img :src="existingImage" class="max-h-40 rounded-lg opacity-80" />
                <p class="text-xs text-gray-500 mt-2">Current photo (click to change)</p>
            </div>

            <div v-else class="text-center">
                <svg
                    :class="error ? 'text-red-400' : 'text-gray-400'"
                    class="mx-auto h-10 w-10 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                    ></path>
                </svg>
                <p class="mt-1 text-sm text-gray-600 font-medium">Click or drag</p>
                <p class="text-xs text-gray-400">PNG, JPG, GIF</p>
                <span
                    v-show="isCompressing"
                    class="text-sm text-green-600 font-medium animate-bounce"
                    >Photo optimizing...</span
                >
            </div>
        </div>

        <p v-if="error" class="mt-1 text-xs text-red-600 italic">
            {{ error }}
        </p>
    </div>
</template>
