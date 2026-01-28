<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-lg p-6 animate-fade-in">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-users mr-2 text-brand-blue"></i> Our Expert Team
                </h3>
                <button @click="openModal()" class="px-4 py-2 bg-brand-blue text-white rounded font-bold hover:bg-blue-700 transition shadow">
                    <i class="fas fa-plus mr-2"></i> Add Member
                </button>
            </div>

            <!-- Team List -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-4 border-b">Image</th>
                            <th class="p-4 border-b">Name / Designation</th>
                            <th class="p-4 border-b">Contact Info</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr v-for="member in teams" :key="member.id" class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <img v-if="member.image" :src="`/storage/${member.image}`" alt="Team" class="h-12 w-12 rounded-full object-cover border">
                                <div v-else class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center text-gray-500">
                                    <i class="fas fa-user"></i>
                                </div>
                            </td>
                            <td class="p-4">
                                <p class="font-bold text-gray-800">{{ member.name }}</p>
                                <p class="text-brand-blue text-xs">{{ member.designation }}</p>
                                <p class="text-gray-500 text-xs mt-1">{{ member.education }}</p>
                            </td>
                            <td class="p-4 text-xs text-gray-600">
                                <div v-if="member.address"><i class="fas fa-map-marker-alt w-4"></i> {{ member.address }}</div>
                                <div v-if="member.whatsapp"><i class="fab fa-whatsapp w-4"></i> {{ member.whatsapp }}</div>
                                <div v-if="member.fb_link"><a :href="member.fb_link" target="_blank" class="text-blue-600 hover:underline"><i class="fab fa-facebook w-4"></i> FB Profile</a></div>
                            </td>
                            <td class="p-4 text-right whitespace-nowrap">
                                <button @click="openModal(member)" class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-md hover:bg-blue-200 transition text-xs font-bold mr-2">
                                    <i class="fas fa-edit mr-1"></i> Edit
                                </button>
                                <button @click="deleteTeam(member.id)" class="inline-flex items-center px-3 py-1 bg-red-100 text-red-700 rounded-md hover:bg-red-200 transition text-xs font-bold">
                                    <i class="fas fa-trash-alt mr-1"></i> Delete
                                </button>
                            </td>
                        </tr>
                        <tr v-if="teams.length === 0">
                            <td colspan="4" class="p-8 text-center text-gray-500 italic">No team members added yet.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add/Edit Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down max-h-[90vh] overflow-y-auto">
                <h3 class="text-xl font-bold mb-4 font-heading text-brand-dark">
                    {{ isEditing ? 'Edit Team Member' : 'Add Team Member' }}
                </h3>
                
                <form @submit.prevent="submitForm" class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-1 text-sm">Full Name <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" required class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-1 text-sm">Designation <span class="text-red-500">*</span></label>
                        <input v-model="form.designation" type="text" required class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="e.g. Senior Consultant">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-1 text-sm">Education</label>
                        <input v-model="form.education" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="e.g. LLB (Hon's), LLM">
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-1 text-sm">Address/Location</label>
                        <input v-model="form.address" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-1 text-sm">WhatsApp Number</label>
                            <input v-model="form.whatsapp" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="+880...">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-1 text-sm">Facebook Link</label>
                            <input v-model="form.fb_link" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="https://facebook.com/...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-1 text-sm">Profile Image</label>
                        <div v-if="isEditing && currentImage" class="mb-2 flex items-center gap-3">
                             <img :src="`/storage/${currentImage}`" class="h-12 w-12 rounded-full border object-cover">
                             <span class="text-xs text-gray-500">Current Image</span>
                        </div>
                        <input type="file" @change="handleImageUpload" class="w-full p-2 border rounded bg-gray-50 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image (if editing).</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <button type="button" @click="closeModal" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                        <button type="submit" class="px-5 py-2 bg-brand-blue text-white rounded font-bold hover:bg-blue-700 transition shadow">
                            {{ isEditing ? 'Update Member' : 'Save Member' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const toast = useToast();
const teams = ref([]);
const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const imageFile = ref(null);
const currentImage = ref(null);

const form = reactive({
    name: '',
    designation: '',
    education: '',
    address: '',
    whatsapp: '',
    fb_link: ''
});

const fetchTeams = async () => {
    try {
        const res = await axios.get('/api/teams');
        teams.value = res.data;
    } catch (e) {
        console.error('Failed to fetch teams');
    }
};

const openModal = (member = null) => {
    if (member) {
        isEditing.value = true;
        editingId.value = member.id;
        form.name = member.name;
        form.designation = member.designation;
        form.education = member.education || '';
        form.address = member.address || '';
        form.whatsapp = member.whatsapp || '';
        form.fb_link = member.fb_link || '';
        currentImage.value = member.image;
    } else {
        isEditing.value = false;
        editingId.value = null;
        Object.keys(form).forEach(key => form[key] = '');
        currentImage.value = null;
    }
    imageFile.value = null;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};

const handleImageUpload = (event) => {
    imageFile.value = event.target.files[0];
};

const submitForm = async () => {
    const formData = new FormData();
    Object.keys(form).forEach(key => formData.append(key, form[key]));
    if (imageFile.value) {
        formData.append('image', imageFile.value);
    }

    try {
        if (isEditing.value) {
            await axios.post(`/api/admin/teams/${editingId.value}`, formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            toast.success('Team member updated!');
        } else {
            await axios.post('/api/admin/teams', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            });
            toast.success('Team member added!');
        }
        closeModal();
        fetchTeams();
    } catch (e) {
        toast.error('Failed to save team member.');
    }
};

const deleteTeam = async (id) => {
    if (!confirm('Are you sure you want to delete this member?')) return;
    try {
        await axios.delete(`/api/admin/teams/${id}`);
        toast.success('Member deleted.');
        fetchTeams();
    } catch (e) {
        toast.error('Failed to delete.');
    }
};

onMounted(() => {
    fetchTeams();
});
</script>
