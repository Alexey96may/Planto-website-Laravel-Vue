<script setup lang="ts">
    const minPrice = defineModel<number | string>('min');
    const maxPrice = defineModel<number | string>('max');

    const emit = defineEmits<{
        (e: 'change-price'): void;
    }>();

    const handleChange = () => {
        // Базовая валидация: если min > max, можно поменять их местами или сбросить
        if (Number(minPrice.value) > Number(maxPrice.value) && maxPrice.value !== '') {
            const temp = minPrice.value;
            minPrice.value = maxPrice.value;
            maxPrice.value = temp;
        }
        emit('change-price');
    };
</script>

<template>
    <fieldset class="m-0 space-y-4 border-none p-0">
        <legend class="mb-4 block text-xs font-bold uppercase tracking-widest text-zinc-500">
            Price Range
        </legend>

        <div class="flex items-center gap-3">
            <div class="group relative w-1/2 min-w-20">
                <label
                    for="price-min"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black uppercase tracking-tight text-zinc-500 transition-colors group-focus-within:text-emerald-500"
                >
                    From
                </label>
                <input
                    id="price-min"
                    v-model="minPrice"
                    type="number"
                    inputmode="numeric"
                    step="10"
                    min="0"
                    @change="handleChange"
                    @keyup.enter="handleChange"
                    class="w-full rounded-xl border border-zinc-200 bg-zinc-50 py-2.5 pl-12 pr-3 text-sm text-zinc-900 outline-none transition-all duration-300 placeholder:text-zinc-300 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    placeholder="0"
                />
            </div>

            <div class="h-[1px] w-4 shrink-0 bg-zinc-300" aria-hidden="true"></div>

            <div class="group relative w-1/2 min-w-20">
                <label
                    for="price-max"
                    class="absolute left-3 top-1/2 -translate-y-1/2 text-[10px] font-black uppercase tracking-tight text-zinc-500 transition-colors group-focus-within:text-emerald-500"
                >
                    To
                </label>
                <input
                    id="price-max"
                    v-model="maxPrice"
                    type="number"
                    inputmode="numeric"
                    step="10"
                    min="0"
                    @change="handleChange"
                    @keyup.enter="handleChange"
                    class="w-full rounded-xl border border-zinc-200 bg-zinc-50 py-2.5 pl-10 pr-3 text-sm text-zinc-900 outline-none transition-all duration-300 placeholder:text-zinc-300 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                    placeholder="Max"
                />
            </div>
        </div>
    </fieldset>
</template>
