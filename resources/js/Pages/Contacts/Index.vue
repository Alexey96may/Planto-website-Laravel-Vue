<template>
    <div class="min-h-screen bg-[#0b120c] text-white py-16 px-4 font-sans">
        <div class="max-w-6xl mx-auto">
            <h1
                class="text-5xl md:text-7xl font-bold mb-8 opacity-90 tracking-tight"
            >
                Contact <span class="text-green-500">Us</span>
            </h1>

            <div class="mt-16">
                <div class="space-y-10">
                    <div>
                        <h2 class="text-2xl font-semibold mb-6">Planto.</h2>
                        <div class="space-y-4 text-gray-400">
                            <p class="flex items-center gap-4">
                                <span class="text-green-500">📍</span>
                                г. Алматы, пр. Абая 150
                            </p>
                            <p class="flex items-center gap-4">
                                <span class="text-green-500">📞</span>
                                +7 (707) 123-45-67
                            </p>
                            <p class="flex items-center gap-4">
                                <span class="text-green-500">✉️</span>
                                support@planto.com
                            </p>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl mb-4">Follow us</h3>
                        <div class="flex gap-4">
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-green-600 transition"
                                >Ig</a
                            >
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-green-600 transition"
                                >Tg</a
                            >
                            <a
                                href="#"
                                class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-green-600 transition"
                                >Fb</a
                            >
                        </div>
                    </div>

                    <div
                        class="w-full h-64 bg-white/5 rounded-3xl border border-white/10 overflow-hidden flex items-center justify-center relative"
                    >
                        <span class="text-gray-500 italic"
                            >Interactive Map will be here</span
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";

defineOptions({
    layout: (h, page) =>
        h(
            MainLayout,
            {
                full: false,
            },
            () => page,
        ),
});
</script>

<style scoped>
/* Добавим мягкое свечение фону как на скрине */
.min-h-screen {
    background: radial-gradient(circle at top right, #1a2e1c 0%, #0b120c 50%);
}
</style>
