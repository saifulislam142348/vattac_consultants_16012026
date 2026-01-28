<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-lg p-6 animate-fade-in">
            <h3 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Home Page Content</h3>
            <form @submit.prevent="saveContent" class="grid grid-cols-1 gap-6 max-w-4xl">
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Hero Title</label>
                    <input v-model="contentForm.hero_title" type="text" class="w-full p-3 border rounded focus:ring-1 focus:ring-brand-blue outline-none">
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-2">Hero Subtitle</label>
                    <textarea v-model="contentForm.hero_subtitle" class="w-full p-3 border rounded focus:ring-1 focus:ring-brand-blue outline-none" rows="3"></textarea>
                </div>
                <div>
                    <label class="block font-medium text-gray-700 mb-2">About Us Text</label>
                    <textarea v-model="contentForm.about_text" class="w-full p-3 border rounded focus:ring-1 focus:ring-brand-blue outline-none" rows="5"></textarea>
                </div>
                <div class="text-right">
                    <button type="submit" class="bg-brand-blue text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition font-bold">
                        <i class="fas fa-save mr-2"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const toast = useToast();
const contentForm = reactive({
    hero_title: '',
    hero_subtitle: '',
    about_text: '',
});

const fetchContent = async () => {
    try {
        const res = await axios.get('/api/content/home');
        if (res.data) {
            contentForm.hero_title = res.data.hero_title || '';
            contentForm.hero_subtitle = res.data.hero_subtitle || '';
            contentForm.about_text = res.data.about_text || '';
        }
    } catch (e) {
        console.error('Failed to fetch content');
    }
};

const saveContent = async () => {
    try {
        await axios.post('/api/admin/content', {
            section: 'home',
            contents: contentForm
        });
        toast.success('Content updated successfully!');
    } catch (e) {
        toast.error('Failed to update content');
    }
};

onMounted(() => {
    fetchContent();
});
</script>
