<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                <h2 class="text-xl font-bold font-heading text-brand-dark">VAT & Tax Return Work</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm font-medium text-gray-600">Filter:</span>
                    <select v-model="filterStatus" class="border rounded px-2 py-1 text-sm focus:ring-1 focus:ring-brand-blue outline-none">
                        <option value="all">All Files</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <tr>
                            <th class="p-4 border-b">Client Info</th>
                            <th class="p-4 border-b">Service</th>
                            <th class="p-4 border-b">VAT Month</th>
                            <th class="p-4 border-b">NBR Ref No</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <tr v-for="sub in filteredSubmissions" :key="sub.id" class="border-b hover:bg-gray-50 transition">
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ sub.full_name }}</div>
                                <div class="text-xs text-brand-blue">{{ sub.phone_number }}</div>
                            </td>
                            <td class="p-4 capitalize">{{ sub.service_type.replace('_', ' ') }}</td>
                            <td class="p-4">
                                <span v-if="sub.vat_month" class="bg-blue-50 text-blue-800 px-2 py-1 rounded text-xs font-bold">{{ sub.vat_month }}</span>
                                <span v-else class="text-gray-400 text-xs italic">Not Set</span>
                            </td>
                            <td class="p-4">
                                <span v-if="sub.nbr_ref_no" class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">{{ sub.nbr_ref_no }}</span>
                                <span v-else class="text-gray-400 text-xs italic">Pending</span>
                            </td>
                            <td class="p-4">
                                <span :class="getStatusColor(sub.status)" class="px-2 py-1 rounded-full text-xs font-bold uppercase">{{ sub.status }}</span>
                            </td>
                            <td class="p-4 text-right">
                                <button @click="openWorkModal(sub)" class="flex items-center ml-auto px-3 py-1 bg-orange-100 text-orange-700 rounded hover:bg-orange-200 transition font-bold text-xs border border-orange-200">
                                    <i class="fas fa-file-signature mr-2"></i> Process Return
                                </button>
                            </td>
                        </tr>
                        <tr v-if="filteredSubmissions.length === 0">
                            <td colspan="6" class="p-8 text-center text-gray-500 italic">No submissions found matching filter.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Work Modal -->
        <div v-if="workSubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down h-[85vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold font-heading text-brand-dark">Work File: {{ workSubmission.full_name }}</h3>
                    <button @click="workSubmission = null" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                
                <div class="bg-orange-50 border-l-4 border-orange-500 p-3 mb-6 rounded-r">
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <p><span class="font-bold text-gray-700">Service:</span> {{ workSubmission.service_type }}</p>
                        <p><span class="font-bold text-gray-700">Phone:</span> {{ workSubmission.phone_number }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">VAT Month / Tax Year</label>
                        <input v-model="workForm.vat_month" type="month" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none transition">
                        <p class="text-xs text-gray-500 mt-1">Select the month this return applies to.</p>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Manual Return Data (Entry)</label>
                        <textarea v-model="workForm.return_data" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none font-mono text-sm h-32" placeholder="Turnover: 50,000&#10;VAT: 7,500&#10;Rebate: 2,000..."></textarea>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-bold mb-2 text-sm">Calculation / Internal Notes</label>
                        <textarea v-model="workForm.calculation_notes" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none text-sm h-24 bg-yellow-50" placeholder="Notes for internal staff reference..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-sm">Submission Date</label>
                            <input v-model="workForm.submission_date" type="date" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-sm">NBR Ref No</label>
                            <input v-model="workForm.nbr_ref_no" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none font-mono uppercase" placeholder="e.g. VAT-2026-X">
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t">
                    <button @click="workSubmission = null" class="px-5 py-2 bg-gray-100 rounded text-gray-600 font-bold hover:bg-gray-200 transition">Cancel</button>
                    <button @click="updateWorkDetails" class="px-5 py-2 bg-orange-600 text-white rounded font-bold hover:bg-orange-700 transition shadow flex items-center">
                        <i class="fas fa-save mr-2"></i> Save Details
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, onMounted, reactive, computed } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const toast = useToast();
const submissions = ref([]);
const filterStatus = ref('all');
const workSubmission = ref(null);

const workForm = reactive({
    vat_month: '',
    return_data: '',
    calculation_notes: '',
    submission_date: '',
    nbr_ref_no: ''
});

const getStatusColor = (status) => {
    switch(status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'processing': return 'bg-blue-100 text-blue-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const fetchSubmissions = async () => {
    try {
        const response = await axios.get('/api/admin/submissions');
        submissions.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch submissions', error);
        toast.error('Failed to load data');
    }
};

const filteredSubmissions = computed(() => {
    if (filterStatus.value === 'all') return submissions.value;
    return submissions.value.filter(sub => sub.status === filterStatus.value);
});

const openWorkModal = (sub) => {
    workSubmission.value = sub;
    workForm.vat_month = sub.vat_month || '';
    workForm.return_data = sub.return_data || '';
    workForm.calculation_notes = sub.calculation_notes || '';
    workForm.submission_date = sub.submission_date || '';
    workForm.nbr_ref_no = sub.nbr_ref_no || '';
};

const updateWorkDetails = async () => {
    if (!workSubmission.value) return;
    try {
        await axios.patch(`/api/admin/submissions/${workSubmission.value.id}/return-details`, workForm);
        toast.success('Work details saved successfully!');
        workSubmission.value = null;
        fetchSubmissions(); // Refresh list to show new data
    } catch (e) {
        toast.error('Failed to save details');
        console.error(e);
    }
};

onMounted(() => {
    fetchSubmissions();
});
</script>
