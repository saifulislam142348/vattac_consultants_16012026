<template>
    <AppLayout>
        <div class="p-6 container mx-auto">
            <h1 class="text-3xl font-bold mb-6 font-heading text-brand-dark">Admin Dashboard</h1>
            
            <div class="overflow-x-auto bg-white rounded shadow">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th class="p-4">Client</th>
                            <th class="p-4">Service</th>
                            <th class="p-4">Status</th>
                            <th class="p-4">Date</th>
                            <th class="p-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="sub in submissions" :key="sub.id" class="border-b hover:bg-gray-50">
                            <td class="p-4">
                                <p class="font-bold">{{ sub.full_name }}</p>
                                <p class="text-sm text-gray-500">{{ sub.phone_number }}</p>
                                <p v-if="sub.tin_number" class="text-xs text-brand-blue">TIN: {{ sub.tin_number }}</p>
                            </td>
                            <td class="p-4 capitalize">{{ sub.service_type.replace('_', ' ') }}</td>
                            <td class="p-4">
                                <select :value="sub.status" @change="updateStatus(sub.id, $event.target.value)" 
                                    :class="{'bg-yellow-100 text-yellow-800': sub.status === 'pending', 'bg-blue-100 text-blue-800': sub.status === 'processing', 'bg-green-100 text-green-800': sub.status === 'completed'}"
                                    class="border-none rounded px-2 py-1 text-sm font-semibold cursor-pointer">
                                    <option value="pending">Pending</option>
                                    <option value="processing">Processing</option>
                                    <option value="completed">Completed</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </td>
                            <td class="p-4 text-sm">{{ new Date(sub.created_at).toLocaleDateString() }}</td>
                            <td class="p-4">
                                <button @click="viewDetails(sub)" class="text-brand-blue hover:underline mr-2">View Files</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Files Modal -->
            <div v-if="selectedSubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50">
                <div class="bg-white rounded-lg p-6 max-w-lg w-full">
                    <h3 class="text-xl font-bold mb-4 font-heading">Documents: {{ selectedSubmission.full_name }}</h3>
                    <ul class="space-y-2 mb-4">
                        <li v-for="doc in selectedSubmission.documents" :key="doc.id" class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <span class="truncate max-w-xs">{{ doc.file_name }}</span>
                            <a :href="`/storage/${doc.file_path}`" target="_blank" download class="text-brand-blue hover:underline text-sm font-bold">Download</a>
                        </li>
                        <li v-if="selectedSubmission.documents.length === 0" class="text-gray-500 italic">No files uploaded.</li>
                    </ul>
                    <div class="flex justify-end">
                        <button @click="selectedSubmission = null" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useAuthStore } from '../stores/auth';
import AppLayout from '../Layouts/AppLayout.vue';

const submissions = ref([]);
const selectedSubmission = ref(null);
const authStore = useAuthStore();

const fetchSubmissions = async () => {
    try {
        const response = await axios.get('/api/admin/submissions');
        submissions.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch submissions', error);
    }
};

const updateStatus = async (id, newStatus) => {
    try {
        await axios.patch(`/api/admin/submissions/${id}/status`, { status: newStatus });
        fetchSubmissions(); // Refresh
    } catch (error) {
        alert('Failed to update status');
    }
};

const viewDetails = (sub) => {
    selectedSubmission.value = sub;
};

onMounted(() => {
    fetchSubmissions();
});
</script>
