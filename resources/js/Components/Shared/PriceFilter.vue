<script setup lang="ts">
    const minPrice = defineModel<number | string>('min');
    const maxPrice = defineModel<number | string>('max');

    const emit = defineEmits<{
        (e: 'change-price'): void;
    }>();

    const handleChange = () => {
        emit('change-price');
    };
</script>

<template>
    <div class="space-y-4 text-zinc-900">
        <h3 class="text-xs uppercase tracking-widest text-zinc-500 font-bold mb-4">Price Range</h3>

        <div class="flex items-center gap-3">
            <div class="relative w-1/2 min-w-20">
                <span
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs font-medium"
                    >From</span
                >
                <input
                    v-model="minPrice"
                    type="number"
                    step="10"
                    min="0"
                    @change="handleChange"
                    @keyup.enter="handleChange"
                    class="w-full pl-12 pr-3 py-2 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all duration-300 placeholder:text-zinc-300"
                    placeholder="0"
                />
            </div>

            <div class="h-[1px] w-4 bg-zinc-300"></div>

            <div class="relative w-1/2 min-w-20">
                <span
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-zinc-500 text-xs font-medium"
                    >To</span
                >
                <input
                    v-model="maxPrice"
                    type="number"
                    step="10"
                    min="0"
                    @change="handleChange"
                    @keyup.enter="handleChange"
                    class="w-full pl-8 pr-3 py-2 bg-zinc-50 border border-zinc-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all duration-300 placeholder:text-zinc-300"
                    placeholder="Max"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    input[type='number'] {
        -moz-appearance: textfield;
    }
</style>
