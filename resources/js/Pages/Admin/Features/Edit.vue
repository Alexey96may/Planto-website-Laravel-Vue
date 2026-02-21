<script setup>
import { useForm } from "@inertiajs/vue3";

// Получаем данные карточки от контроллера
const props = defineProps({
    feature: Object,
});

const form = useForm({
    _method: "put", // ВАЖНО: Маскировка под PUT запрос
    title: props.feature.title,
    description: props.feature.description,
    link: props.feature.link,
    order: props.feature.order,
    image: null, // Новое фото, если захотим сменить
});

const submit = () => {
    // ВНИМАНИЕ: Отправляем через POST (из-за фото),
    // но Laravel поймет что это UPDATE благодаря полю _method выше
    form.post(route("admin.features.update", props.feature.id));
};

const handleFileUpload = (e) => {
    form.image = e.target.files[0];
};
</script>

<template>
    <div class="max-w-2xl mx-auto p-6 bg-white shadow rounded">
        <h1 class="text-xl font-bold mb-4">
            Редактировать: {{ feature.title }}
        </h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <label class="block">Заголовок</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border p-2 rounded"
                />
            </div>

            <div>
                <label class="block">Текст</label>
                <textarea
                    v-model="form.description"
                    class="w-full border p-2 rounded"
                ></textarea>
            </div>

            <div>
                <label class="block font-bold">Текущее фото:</label>
                <img
                    v-if="feature.image_url"
                    :src="feature.image_url"
                    class="w-32 mb-2 rounded"
                />

                <label class="block">Заменить фото (если нужно):</label>
                <input type="file" @change="handleFileUpload" />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-blue-500 text-white px-6 py-2 rounded"
            >
                Обновить
            </button>
        </form>
    </div>
</template>
