<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="text-xl font-bold font-heading text-brand-dark">Document Repository</h2>
                
                <div class="flex items-center space-x-2 w-full md:w-auto">
                    <span class="text-sm font-medium text-gray-600">Category:</span>
                    <select v-model="filterType" @change="fetchDocuments" class="border rounded px-3 py-2 text-sm focus:ring-1 focus:ring-brand-blue outline-none flex-grow md:flex-grow-0">
                        <option value="all">All Documents</option>
                        <option value="nid">NID</option>
                        <option value="tin">TIN Certificate</option>
                        <option value="bin">BIN Certificate</option>
                        <option value="trade_license">Trade License</option>
                        <option value="bank_statement">Bank Statement</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-4 border-b">Document Name</th>
                            <th class="p-4 border-b">Category</th>
                            <th class="p-4 border-b">Client</th>
                            <th class="p-4 border-b">Upload Date</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr v-for="doc in documents" :key="doc.id" class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <div class="flex items-center">
                                    <i class="fas fa-file-pdf text-red-500 text-xl mr-3" v-if="doc.file_path.endsWith('.pdf')"></i>
                                    <i class="fas fa-file-image text-blue-500 text-xl mr-3" v-else></i>
                                    <span class="font-medium text-gray-700 truncate max-w-xs">{{ doc.file_name }}</span>
                                </div>
                            </td>
                            <td class="p-4">
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs font-bold uppercase">{{ doc.doc_type || 'General' }}</span>
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ doc.submission?.user?.name }}</div>
                                <div class="text-xs text-gray-500">{{ doc.submission?.service_type }}</div>
                            </td>
                            <td class="p-4 text-gray-600">{{ new Date(doc.created_at).toLocaleDateString() }}</td>
                            <td class="p-4 text-right">
                                <a :href="`/storage/${doc.file_path}`" target="_blank" download class="inline-flex items-center px-3 py-1 bg-brand-blue text-white rounded hover:bg-blue-700 transition font-bold text-xs shadow-sm">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </td>
                        </tr>
                        <tr v-if="documents.length === 0">
                            <td colspan="5" class="p-8 text-center text-gray-500 italic">No documents found.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const documents = ref([]);
const filterType = ref('all');

const fetchDocuments = async () => {
    try {
        const response = await axios.get('/api/admin/documents', {
            params: { type: filterType.value }
        });
        documents.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch documents', error);
    }
};

onMounted(() => {
    fetchDocuments();
});
</script>
