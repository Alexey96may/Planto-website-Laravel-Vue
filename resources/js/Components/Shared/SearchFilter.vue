<script setup lang="ts">
    const search = defineModel<string>({ default: '' });
    const emit = defineEmits(['apply-search']);

    const handleEnter = () => {
        emit('apply-search');
    };
</script>

<template>
    <div class="group relative mb-8 text-zinc-900">
        <div
            class="absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400 transition-colors group-focus-within:text-emerald-500"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                />
            </svg>
        </div>

        <input
            v-model="search"
            @keyup.enter="handleEnter"
            type="text"
            placeholder="Search plants..."
            class="w-full rounded-2xl border border-zinc-200 bg-zinc-50 py-2 pl-11 pr-4 text-sm outline-none transition-all duration-300 placeholder:text-zinc-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
        />

        <button
            v-if="search"
            @click="
                search = '';
                handleEnter();
            "
            class="absolute right-4 top-1/2 -translate-y-1/2 text-zinc-300 transition-colors hover:text-zinc-500"
        >
            <svg
                xmlns="http://www.w3.org/2000/svg"
                class="h-4 w-4"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
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
