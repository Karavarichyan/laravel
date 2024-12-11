<!-- <script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template> -->


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

const blogs = ref([]);

// Загрузка блогов
const fetchBlogs = async () => {
    try {
        const response = await axios.get('/api/blogs');
        blogs.value = response.data.data;
    } catch (error) {
        console.error('Error fetching blogs:', error);
    }
};

onMounted(() => {
    fetchBlogs();
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        You're logged in!
                    </div>
                </div>

                <!-- Раздел блогов -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                        Your Blogs
                    </h3>
                    <div v-if="blogs.length > 0" class="mt-4 space-y-4">
                        <div
                            v-for="blog in blogs"
                            :key="blog.id"
                            class="p-4 bg-white shadow rounded dark:bg-gray-700"
                        >
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                {{ blog.title }}
                            </h4>
                            <p class="text-gray-700 dark:text-gray-300">
                                {{ blog.content }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Created by: {{ blog.user.name }}
                            </p>
                        </div>
                    </div>
                    <p v-else class="text-gray-500 dark:text-gray-400">
                        No blogs found.
                    </p>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
