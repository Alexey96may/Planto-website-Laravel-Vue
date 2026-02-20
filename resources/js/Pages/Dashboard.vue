<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { defineProps } from "vue";
import { ref } from "vue";

const props = defineProps({
    myComments: Array,
    auth: Object,
});

const formAvatar = useForm({
    name: props.auth.user.name,
    email: props.auth.user.email,
    avatar: null,
    _method: "patch",
});

const imageUrl = ref(props.auth.user.avatar_url);

const onFileChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        formAvatar.avatar = file;
        imageUrl.value = URL.createObjectURL(file);
    }
};

const submitInfo = () => {
    formAvatar.post(route("profile.update"), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            photoPreview.value = null;
            form.avatar = null;
        },
    });
};

const form = useForm({
    body: "",
    rating: 5.0,
});

const submitComment = () => {
    form.post(route("profile.update"), {
        preserveScroll: true,
        onSuccess: () => {
            form.avatar = null;
        },
    });
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="profile-upload">
            <form @submit.prevent="submitInfo">
                <div class="flex flex-col items-center gap-4">
                    <div class="relative">
                        <img
                            :src="imageUrl"
                            class="w-32 h-32 rounded-full object-cover border"
                        />
                    </div>

                    <input
                        type="file"
                        id="avatar-input"
                        @change="onFileChange"
                        accept="image/*"
                        class="hidden"
                    />
                    <label
                        for="avatar-input"
                        class="cursor-pointer text-sm font-semibold text-blue-600 hover:text-blue-500"
                    >
                        Изменить фото
                    </label>
                    <p
                        v-if="formAvatar.errors.avatar"
                        class="text-red-500 text-xs"
                    >
                        {{ formAvatar.errors.avatar }}
                    </p>
                </div>

                <hr class="border-gray-100" />

                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Имя</label
                    >
                    <input
                        v-model="formAvatar.name"
                        type="text"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                    <div
                        v-if="formAvatar.errors.name"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ formAvatar.errors.name }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Email</label
                    >
                    <input
                        v-model="formAvatar.email"
                        type="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    />
                    <div
                        v-if="formAvatar.errors.email"
                        class="text-red-500 text-xs mt-1"
                    >
                        {{ formAvatar.errors.email }}
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <button
                        type="submit"
                        :disabled="formAvatar.processing"
                        class="w-full bg-blue-600 text-white py-2 px-4 rounded-md font-medium hover:bg-blue-700 transition disabled:opacity-50"
                    >
                        <span v-if="formAvatar.processing">Сохранение...</span>
                        <span v-else>Сохранить настройки</span>
                    </button>
                </div>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="formAvatar.recentlySuccessful"
                        class="text-sm text-green-600 text-center"
                    >
                        Данные успешно обновлены!
                    </p>
                </Transition>
            </form>
        </div>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        Welcome to the Plunt Shop!
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            <h3 class="text-lg font-medium">Мои отзывы</h3>

            <div v-if="myComments.length === 0" class="mt-4 text-gray-500">
                Вы еще не оставляли отзывов.
            </div>

            <div
                v-for="comment in myComments"
                :key="comment.id"
                class="border-b py-4"
            >
                <div class="flex justify-between">
                    <span class="font-bold"
                        >Оценка: {{ comment.rating }} ⭐</span
                    >
                    <span
                        :class="
                            comment.is_active
                                ? 'text-green-600'
                                : 'text-orange-500'
                        "
                    >
                        {{ comment.is_active ? "Опубликован" : "На модерации" }}
                    </span>
                </div>
                <p class="mt-2 text-gray-700">{{ comment.body }}</p>
                <small class="text-gray-400">{{
                    new Date(comment.created_at).toLocaleDateString()
                }}</small>
            </div>
        </div>

        <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <h2 class="text-xl font-semibold mb-4">Оставить отзыв о сайте</h2>

            <form @submit.prevent="submitComment" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Ваше мнение</label
                    >
                    <textarea
                        v-model="form.body"
                        rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Напишите, что вы думаете..."
                    ></textarea>
                    <div
                        v-if="form.errors.body"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.body }}
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700"
                        >Оценка (от 0.5 до 5)</label
                    >
                    <div class="flex items-center space-x-2">
                        <input
                            type="range"
                            v-model="form.rating"
                            min="0.5"
                            max="5"
                            step="0.5"
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                        />
                        <span class="font-bold text-lg text-indigo-600">{{
                            form.rating
                        }}</span>
                    </div>
                    <div
                        v-if="form.errors.rating"
                        class="text-red-500 text-sm mt-1"
                    >
                        {{ form.errors.rating }}
                    </div>
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition"
                >
                    {{ form.processing ? "Отправка..." : "Опубликовать отзыв" }}
                </button>
            </form>

            <hr class="my-8" />
        </div>
    </AuthenticatedLayout>
</template>
