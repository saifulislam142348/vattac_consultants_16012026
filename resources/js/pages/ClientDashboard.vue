<template>
    <AppLayout>
        <div class="max-w-6xl mx-auto p-6 bg-white shadow-lg rounded-lg my-10 min-h-[500px]">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold font-heading text-brand-dark">Client Portal</h2>
                <div class="flex space-x-4">
                    <button @click="activeTab = 'dashboard'" :class="['px-4 py-2 rounded font-bold transition', activeTab === 'dashboard' ? 'bg-brand-blue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
                        <i class="fas fa-th-large mr-2"></i> My Cases
                    </button>
                    <button @click="activeTab = 'new_request'" :class="['px-4 py-2 rounded font-bold transition', activeTab === 'new_request' ? 'bg-brand-blue text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200']">
                        <i class="fas fa-plus mr-2"></i> New Request
                    </button>
                </div>
            </div>

            <!-- Dashboard Tab -->
            <div v-if="activeTab === 'dashboard'" class="animate-fade-in">
                <div v-if="loading" class="text-center py-10">
                    <i class="fas fa-spinner fa-spin text-4xl text-brand-blue"></i>
                    <p class="mt-2 text-gray-500">Loading your cases...</p>
                </div>

                <div v-else-if="submissions.length === 0" class="text-center py-10 bg-gray-50 rounded-lg border-2 border-dashed">
                    <i class="fas fa-folder-open text-4xl text-gray-300 mb-2"></i>
                    <p class="text-gray-500 mb-4">You haven't submitted any requests yet.</p>
                    <button @click="activeTab = 'new_request'" class="px-5 py-2 bg-brand-blue text-white rounded font-bold hover:bg-blue-700"> Start New Request </button>
                </div>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-100 text-gray-600 uppercase text-xs font-bold">
                            <tr>
                                <th class="p-4 border-b">ID</th>
                                <th class="p-4 border-b">Service</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b">Payment</th>
                                <th class="p-4 border-b">Date</th>
                                <th class="p-4 border-b text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr v-for="sub in submissions" :key="sub.id" class="border-b hover:bg-gray-50">
                                <td class="p-4 font-mono text-gray-500">#{{ sub.id }}</td>
                                <td class="p-4 font-medium capitalize">{{ sub.service_type.replace('_', ' ') }}</td>
                                <td class="p-4">
                                    <span :class="getStatusColor(sub.status)" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                                        {{ sub.status }}
                                    </span>
                                </td>
                                <td class="p-4">
                                     <div class="flex flex-col">
                                        <span :class="getPaymentColor(sub.payment_status)" class="text-xs font-bold uppercase mb-1">
                                            {{ sub.payment_status }}
                                        </span>
                                        <span v-if="sub.service_charge > 0" class="text-xs font-medium text-gray-600">
                                            Invoiced: ৳{{ sub.service_charge }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-4 text-gray-500">{{ new Date(sub.created_at).toLocaleDateString() }}</td>
                                <td class="p-4 text-right">
                                    <button @click="viewDetails(sub)" class="text-brand-blue hover:underline font-medium">View Details</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- New Request Tab -->
            <div v-if="activeTab === 'new_request'" class="animate-fade-in">
                 <SubmissionForm @submitted="handleNewSubmission"/>
            </div>

            <!-- Details Modal -->
             <div v-if="selectedCase" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
                <div class="bg-white rounded-lg shadow-xl p-6 max-w-2xl w-full animate-fade-in-down h-[85vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-xl font-bold font-heading text-brand-dark">Case #{{ selectedCase.id }} Details</h3>
                        <button @click="selectedCase = null" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                    </div>

                    <!-- Status Banner -->
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded mb-6">
                        <div>
                            <p class="text-xs text-gray-500 uppercase font-bold">Current Status</p>
                            <p class="text-lg font-bold capitalize text-brand-blue">{{ selectedCase.status }}</p>
                        </div>
                        <div class="text-right">
                             <p class="text-xs text-gray-500 uppercase font-bold">Submission Date</p>
                             <p class="font-medium">{{ new Date(selectedCase.created_at).toLocaleDateString() }}</p>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    <div v-if="selectedCase.service_charge > 0" class="mb-6 border rounded-lg overflow-hidden">
                        <div class="bg-purple-50 p-3 border-b border-purple-100 flex justify-between items-center">
                            <h4 class="font-bold text-purple-900"><i class="fas fa-file-invoice-dollar mr-2"></i> Invoice & Payment</h4>
                            <span :class="getPaymentColor(selectedCase.payment_status)" class="px-2 py-1 rounded text-xs font-bold uppercase">{{ selectedCase.payment_status }}</span>
                        </div>
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">Service Charge:</span>
                                <span class="text-xl font-bold text-gray-800">৳{{ selectedCase.service_charge }}</span>
                            </div>
                            <div v-if="selectedCase.payment_method" class="flex justify-between items-center">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="font-medium capitalize">{{ selectedCase.payment_method }}</span>
                            </div>
                             <div v-if="selectedCase.payment_status === 'pending'" class="mt-4 p-3 bg-yellow-50 text-yellow-800 text-sm rounded">
                                <i class="fas fa-info-circle mr-1"></i> Please pay the service charge to proceed. Contact admin for payment methods.
                            </div>
                        </div>
                    </div>

                     <!-- Return Work Details (If available) -->
                    <div v-if="selectedCase.vat_month || selectedCase.return_data || selectedCase.nbr_ref_no" class="mb-6 border rounded-lg overflow-hidden">
                        <div class="bg-orange-50 p-3 border-b border-orange-100">
                             <h4 class="font-bold text-orange-900"><i class="fas fa-clipboard-list mr-2"></i> Return Work Details</h4>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                             <div v-if="selectedCase.vat_month">
                                <span class="block text-xs text-gray-500 uppercase">VAT Month</span>
                                <span class="font-medium">{{ selectedCase.vat_month }}</span>
                             </div>
                             <div v-if="selectedCase.submission_date">
                                <span class="block text-xs text-gray-500 uppercase">Submission Date</span>
                                <span class="font-medium">{{ selectedCase.submission_date }}</span>
                             </div>
                             <div v-if="selectedCase.nbr_ref_no">
                                <span class="block text-xs text-gray-500 uppercase">NBR Ref No</span>
                                <span class="font-medium font-mono text-gray-800 bg-gray-100 px-2 py-1 rounded inline-block">{{ selectedCase.nbr_ref_no }}</span>
                             </div>
                             <div v-if="selectedCase.return_data" class="col-span-2 mt-2">
                                <span class="block text-xs text-gray-500 uppercase mb-1">Return Data / Figures</span>
                                <pre class="bg-gray-50 p-3 rounded text-sm font-mono whitespace-pre-wrap">{{ selectedCase.return_data }}</pre>
                             </div>
                        </div>
                    </div>

                    <!-- Admin Reply -->
                    <div v-if="selectedCase.admin_reply" class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-2">Message from Admin</h4>
                        <div class="bg-blue-50 p-4 rounded-lg text-blue-900 border-l-4 border-brand-blue">
                            {{ selectedCase.admin_reply }}
                        </div>
                    </div>

                    <!-- Client Info (Recap) -->
                    <div class="mb-6">
                        <h4 class="font-bold text-gray-800 mb-2 border-b pb-1">Your Submission Info</h4>
                         <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><span class="text-gray-500">Service:</span> <span class="capitalize">{{ selectedCase.service_type.replace('_', ' ') }}</span></div>
                            <div><span class="text-gray-500">Total Income:</span> {{ selectedCase.total_income || 'N/A' }}</div>
                            <div class="col-span-2"><span class="text-gray-500">Message:</span> {{ selectedCase.message || 'None' }}</div>
                        </div>
                    </div>

                    <div class="text-right">
                         <button @click="selectedCase = null" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Close Details</button>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';
import SubmissionForm from './SubmissionForm.vue';

const activeTab = ref('dashboard');
const submissions = ref([]);
const loading = ref(true);
const selectedCase = ref(null);

const fetchSubmissions = async () => {
    loading.value = true;
    try {
        const res = await axios.get('/api/submissions'); // Fetch client's own submissions
        submissions.value = res.data.data;
    } catch (e) {
        console.error("Failed to fetch submissions");
    } finally {
        loading.value = false;
    }
};

const handleNewSubmission = () => {
    activeTab.value = 'dashboard';
    fetchSubmissions();
};

const viewDetails = (sub) => {
    selectedCase.value = sub;
};

const getStatusColor = (status) => {
    switch(status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        case 'processing': return 'bg-blue-100 text-blue-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const missingDocsAlerts = computed(() => {
    return submissions.value
        .filter(sub => sub.missing_documents && sub.missing_documents.length > 0 && sub.status !== 'completed' && sub.status !== 'rejected')
        .map(sub => ({
            id: sub.id,
            service_type: sub.service_type.replace('_', ' '),
            docs: sub.missing_documents
        }));
});

const getPaymentColor = (status) => {
     switch(status) {
        case 'paid': return 'text-green-600';
        case 'pending': return 'text-yellow-600';
        case 'partial': return 'text-blue-600';
        default: return 'text-gray-500';
    }
};

onMounted(() => {
    fetchSubmissions();
});
</script>
