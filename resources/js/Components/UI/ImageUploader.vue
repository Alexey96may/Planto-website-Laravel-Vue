<script setup lang="ts">
    import { onMounted } from 'vue';

    import { ImageIcon, ImagePlus, Loader2, UploadCloud, X } from 'lucide-vue-next';

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
    <div class="group w-full">
        <label
            v-if="label"
            class="mb-3 ml-1 block text-[10px] font-black uppercase tracking-[0.2em] transition-colors"
            :class="error ? 'text-red-500' : 'text-zinc-500 group-focus-within:text-[#c5d86d]'"
        >
            {{ label }}
        </label>

        <div
            class="group/zone relative flex min-h-[220px] cursor-pointer flex-col items-center justify-center overflow-hidden rounded-[2rem] border-2 border-dashed transition-all duration-500"
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
                ref="fileInput"
                class="hidden"
                accept="image/*"
                @change="handleFileChange"
            />

            <div v-if="imagePreview" class="relative flex h-full w-full flex-col items-center p-4">
                <div class="group/preview relative">
                    <img
                        :src="imagePreview"
                        class="max-h-48 w-auto rounded-2xl object-cover shadow-2xl transition-transform duration-500 group-hover/preview:scale-[1.02]"
                    />

                    <div
                        class="absolute inset-0 flex items-center justify-center rounded-2xl bg-black/40 opacity-0 backdrop-blur-[2px] transition-opacity group-hover/preview:opacity-100"
                    >
                        <ImagePlus class="h-8 w-8 text-white opacity-70" />
                    </div>

                    <button
                        @click.stop="removeImage"
                        type="button"
                        class="absolute -right-3 -top-3 z-10 rounded-xl bg-red-500 p-2 text-white shadow-xl transition-all duration-300 hover:rotate-90 hover:bg-red-600"
                    >
                        <X class="h-4 w-4" />
                    </button>
                </div>
                <p
                    class="mt-4 text-[10px] font-bold uppercase italic tracking-widest text-zinc-500 transition-colors group-hover/zone:text-[#c5d86d]"
                >
                    Click to replace photo
                </p>
            </div>

            <div
                v-else
                class="p-8 text-center transition-transform duration-500 group-hover/zone:scale-110"
            >
                <div
                    class="relative mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl border border-white/5 bg-white/5 transition-all group-hover/zone:border-[#c5d86d]/30 group-hover/zone:bg-[#c5d86d]/5"
                >
                    <UploadCloud
                        class="h-8 w-8 transition-colors"
                        :class="
                            error ? 'text-red-400' : 'text-zinc-600 group-hover/zone:text-[#c5d86d]'
                        "
                    />

                    <div
                        v-if="isCompressing"
                        class="absolute -bottom-1 -right-1 rounded-lg bg-[#c5d86d] p-1"
                    >
                        <Loader2 class="h-3 w-3 animate-spin text-[#0f120e]" />
                    </div>
                </div>

                <div class="space-y-1">
                    <p class="text-sm font-bold uppercase italic tracking-tight text-white">
                        Drop your leaf here
                    </p>
                    <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500">
                        PNG, JPG, JPEG
                    </p>
                </div>
            </div>

            <div
                v-if="isCompressing"
                class="absolute inset-0 z-20 flex flex-col items-center justify-center bg-[#0f120e]/80 backdrop-blur-sm"
            >
                <Loader2 class="mb-4 h-10 w-10 animate-spin text-[#c5d86d]" />
                <span
                    class="animate-pulse text-xs font-black uppercase tracking-[0.3em] text-[#c5d86d]"
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
                class="ml-2 mt-3 flex items-center gap-1 text-[10px] font-bold uppercase tracking-widest text-red-500"
            >
                <X class="h-3 w-3" /> {{ error }}
            </p>
        </transition>
    </div>
</template>
