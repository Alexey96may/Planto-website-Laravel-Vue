<script setup lang="ts">
    import { Head, router } from '@inertiajs/vue3';

    import { Calendar, Mail, ShieldCheck, User, UserCircle } from 'lucide-vue-next';

    import SeoMeta from '@/Components/Shared/SeoMeta.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import { Role, Seo, User as UserType } from '@/types';

    type UserInAdmin = Pick<
        UserType,
        'id' | 'name' | 'email' | 'role' | 'created_at' | 'avatar_url'
    >;

    const props = defineProps<{
        users: UserInAdmin[];
        seo?: Seo;
    }>();

    const changeRole = (user: UserInAdmin, newRole: Role) => {
        router.patch(
            route('admin.users.updateRole', user.id),
            { role: newRole },
            {
                preserveScroll: true,
            },
        );
    };
</script>

<template>
    <SeoMeta :seo="props.seo" />

    <AdminLayout>
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

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 2xl:grid-cols-3">
                <transition-group
                    enter-active-class="transition duration-500 ease-out"
                    enter-from-class="transform translate-x-4 opacity-0"
                    leave-active-class="transition duration-300 ease-in"
                    leave-to-class="transform -translate-x-10 opacity-0"
                    move-class="transition duration-500"
                >
                    <div
                        v-for="user in users"
                        :key="user.id"
                        class="group relative overflow-hidden rounded-[1rem] border border-white/5 bg-[#161b14] p-6 transition-all hover:border-[#c5d86d]/20 hover:bg-[#1a2118] lg:rounded-[2rem]"
                    >
                        <div
                            class="absolute -right-4 -top-4 opacity-[0.02] transition-opacity group-hover:opacity-[0.05]"
                        >
                            <ShieldCheck class="h-32 w-32 text-[#c5d86d]" />
                        </div>

                        <div class="relative space-y-6">
                            <div class="flex items-center gap-4">
                                <div
                                    class="relative h-14 w-14 shrink-0 overflow-hidden rounded-2xl border border-white/10 bg-zinc-800 p-0.5"
                                >
                                    <img
                                        v-if="user.avatar_url"
                                        :src="user.avatar_url"
                                        class="h-full w-full rounded-[14px] object-cover"
                                    />
                                    <div
                                        v-else
                                        class="flex h-full w-full items-center justify-center text-[#c5d86d]"
                                    >
                                        <UserCircle class="h-8 w-8" />
                                    </div>
                                </div>

                                <div class="flex min-w-0 flex-1 flex-col gap-1">
                                    <h3
                                        class="truncate text-sm font-black uppercase tracking-tight text-white"
                                    >
                                        {{ user.name }}
                                    </h3>
                                    <div
                                        class="flex items-center gap-1.5 text-[11px] font-medium text-zinc-500"
                                    >
                                        <Mail class="h-3 w-3 shrink-0" />
                                        <span class="truncate">{{ user.email }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label
                                    class="text-[9px] font-black uppercase tracking-[0.2em] text-zinc-600"
                                    for="role"
                                >
                                    Access Level
                                </label>
                                <div class="relative">
                                    <ShieldCheck
                                        class="pointer-events-none absolute left-3 top-1/2 h-3.5 w-3.5 -translate-y-1/2 text-[#c5d86d]"
                                    />
                                    <select
                                        :value="user.role"
                                        id="role"
                                        @change="
                                            (e) =>
                                                changeRole(
                                                    user,
                                                    (e.target as HTMLSelectElement).value as Role,
                                                )
                                        "
                                        class="w-full appearance-none rounded-xl border border-white/5 bg-black/40 py-2.5 pl-9 pr-4 text-[10px] font-black uppercase tracking-widest text-white outline-none transition-all focus:ring-1 focus:ring-[#c5d86d]/50"
                                    >
                                        <option value="user">Standard User</option>
                                        <option value="admin">Administrator</option>
                                    </select>
                                </div>
                            </div>

                            <div
                                class="flex items-center justify-between border-t border-white/5 pt-4"
                            >
                                <div
                                    class="flex items-center gap-2 font-mono text-[10px] text-zinc-600"
                                >
                                    <Calendar class="h-3 w-3" />
                                    {{ new Date(user.created_at).toLocaleDateString() }}
                                </div>
                                <div class="text-[9px] font-bold italic text-zinc-700">
                                    ID: #{{ user.id }}
                                </div>
                            </div>
                        </div>
                    </div>
                </transition-group>
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

    .v-move {
        transition: transform 0.5s ease;
    }

    .v-leave-active {
        position: absolute;
        width: 100%;
    }
</style>
