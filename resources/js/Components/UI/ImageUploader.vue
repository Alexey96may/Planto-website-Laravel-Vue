<script setup lang="ts">
    import { onMounted } from 'vue';

    import { ImagePlus, Loader2, UploadCloud, X } from 'lucide-vue-next';

    import { useImageUpload } from '@/composables/useImageUpload';
    import { useSound } from '@/composables/useSound';

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

    const { playClick } = useSound();

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
    <div class="group w-full">
        <label
            v-if="label"
            class="mb-3 ml-1 block text-[10px] font-black uppercase tracking-[0.2em] transition-colors"
            :class="error ? 'text-red-500' : 'text-zinc-500 group-focus-within:text-[#c5d86d]'"
            for="file-upload"
        >
            {{ label }}
        </label>

        <div
            tabindex="0"
            role="button"
            aria-label="Upload an image"
            :aria-invalid="!!error"
            aria-describedby="file-error"
            @keydown.enter.prevent="triggerFileInput"
            @keydown.space.prevent="triggerFileInput"
            class="group/zone relative flex h-[280px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-[2rem] border-2 border-dashed transition-all duration-500 focus:outline-none focus:ring-2 focus:ring-[#c5d86d]/50"
            :class="[
                isDragging
                    ? 'scale-[0.99] border-[#c5d86d] bg-[#c5d86d]/5'
                    : 'border-white/5 bg-[#0f120e] hover:border-white/20 hover:bg-white/[0.02]',
                error ? 'border-red-500/50 bg-red-500/5' : '',
            ]"
            @dragover.prevent="onDragOver"
            @dragleave.prevent="onDragLeave"
            @drop.prevent="onDrop"
            @click="triggerFileInput"
        >
            <input
                type="file"
                id="file-upload"
                ref="fileInput"
                class="sr-only"
                accept="image/*"
                @change="handleFileChange"
            />

            <div
                v-if="imagePreview"
                class="relative flex h-full w-full flex-col items-center justify-center p-8"
            >
                <div class="group/preview relative flex h-full w-full items-center justify-center">
                    <img
                        :src="imagePreview"
                        alt="Image preview"
                        class="h-full w-full rounded-2xl object-cover shadow-2xl transition-transform duration-500 group-hover/preview:scale-[1.02]"
                    />

                    <div
                        aria-hidden="true"
                        class="absolute inset-0 flex items-center justify-center rounded-2xl bg-black/40 opacity-0 backdrop-blur-[2px] transition-opacity group-hover/preview:opacity-100"
                    >
                        <ImagePlus class="h-8 w-8 text-white opacity-70" />
                    </div>

                    <button
                        @click.stop="removeImage"
                        @mousedown="playClick"
                        type="button"
                        aria-label="Remove image"
                        class="absolute -right-3 -top-3 z-10 rounded-xl bg-red-500 p-2 text-white shadow-xl transition-all duration-300 hover:rotate-90 hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
                <p
                    class="mt-4 text-[10px] font-bold uppercase italic tracking-widest text-zinc-500 transition-colors group-hover/zone:text-[#c5d86d]"
                >
                    Click or press Enter to replace photo
                </p>
            </div>

            <div
                v-else
                class="p-8 text-center transition-transform duration-500 group-hover/zone:scale-110"
            ></div>

            <div
                v-if="isCompressing"
                role="alert"
                aria-live="assertive"
                class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-[#0f120e]/80 backdrop-blur-sm"
            >
                <Loader2 class="mb-4 h-10 w-10 animate-spin text-[#c5d86d]" />
                <span
                    class="animate-pulse text-center text-xs font-black uppercase tracking-[0.3em] text-[#c5d86d]"
                >
                    Optimizing pixels...
                </span>
            </div>
        </div>

        <transition
            enter-active-class="transition duration-200 ease-out"
            enter-from-class="transform -translate-y-2 opacity-0"
            enter-to-class="transform translate-y-0 opacity-100"
        >
            <p
                v-if="error"
                id="file-error"
                class="ml-2 mt-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest text-red-500"
            >
                <X aria-hidden="true" class="h-3 w-3" /> {{ error }}
            </p>
        </transition>
    </div>
</template>
