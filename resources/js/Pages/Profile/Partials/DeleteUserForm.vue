<script setup lang="ts">
    import { nextTick, ref } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import { AlertTriangleIcon, Trash2Icon } from 'lucide-vue-next';

    import DangerButton from '@/Components/UI/DangerButton.vue';
    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import Modal from '@/Components/UI/Modal.vue';
    import SecondaryButton from '@/Components/UI/SecondaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';

    const confirmingUserDeletion = ref<boolean>(false);
    const passwordInput = ref<HTMLInputElement | null>(null);

    const form = useForm({
        password: '',
    });

    const confirmUserDeletion = (): void => {
        confirmingUserDeletion.value = true;
        nextTick(() => passwordInput.value?.focus());
    };

    const deleteUser = (): void => {
        form.delete(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.value?.focus(),
            onFinish: () => form.reset(),
        });
    };

    const closeModal = (): void => {
        confirmingUserDeletion.value = false;
        form.clearErrors();
        form.reset();
    };
</script>

<template>
    <section class="space-y-6">
        <header class="flex items-start gap-4">
            <div
                class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-500/10 text-red-500"
            >
                <Trash2Icon class="h-5 w-5" />
            </div>
            <div>
                <h2 class="text-lg font-bold text-zinc-100">Danger Zone</h2>
                <p class="mt-1 text-sm leading-relaxed text-zinc-500">
                    Once your account is deleted, all of its resources and data will be permanently
                    deleted. Please be certain.
                </p>
            </div>
        </header>

        <DangerButton @click="confirmUserDeletion">Delete Account</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="rounded-3xl border border-white/5 bg-[#1a1f16] p-8">
                <div class="mb-4 flex items-center gap-3 text-red-500">
                    <AlertTriangleIcon class="h-6 w-6" />
                    <h2 class="text-xl font-black uppercase italic tracking-tight">
                        Final Confirmation
                    </h2>
                </div>

                <p class="text-sm leading-relaxed text-zinc-400">
                    This action is irreversible. All your garden data, orders, and preferences will
                    be wiped. Please enter your password to confirm.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Confirm Password" class="sr-only" />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full sm:w-3/4"
                        placeholder="Type your password..."
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <SecondaryButton @click="closeModal" class="px-6">
                        Wait, Go Back
                    </SecondaryButton>

                    <DangerButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                        class="px-6 shadow-lg shadow-red-900/20"
                    >
                        Yes, Delete Everything
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
