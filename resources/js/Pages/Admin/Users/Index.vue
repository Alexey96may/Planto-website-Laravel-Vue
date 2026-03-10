<script setup lang="ts">
    import { Head, router } from '@inertiajs/vue3';

    import { Calendar, Mail, ShieldCheck, User, UserCircle } from 'lucide-vue-next';

    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Role, User as UserType } from '@/types';

    type UserInAdmin = Pick<
        UserType,
        'id' | 'name' | 'email' | 'role' | 'created_at' | 'avatar_url'
    >;

    const props = defineProps<{
        users: UserInAdmin[];
    }>();

    const changeRole = (user: UserInAdmin, newRole: Role) => {
        router.patch(
            route('admin.users.updateRole', user.id),
            { role: newRole },
            {
                preserveScroll: true,
                onSuccess: () => {
                    /* Toast "Role updated!" */
                },
            },
        );
    };
</script>

<template>
    <AdminLayout>
        <Head title="Users Management" />

        <div class="mx-auto max-w-6xl space-y-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black uppercase italic tracking-tighter text-white">
                        User <span class="text-[#c5d86d]">Database</span>
                    </h1>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-zinc-500">
                        Total active entities: {{ users.length }}
                    </p>
                </div>
            </div>

            <div
                class="overflow-x-auto rounded-[1rem] border border-white/5 bg-[#161b14] shadow-2xl md:rounded-[2.5rem]"
            >
                <table class="w-full border-collapse text-left">
                    <thead
                        class="bg-black/20 text-[10px] font-black uppercase tracking-widest text-zinc-500"
                    >
                        <tr>
                            <th class="p-6">Identity</th>
                            <th class="p-6">Access Level</th>
                            <th class="p-6">Entry Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        <tr
                            v-for="user in users"
                            :key="user.id"
                            class="group transition-colors hover:bg-white/[0.02]"
                        >
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-white/10 bg-zinc-800 text-[#c5d86d]"
                                    >
                                        <img
                                            v-if="user.avatar_url"
                                            :src="user.avatar_url"
                                            class="h-full w-full rounded-xl object-cover"
                                        />
                                        <UserCircle v-else class="h-6 w-6" />
                                    </div>
                                    <div class="flex flex-col gap-1">
                                        <span class="text-sm font-bold text-white">{{
                                            user.name
                                        }}</span>
                                        <span
                                            class="flex items-center gap-1 text-[11px] font-medium text-zinc-500"
                                        >
                                            <Mail class="h-3 w-3" /> {{ user.email }}
                                        </span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-6">
                                <div class="relative min-w-[100px] max-w-[140px]">
                                    <ShieldCheck
                                        class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[#c5d86d]"
                                    />
                                    <select
                                        :value="user.role"
                                        @change="
                                            (e) =>
                                                changeRole(
                                                    user,
                                                    (e.target as HTMLSelectElement).value as Role,
                                                )
                                        "
                                        class="w-full appearance-none rounded-xl border border-white/5 bg-black/40 py-2 pl-9 pr-4 text-[10px] font-black uppercase tracking-widest text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                    >
                                        <option value="user">Standard User</option>
                                        <option value="admin">Administrator</option>
                                    </select>
                                </div>
                            </td>

                            <td class="p-6 text-zinc-500">
                                <div class="flex items-center gap-2 font-mono text-[11px]">
                                    <Calendar class="h-3.5 w-3.5" />
                                    {{ new Date(user.created_at).toLocaleDateString() }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
    select {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23c5d86d' stroke-width='2'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19.5 8.25l-7.5 7.5-7.5-7.5'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 1rem;
    }
</style>
