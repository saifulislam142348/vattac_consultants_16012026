<template>
    <AdminLayout>
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 flex items-center border-l-4 border-brand-blue">
                <div class="p-3 rounded-full bg-blue-100 text-brand-blue mr-4">
                    <i class="fas fa-file-invoice text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-500 text-sm font-medium">Total Submissions</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ stats.total }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex items-center border-l-4 border-yellow-400">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-500 text-sm font-medium">Pending</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ stats.pending }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex items-center border-l-4 border-green-500">
                <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-500 text-sm font-medium">Completed</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ stats.completed }}</p>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-6 flex items-center border-l-4 border-purple-500">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600 mr-4">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div>
                    <h4 class="text-gray-500 text-sm font-medium">Clients</h4>
                    <p class="text-2xl font-bold text-gray-800">{{ uniqueClients }}</p>
                </div>
            </div>
        </div>

        <!-- Main Content (Submissions Only) -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                
                <!-- Filters/Search -->
                <div class="flex justify-between items-center mb-4">
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <i class="fas fa-search"></i>
                        </span>
                        <input type="text" placeholder="Search clients..." class="pl-10 pr-4 py-2 border rounded-lg focus:ring-1 focus:ring-brand-blue focus:border-brand-blue outline-none w-64">
                    </div>
                    <button @click="fetchSubmissions" class="text-brand-blue hover:text-blue-800 font-medium text-sm">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>

                <div class="overflow-x-auto rounded border border-gray-200">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                            <tr>
                                <th class="p-4 border-b">Client</th>
                                <th class="p-4 border-b">Service</th>
                                <th class="p-4 border-b">Verification</th>
                                <th class="p-4 border-b">Payment</th>
                                <th class="p-4 border-b">Status</th>
                                <th class="p-4 border-b">Date</th>
                                <th class="p-4 border-b text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <tr v-for="sub in submissions" :key="sub.id" class="border-b hover:bg-gray-50 transition">
                                <td class="p-4">
                                    <div class="font-bold text-gray-800">{{ sub.full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ sub.phone_number }}</div>
                                </td>
                                <td class="p-4 capitalize text-gray-700">{{ sub.service_type.replace('_', ' ') }}</td>
                                <td class="p-4 text-xs font-bold">{{ sub.is_phone_verified ? 'Verified' : 'Unverified' }}</td>
                                <td class="p-4">
                                    <div class="flex flex-col">
                                        <span :class="{'text-green-600': sub.payment_status === 'paid', 'text-yellow-600': sub.payment_status === 'pending', 'text-blue-600': sub.payment_status === 'partial'}" class="text-xs font-bold uppercase mb-1">
                                            {{ sub.payment_status }}
                                        </span>
                                        <span v-if="sub.service_charge > 0" class="text-xs font-medium text-gray-600">৳{{ sub.service_charge }}</span>
                                    </div>
                                </td>
                                <td class="p-4">
                                    <select :value="sub.status" @change="updateStatus(sub.id, $event.target.value)" 
                                        :class="getStatusColor(sub.status)"
                                        class="border-none rounded-full px-3 py-1 text-xs font-bold cursor-pointer shadow-sm focus:ring-2 focus:ring-offset-1 focus:ring-brand-blue">
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </td>
                                <td class="p-4 text-gray-600">{{ new Date(sub.created_at).toLocaleDateString() }}</td>
                                <td class="p-4 text-right space-x-2">
                                    <button @click="openPaymentModal(sub)" class="text-purple-600 hover:text-purple-800" title="Manage Payment"><i class="fas fa-file-invoice-dollar"></i></button>
                                    <button @click="openWorkModal(sub)" class="text-orange-600 hover:text-orange-800" title="Process Return"><i class="fas fa-tasks"></i></button>
                                    <button @click="viewDetails(sub)" class="text-brand-blue hover:text-blue-800" title="View Files"><i class="fas fa-folder-open"></i></button>
                                    <button @click="openReplyModal(sub)" class="text-green-600 hover:text-green-800" title="Reply"><i class="fas fa-reply"></i></button>
                                    <button @click="openMissingDocsModal(sub)" class="text-red-500 hover:text-red-700" title="Flag Missing Documents"><i class="fas fa-exclamation-circle"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Files Modal -->
        <div v-if="selectedSubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold font-heading text-brand-dark">Documents</h3>
                    <button @click="selectedSubmission = null" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
                </div>
                <div class="bg-gray-50 rounded p-4 mb-4">
                    <p class="font-bold text-gray-800">{{ selectedSubmission.full_name }}</p>
                    <p class="text-sm text-gray-500">{{ selectedSubmission.service_type.replace('_', ' ') }}</p>
                </div>
                <ul class="space-y-3 mb-6">
                    <li v-for="doc in selectedSubmission.documents" :key="doc.id" class="flex justify-between items-center bg-white border p-3 rounded shadow-sm hover:shadow transition">
                        <div class="flex items-center">
                            <i class="fas fa-file-pdf text-red-500 mr-3 text-lg"></i>
                            <span class="truncate max-w-xs font-medium text-gray-700">{{ doc.file_name }}</span>
                        </div>
                        <a :href="`/storage/${doc.file_path}`" target="_blank" download class="text-brand-blue hover:underline text-sm font-bold bg-blue-50 px-3 py-1 rounded">Download</a>
                    </li>
                    <li v-if="selectedSubmission.documents.length === 0" class="text-gray-500 italic text-center py-4">No files uploaded.</li>
                </ul>
                <div class="text-right">
                    <button @click="selectedSubmission = null" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Close</button>
                </div>
            </div>
        </div>

        <!-- Reply Modal -->
        <div v-if="replySubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down">
                <h3 class="text-xl font-bold mb-4 font-heading text-brand-dark">Reply to Client</h3>
                <div class="bg-blue-50 border-l-4 border-brand-blue p-3 mb-4 rounded-r">
                    <p class="text-sm font-bold text-gray-700">To: {{ replySubmission.full_name }}</p>
                    <p class="text-xs text-gray-500">Phone: {{ replySubmission.phone_number }}</p>
                </div>
                <textarea v-model="replyText" class="w-full p-3 border rounded mb-4 focus:ring-1 focus:ring-brand-blue outline-none" rows="4" placeholder="Type your reply message here..."></textarea>
                <div class="flex justify-end gap-3">
                    <button @click="replySubmission = null" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                    <button @click="sendReply" class="px-5 py-2 bg-brand-blue text-white rounded font-bold hover:bg-blue-700 transition shadow">
                        <i class="fas fa-paper-plane mr-2"></i> Send Reply
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <div v-if="paymentSubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down">
                <h3 class="text-xl font-bold mb-4 font-heading text-brand-dark">Manage Payment</h3>
                <div class="bg-purple-50 border-l-4 border-purple-500 p-3 mb-4 rounded-r">
                    <p class="text-sm font-bold text-gray-700">Client: {{ paymentSubmission.full_name }}</p>
                    <p class="text-xs text-gray-500">Service: {{ paymentSubmission.service_type }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Service Charge (Tk)</label>
                    <input v-model="paymentForm.service_charge" type="number" class="w-full p-2 border rounded focus:ring-1 focus:ring-purple-500 outline-none" placeholder="Enter amount">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Payment Status</label>
                    <select v-model="paymentForm.payment_status" class="w-full p-2 border rounded focus:ring-1 focus:ring-purple-500 outline-none">
                        <option value="pending">Pending</option>
                        <option value="partial">Partial</option>
                        <option value="paid">Paid</option>
                    </select>
                </div>
                
                    <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Payment Method</label>
                    <select v-model="paymentForm.payment_method" class="w-full p-2 border rounded focus:ring-1 focus:ring-purple-500 outline-none">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="bkash">bKash</option>
                        <option value="nagad">Nagad</option>
                        <option value="bank">Bank Transfer</option>
                    </select>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="paymentSubmission = null" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                    <button @click="updatePayment" class="px-5 py-2 bg-purple-600 text-white rounded font-bold hover:bg-purple-700 transition shadow">
                        <i class="fas fa-check mr-2"></i> Update Payment
                    </button>
                </div>
            </div>
        </div>

        <!-- Work Modal -->
        <div v-if="workSubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down h-[80vh] overflow-y-auto">
                <h3 class="text-xl font-bold mb-4 font-heading text-brand-dark">Process Return</h3>
                <div class="bg-orange-50 border-l-4 border-orange-500 p-3 mb-4 rounded-r">
                    <p class="text-sm font-bold text-gray-700">Client: {{ workSubmission.full_name }}</p>
                    <p class="text-xs text-gray-500">Service: {{ workSubmission.service_type }}</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">VAT Month</label>
                    <input v-model="workForm.vat_month" type="month" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Manual Return Data (Entry)</label>
                    <textarea v-model="workForm.return_data" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none font-mono text-sm" rows="4" placeholder="Enter return figures..."></textarea>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Calculation Notes</label>
                    <textarea v-model="workForm.calculation_notes" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none text-sm" rows="3" placeholder="Internal notes..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">Submission Date</label>
                        <input v-model="workForm.submission_date" type="date" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-700 font-bold mb-2">NBR Ref No</label>
                        <input v-model="workForm.nbr_ref_no" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-orange-500 outline-none" placeholder="Ref #">
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <button @click="workSubmission = null" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                    <button @click="updateWorkDetails" class="px-5 py-2 bg-orange-600 text-white rounded font-bold hover:bg-orange-700 transition shadow">
                        <i class="fas fa-save mr-2"></i> Save Details
                    </button>
                </div>
            </div>
        </div>


        <!-- Missing Docs Modal -->
        <div v-if="missingDocsSubmission" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
            <div class="bg-white rounded-lg shadow-xl p-6 max-w-lg w-full animate-fade-in-down">
                <h3 class="text-xl font-bold mb-4 font-heading text-brand-dark">Flag Missing Documents</h3>
                <div class="bg-red-50 border-l-4 border-red-500 p-3 mb-6 rounded-r">
                    <p class="text-sm font-bold text-gray-700">Client: {{ missingDocsSubmission.full_name }}</p>
                    <p class="text-xs text-gray-500">Service: {{ missingDocsSubmission.service_type }}</p>
                </div>

                <div class="mb-4">
                    <p class="text-gray-700 font-bold mb-2 text-sm">Select Missing Documents:</p>
                    <div class="space-y-2 max-h-60 overflow-y-auto border p-2 rounded bg-gray-50">
                        <label v-for="doc in commonDocuments" :key="doc" class="flex items-center space-x-2 cursor-pointer hover:bg-white p-2 rounded transition">
                            <input type="checkbox" :value="doc" v-model="missingDocsList" class="form-checkbox text-red-500 rounded focus:ring-red-500">
                            <span class="text-gray-700 text-sm">{{ doc }}</span>
                        </label>
                    </div>
                </div>

                 <div class="mb-4">
                    <p class="text-gray-700 font-bold mb-2 text-sm">Or Helper Notes:</p>
                     <textarea v-model="missingDocsNote" class="w-full p-2 border rounded text-sm focus:ring-1 focus:ring-red-500 outline-none" placeholder="e.g. Please re-upload clearer copy of NID..."></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button @click="missingDocsSubmission = null" class="px-5 py-2 bg-gray-200 rounded text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                    <button @click="updateMissingDocs" class="px-5 py-2 bg-red-600 text-white rounded font-bold hover:bg-red-700 transition shadow flex items-center">
                        <i class="fas fa-flag mr-2"></i> Update Flags
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
import AdminLayout from '../Layouts/AdminLayout.vue';

const toast = useToast();

const submissions = ref([]);
const selectedSubmission = ref(null);
const replySubmission = ref(null);
const paymentSubmission = ref(null);
const workSubmission = ref(null);
const replyText = ref('');
const paymentForm = reactive({
    service_charge: 0,
    payment_status: 'pending',
    payment_method: ''
});
const workForm = reactive({
    vat_month: '',
    return_data: '',
    calculation_notes: '',
    submission_date: '',
    nbr_ref_no: ''
});

// Missing Docs Logic
const missingDocsSubmission = ref(null);
const missingDocsList = ref([]);
const missingDocsNote = ref('');
const commonDocuments = [
    'Trade License', 'TIN Certificate', 'BIN Certificate', 
    'Bank Statement (Last 6 Months)', 'NID Copy', 
    'Previous VAT Return Copy', 'Import/Export Documents',
    'Mushak 6.3', 'Mushak 9.1'
];

// Stats Computed
const stats = computed(() => {
    return {
        total: submissions.value.length,
        pending: submissions.value.filter(s => s.status === 'pending').length,
        completed: submissions.value.filter(s => s.status === 'completed').length,
    };
});

const uniqueClients = computed(() => {
    const phones = new Set(submissions.value.map(s => s.phone_number));
    return phones.size;
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
    }
};

const updateStatus = async (id, newStatus) => {
    try {
        await axios.patch(`/api/admin/submissions/${id}/status`, { status: newStatus });
        toast.success("Status updated successfully!");
        fetchSubmissions(); 
    } catch (error) {
        toast.error('Failed to update status');
    }
};

const viewDetails = (sub) => {
    selectedSubmission.value = sub;
};

// Reply Logic
const openReplyModal = (sub) => {
    replySubmission.value = sub;
    // Potentially user might want to clear previous reply or edit it. 
    // Here we show existing reply if any.
    replyText.value = sub.admin_reply || '';
};

const sendReply = async () => {
    if (!replyText.value) return;
    try {
        await axios.post(`/api/admin/submissions/${replySubmission.value.id}/reply`, { reply: replyText.value });
        toast.success('Reply sent successfully!');
        replySubmission.value = null;
        fetchSubmissions(); 
    } catch (e) {
        toast.error('Failed to send reply');
    }
};

// Payment Logic
const openPaymentModal = (sub) => {
    paymentSubmission.value = sub;
    paymentForm.service_charge = sub.service_charge || 0;
    paymentForm.payment_status = sub.payment_status || 'pending';
    paymentForm.payment_method = sub.payment_method || '';
};

const updatePayment = async () => {
    try {
        await axios.patch(`/api/admin/submissions/${paymentSubmission.value.id}/payment`, paymentForm);
        toast.success('Payment info updated!');
        paymentSubmission.value = null;
        fetchSubmissions();
    } catch (e) {
        toast.error('Failed to update payment info');
    }
};

// Work Logic
const openWorkModal = (sub) => {
    workSubmission.value = sub;
    workForm.vat_month = sub.vat_month || '';
    workForm.return_data = sub.return_data || '';
    workForm.calculation_notes = sub.calculation_notes || '';
    workForm.submission_date = sub.submission_date || '';
    workForm.nbr_ref_no = sub.nbr_ref_no || '';
};

const updateWorkDetails = async () => {
    try {
        await axios.patch(`/api/admin/submissions/${workSubmission.value.id}/return-details`, workForm);
        toast.success('Return work details saved!');
        workSubmission.value = null;
        fetchSubmissions();
    } catch (e) {
        toast.error('Failed to save details');
    }
};

const openMissingDocsModal = (sub) => {
    missingDocsSubmission.value = sub;
    missingDocsList.value = sub.missing_documents || [];
    missingDocsNote.value = ''; 
};

const updateMissingDocs = async () => {
    if (!missingDocsSubmission.value) return;
    
    const finalDocs = [...missingDocsList.value];
    if (missingDocsNote.value.trim()) {
        finalDocs.push(`Note: ${missingDocsNote.value.trim()}`);
    }

    try {
        await axios.patch(`/api/admin/submissions/${missingDocsSubmission.value.id}/missing-docs`, { 
            missing_documents: finalDocs 
        });
        toast.success('Missing documents flagged!');
        missingDocsSubmission.value = null;
        fetchSubmissions();
    } catch (e) {
        toast.error('Failed to update missing docs');
    }
};

onMounted(() => {
    fetchSubmissions();
});
</script>

