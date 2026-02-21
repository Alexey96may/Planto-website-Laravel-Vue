<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps({
    parentOptions: Array, // Все возможные пункты для parent_id
});

const form = useForm({
    title: "",
    link: "",
    location: "header",
    parent_id: null,
    order: 0,
    is_active: true,
});

// Фильтруем родителей: показываем только тех, кто в той же локации, что и новый пункт
const filteredParents = computed(() => {
    return props.parentOptions.filter((p) => p.location === form.location);
});

const submit = () => {
    form.post(route("admin.navigation.store"));
};
</script>

<template>
    <form
        @submit.prevent="submit"
        class="max-w-2xl bg-white p-6 rounded shadow"
    >
        <div class="grid gap-4">
            <div>
                <label class="block">Где отображать?</label>
                <select
                    v-model="form.location"
                    class="w-full border p-2 rounded"
                >
                    <option value="header">Header (Шапка)</option>
                    <option value="footer">Footer (Подвал)</option>
                </select>
            </div>

            <div>
                <label class="block">Название пункта</label>
                <input
                    v-model="form.title"
                    type="text"
                    class="w-full border p-2 rounded"
                    required
                />
            </div>

            <div>
                <label class="block">Ссылка (URL или /path)</label>
                <input
                    v-model="form.link"
                    type="text"
                    class="w-full border p-2 rounded"
                />
            </div>

            <div v-if="filteredParents.length">
                <label class="block">Родительский пункт (опционально)</label>
                <select
                    v-model="form.parent_id"
                    class="w-full border p-2 rounded"
                >
                    <option :value="null">
                        Без родителя (корневой уровень)
                    </option>
                    <option
                        v-for="parent in filteredParents"
                        :key="parent.id"
                        :value="parent.id"
                    >
                        {{ parent.title }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block">Порядок сортировки</label>
                <input
                    v-model="form.order"
                    type="number"
                    class="w-full border p-2 rounded"
                />
            </div>

            <button
                type="submit"
                :disabled="form.processing"
                class="bg-green-600 text-white p-2 rounded"
            >
                Сохранить пункт меню
            </button>
        </div>
    </form>
</template>
