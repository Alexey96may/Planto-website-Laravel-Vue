<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import GuestLayout from "@/Layouts/GuestLayout.vue"; // Или AuthenticatedLayout, если только для своих

const props = defineProps({
    cartItems: Array,
    total: Number,
});

// Инициализируем форму через Inertia useForm
const form = useForm({
    name: usePage().props.auth.user?.name || "", // Предзаполняем, если залогинен
    email: "",
    phone: "",
    address: "",
    comment: "",
    create_account: false,
});

const submit = () => {
    form.post(route("checkout.store"), {
        onSuccess: () => {
            console.log("Ура! Заказ создан");
        },
        onError: (errors) => {
            console.log("Ошибки валидации:", errors);
        },
    });
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded-lg mt-10">
        <h1 class="text-2xl font-bold mb-6">Оформление заказа</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block text-sm font-medium">Ваше Имя</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full border rounded p-2"
                    required
                />
                <div v-if="form.errors.name" class="text-red-500 text-xs">
                    {{ form.errors.name }}
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium">Телефон</label>
                <input
                    v-model="form.phone"
                    type="text"
                    placeholder="+7 (___) ___-__-__"
                    class="w-full border rounded p-2"
                    required
                />
            </div>

            <div>
                <input
                    v-model="form.email"
                    type="email"
                    placeholder="Ваш Email"
                />
            </div>

            <div>
                <label class="block text-sm font-medium">Адрес доставки</label>
                <textarea
                    v-model="form.address"
                    class="w-full border rounded p-2"
                    required
                    rows="3"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium"
                    >Комментарий к доставке</label
                >
                <textarea
                    v-model="form.comment"
                    class="w-full border rounded p-2"
                    rows="3"
                ></textarea>
            </div>

            <div class="mt-4" v-if="!$page.props.auth.user">
                <label class="flex items-center">
                    <input
                        type="checkbox"
                        v-model="form.create_account"
                        class="rounded border-gray-300 text-pink-600 shadow-sm focus:ring-pink-500"
                    />
                    <span class="ml-2 text-sm text-gray-600"
                        >Создать личный кабинет, чтобы следить за статусом
                        заказа</span
                    >
                </label>
            </div>

            <div class="border-t pt-4">
                <h3 class="font-bold mb-2">Ваш заказ:</h3>
                <div
                    v-for="item in cartItems"
                    :key="item.id"
                    class="flex items-center"
                >
                    <img :src="item.image" class="w-16 h-16 object-cover" />

                    <div>
                        <h4>{{ item.title }}</h4>
                        <p>{{ item.quantity }} x {{ item.price }} $</p>
                    </div>
                </div>
            </div>

            <div class="border-t pt-4">
                <h3 class="font-bold mb-2">Общая сумма:</h3>
                <p>{{ total }} $</p>
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="w-full bg-green-600 text-white py-3 rounded font-bold hover:bg-green-700 disabled:opacity-50"
            >
                {{ form.processing ? "Обработка..." : "Подтвердить заказ" }}
            </button>
        </form>
    </div>
</template>
