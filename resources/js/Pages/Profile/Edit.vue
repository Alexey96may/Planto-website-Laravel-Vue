<script setup lang="ts">
    import { Head } from '@inertiajs/vue3';

    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

    import DeleteUserForm from './Partials/DeleteUserForm.vue';
    import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
    import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';

    const props = defineProps<{
        mustVerifyEmail?: boolean;
        status?: string;
    }>();
</script>

<template>
    <Head title="Account Settings" />

    <AuthenticatedLayout>
        <template #header> Settings </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden border border-white/5 bg-[#242b1f]/40 shadow-2xl backdrop-blur-md transition-all hover:border-white/10 sm:rounded-3xl"
                >
                    <div class="p-6 sm:p-10">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>
                </div>

                <div
                    class="overflow-hidden border border-white/5 bg-[#242b1f]/40 shadow-2xl backdrop-blur-md transition-all hover:border-white/10 sm:rounded-3xl"
                >
                    <div class="p-6 sm:p-10">
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>
                </div>

                <div
                    class="overflow-hidden border border-red-500/10 bg-[#242b1f]/40 shadow-2xl backdrop-blur-md transition-all hover:border-red-500/20 sm:rounded-3xl"
                >
                    <div class="p-6 sm:p-10">
                        <DeleteUserForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
