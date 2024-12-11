<template>
  <div class="p-6 bg-gray-100 min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Blogs</h1>

    <!-- Список блогов -->
    <div v-if="blogs.length" class="space-y-4">
      <div
        v-for="blog in blogs"
        :key="blog.id"
        class="p-4 bg-white shadow rounded"
      >
        <h2 class="text-xl font-semibold">{{ blog.title }}</h2>
        <p class="text-gray-700">{{ blog.content }}</p>
        <p class="text-sm text-gray-500">Author: {{ blog.user.name }}</p>
        <img
          v-if="blog.image"
          :src="`/storage/${blog.image}`"
          alt="Blog Image"
          class="w-32 h-32 object-cover mt-2"
        />
        <div class="mt-4 flex space-x-2">
          <button
            v-if="blog.user_id === currentUserId"
            class="px-4 py-2 bg-blue-500 text-white rounded"
            @click="editBlog(blog)"
          >
            Edit
          </button>
          <button
            v-if="blog.user_id === currentUserId"
            class="px-4 py-2 bg-red-500 text-white rounded"
            @click="deleteBlog(blog.id)"
          >
            Delete
          </button>
        </div>
      </div>
    </div>
    <div v-else class="text-gray-500">No blogs available.</div>

    <!-- Форма создания/редактирования -->
    <div class="mt-6">
      <h2 v-if="form.id" class="text-2xl font-bold mb-4">Edit Blog</h2>
      <h2 v-else class="text-2xl font-bold mb-4">Create Blog</h2>
      <form @submit.prevent="saveBlog">
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2" for="title">Title</label>
          <input
            v-model="form.title"
            type="text"
            id="title"
            class="block w-full p-2 border rounded"
            required
          />
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2" for="content">Content</label>
          <textarea
            v-model="form.content"
            id="content"
            rows="4"
            class="block w-full p-2 border rounded"
            required
          ></textarea>
        </div>
        <div class="mb-4">
          <label class="block text-sm font-medium mb-2" for="image">Image</label>
          <input
            type="file"
            id="image"
            @change="handleImage"
            class="block w-full p-2 border rounded"
          />
        </div>
        <button
          type="submit"
          class="px-4 py-2 bg-green-500 text-white rounded"
        >
          {{ form.id ? 'Update Blog' : 'Create Blog' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

// Текущий пользователь
const currentUserId = 1; // Замените на реальный ID пользователя, если он есть

// Данные для блогов
const blogs = ref([]);
const form = reactive({
  id: null,
  title: '',
  content: '',
  image: null,
});

// Загрузка блогов
const fetchBlogs = async () => {
  const response = await axios.get('/api/blogs');
  blogs.value = response.data.blogs;
};

// Обработка выбора изображения
const handleImage = (e) => {
  form.image = e.target.files[0];
};

// Сохранение нового или редактирование существующего блога
const saveBlog = async () => {
  const formData = new FormData();
  formData.append('title', form.title);
  formData.append('content', form.content);
  if (form.image) formData.append('image', form.image);

  if (form.id) {
    await axios.post(`/api/blogs/${form.id}?_method=PUT`, formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  } else {
    await axios.post('/api/blogs', formData, {
      headers: { 'Content-Type': 'multipart/form-data' },
    });
  }

  resetForm();
  fetchBlogs();
};

// Удаление блога
const deleteBlog = async (id) => {
  await axios.delete(`/api/blogs/${id}`);
  fetchBlogs();
};

// Редактирование блога
const editBlog = (blog) => {
  form.id = blog.id;
  form.title = blog.title;
  form.content = blog.content;
};

// Сброс формы
const resetForm = () => {
  form.id = null;
  form.title = '';
  form.content = '';
  form.image = null;
};

// Загрузка данных при монтировании компонента
onMounted(() => {
  fetchBlogs();
});
</script>

<style>
/* Добавьте свой стиль, если нужно */
</style>
