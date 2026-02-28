<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3';
    import { useForm } from '@inertiajs/vue3';

    import { route } from 'ziggy-js';

    import AdminLayout from '@/Layouts/AdminLayout.vue';

    const form = useForm({ subject: '', message: '' });

    interface Stats {
        products_count: number;
        users_count: number;
    }

    const props = defineProps<{
        stats: Stats;
    }>();
</script>

<template>
    <AdminLayout>
        <Head title="Dashboard" />

        <div class="p-8 max-w-7xl mx-auto">
            <div class="p-6 bg-gray-900 rounded-2xl text-white flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold">Quick Action</h3>
                    <p class="text-gray-400 text-sm">Want to add a new flower to the shop?</p>
                </div>
                <Link
                    :href="route('admin.products.create')"
                    class="bg-green-500 hover:bg-green-600 px-6 py-2 rounded-xl font-bold transition"
                >
                    + Add Product
                </Link>
            </div>

            <br />
            <br />

            <h1 class="text-3xl font-bold mb-8 text-gray-800">Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <Link
                    :href="route('admin.products.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-green-600 bg-green-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        📦
                    </div>
                    <h2 class="text-xl font-bold mb-2">Products</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Manage inventory ({{ stats.products_count }} items)
                    </p>
                    <span class="text-green-600 font-medium flex items-center">Manage →</span>
                </Link>

                <Link
                    :href="route('admin.categories.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-purple-600 bg-purple-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        📂
                    </div>
                    <h2 class="text-xl font-bold mb-2">Categories</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Create, delete, and edit product categories
                    </p>
                    <span class="text-purple-600 font-medium flex items-center">Manage →</span>
                </Link>

                <Link
                    :href="route('admin.orders.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        🛒
                    </div>
                    <h2 class="text-xl font-bold mb-2">Orders</h2>
                    <p class="text-gray-500 text-sm mb-4">Order list. New: (Not available yet)</p>
                    <span class="text-blue-600 font-medium flex items-center">View →</span>
                </Link>

                <Link
                    :href="route('admin.comments.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-yellow-600 bg-yellow-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        💬
                    </div>
                    <h2 class="text-xl font-bold mb-2">Comments</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        User reviews. Pending:
                        <span v-if="!$page.props.pending_comments_count"
                            >({{ $page.props.pending_comments_count }})</span
                        >
                        <span
                            v-else
                            class="inline-flex items-center justify-center px-2 py-1 ml-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full animate-bounce"
                        >
                            ({{ $page.props.pending_comments_count }})
                        </span>
                    </p>
                    <span class="text-yellow-600 font-medium flex items-center">Moderate →</span>
                </Link>

                <Link
                    :href="route('admin.users.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-blue-600 bg-blue-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        👥
                    </div>
                    <h2 class="text-xl font-bold mb-2">Users</h2>
                    <p class="text-gray-500 text-sm mb-4">
                        Customer list and access rights ({{ stats.users_count }})
                    </p>
                    <span class="text-blue-600 font-medium flex items-center">Manage →</span>
                </Link>

                <Link
                    :href="route('admin.navigation.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-gray-600 bg-gray-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        🗺️
                    </div>
                    <h2 class="text-xl font-bold mb-2">Navigation</h2>
                    <p class="text-gray-500 text-sm mb-4">Manage site menu and links</p>
                    <span class="text-gray-600 font-medium flex items-center">Edit →</span>
                </Link>

                <Link
                    :href="route('admin.features.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-orange-600 bg-orange-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        ✨
                    </div>
                    <h2 class="text-xl font-bold mb-2">Features</h2>
                    <p class="text-gray-500 text-sm mb-4">Manage site features/highlights</p>
                    <span class="text-orange-600 font-medium flex items-center">Manage →</span>
                </Link>

                <Link
                    :href="route('admin.settings.index')"
                    class="group p-6 bg-white rounded-2xl shadow-sm hover:shadow-md transition border border-gray-100"
                >
                    <div
                        class="text-red-600 bg-red-50 w-12 h-12 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition"
                    >
                        ⚙️
                    </div>
                    <h2 class="text-xl font-bold mb-2">General Settings</h2>
                    <p class="text-gray-500 text-sm mb-4">Phone, email, and store info</p>
                    <span class="text-red-600 font-medium flex items-center">Edit →</span>
                </Link>
            </div>

            <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-4">Create Newsletter</h2>
                <form @submit.prevent="form.post(route('admin.newsletter.broadcast'))">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"
                            >Email Subject</label
                        >
                        <input
                            v-model="form.subject"
                            type="text"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Enter subject..."
                        />
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1"
                            >Message Body</label
                        >
                        <textarea
                            v-model="form.message"
                            rows="5"
                            class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-blue-500 outline-none"
                            placeholder="Type your message here..."
                        ></textarea>
                    </div>
                    <button
                        :disabled="form.processing"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-xl font-bold transition disabled:opacity-50"
                    >
                        {{ form.processing ? 'Sending...' : 'Send to All Subscribers' }}
                    </button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
