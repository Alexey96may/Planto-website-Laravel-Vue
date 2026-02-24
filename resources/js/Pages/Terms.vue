<template>
    <div
        class="min-h-screen bg-[#0b120c] text-white py-20 px-6 font-sans selection:bg-green-500/30"
    >
        <div class="max-w-4xl mx-auto">
            <div class="mb-16 border-b border-white/10 pb-10 reveal">
                <h1 class="text-4xl md:text-6xl font-bold mb-4">
                    Terms of <span class="text-green-500">Service</span>
                </h1>
                <p class="text-gray-500 uppercase tracking-[0.2em] text-sm">
                    Последнее обновление: 24 февраля 2026
                </p>
            </div>

            <div
                class="bg-white/5 backdrop-blur-md border border-white/10 rounded-[40px] p-8 md:p-12 space-y-12 text-gray-300 leading-relaxed"
            >
                <section class="reveal">
                    <h2
                        class="text-2xl font-semibold text-white mb-4 flex items-center gap-3"
                    >
                        <span class="text-green-500">01.</span> Общие положения
                    </h2>
                    <p>
                        Добро пожаловать в Planto. Используя наш сайт, вы
                        соглашаетесь с данными условиями. Мы просим вас
                        внимательно ознакомиться с ними, так как они регулируют
                        ваши права и обязанности при покупке растений и
                        аксессуаров.
                    </p>
                </section>

                <section class="reveal">
                    <h2
                        class="text-2xl font-semibold text-white mb-4 flex items-center gap-3"
                    >
                        <span class="text-green-500">02.</span> Условия доставки
                    </h2>
                    <p>
                        Мы заботимся о каждом растении. Доставка осуществляется
                        специализированными курьерскими службами. Planto несет
                        ответственность за состояние растения до момента
                        передачи его покупателю.
                    </p>
                </section>

                <section class="reveal">
                    <h2
                        class="text-2xl font-semibold text-white mb-4 flex items-center gap-3"
                    >
                        <span class="text-green-500">03.</span> Политика
                        возврата
                    </h2>
                    <p>
                        Живые растения являются скоропортящимся товаром. Возврат
                        возможен только в случае обнаружения явных дефектов или
                        болезней в момент получения заказа.
                    </p>
                </section>

                <section class="reveal">
                    <h2
                        class="text-2xl font-semibold text-white mb-4 flex items-center gap-3"
                    >
                        <span class="text-green-500">04.</span> Интеллектуальная
                        собственность
                    </h2>
                    <p>
                        Весь контент, включая фотографии растений и описания,
                        является собственностью Planto. Использование материалов
                        без нашего согласия запрещено.
                    </p>
                </section>

                <div
                    class="pt-8 border-t border-white/5 flex justify-between items-center"
                >
                    <p class="text-sm text-gray-500">
                        Есть вопросы?
                        <Link
                            href="/contacts"
                            class="text-green-500 hover:underline"
                            >Напишите нам</Link
                        >
                    </p>
                    <Link
                        href="/"
                        class="px-8 py-3 bg-white text-black rounded-full font-bold hover:bg-green-500 hover:text-white transition-colors duration-300"
                    >
                        Согласен
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { onMounted } from "vue";

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

onMounted(() => {
    const observerOptions = {
        threshold: 0.1,
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("reveal-visible");
            }
        });
    }, observerOptions);

    document.querySelectorAll(".reveal").forEach((el) => observer.observe(el));
});
</script>

<style scoped>
/* Плавный градиент на фоне, чтобы страница не была скучной */
.min-h-screen {
    background: radial-gradient(circle at bottom left, #122214 0%, #0b120c 40%);
}

/* Базовое состояние: невидимый и чуть ниже своего места */
.reveal {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
}

/* Состояние после появления: прозрачность 1 и возврат на место */
.reveal-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>
