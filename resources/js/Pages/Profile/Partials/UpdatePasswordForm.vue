<script setup lang="ts">
    import { ref } from 'vue';

    import { useForm } from '@inertiajs/vue3';

    import { CheckCircleIcon, KeyIcon } from 'lucide-vue-next';

    import InputError from '@/Components/UI/InputError.vue';
    import InputLabel from '@/Components/UI/InputLabel.vue';
    import PrimaryButton from '@/Components/UI/PrimaryButton.vue';
    import TextInput from '@/Components/UI/TextInput.vue';

    const passwordInput = ref<HTMLInputElement | null>(null);
    const currentPasswordInput = ref<HTMLInputElement | null>(null);

    const form = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword = (): void => {
        form.put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => form.reset(),
            onError: () => {
                if (form.errors.password) {
                    form.reset('password', 'password_confirmation');
                    passwordInput.value?.focus();
                }
                if (form.errors.current_password) {
                    form.reset('current_password');
                    currentPasswordInput.value?.focus();
                }
            },
        });
    };
</script>

<template>
    <section>
        <header class="flex items-center gap-4">
            <div
                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-[#c5d86d]/10 text-[#c5d86d]"
            >
                <KeyIcon class="h-5 w-5" />
            </div>
            <div>
                <h2 class="text-lg font-bold text-zinc-100">Update Password</h2>
                <p class="mt-1 text-sm text-zinc-500">
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </div>
        </header>

        <form @submit.prevent="updatePassword" class="mt-8 space-y-6">
            <div class="max-w-md">
                <InputLabel for="current_password" value="Current Password" class="text-zinc-400" />

                <TextInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="current-password"
                    placeholder="••••••••"
                />

                <InputError :message="form.errors.current_password" class="mt-2" />
            </div>

            <div class="max-w-md">
                <InputLabel for="password" value="New Password" class="text-zinc-400" />

                <TextInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    placeholder="Min 8 characters"
                />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="max-w-md">
                <InputLabel
                    for="password_confirmation"
                    value="Confirm New Password"
                    class="text-zinc-400"
                />

                <TextInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    autocomplete="new-password"
                    placeholder="Confirm your new password"
                />

                <InputError :message="form.errors.password_confirmation" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton
                    :disabled="form.processing"
                    class="px-8 shadow-lg shadow-emerald-900/20"
                >
                    Save Changes
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0 translate-x-[-10px]"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-to-class="opacity-0 translate-x-[10px]"
                >
                    <div
                        v-if="form.recentlySuccessful"
                        class="flex items-center gap-2 text-sm font-medium text-emerald-500"
                    >
                        <CheckCircleIcon class="h-4 w-4" />
                        <span>Saved successfully</span>
                    </div>
                </Transition>
            </div>
        </form>
    </section>
</template>
