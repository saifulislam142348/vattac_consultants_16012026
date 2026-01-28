<template>
    <div class="bg-white rounded-lg">
        <h2 class="text-2xl font-bold mb-6 font-heading text-brand-dark">Tax Submission Form</h2>
        
        <!-- Steps Indicator -->
        <div class="flex mb-8">
            <div v-for="step in 3" :key="step" 
                :class="['w-1/3 text-center py-2 border-b-4 transition-colors', currentStep >= step ? 'border-brand-blue text-brand-blue font-bold' : 'border-gray-200 text-gray-500']">
                Step {{ step }}
            </div>
        </div>

        <form @submit.prevent="submitForm">
            <!-- Step 1: Personal Identity -->
            <div v-if="currentStep === 1" class="space-y-4">
                <h3 class="text-xl font-semibold text-brand-dark">Personal Identity</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Full Name <span class="text-red-500">*</span></label>
                        <input v-model="form.full_name" type="text" required class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Phone Number <span class="text-red-500">*</span></label>
                        <div class="flex gap-2">
                            <input v-model="form.phone_number" type="text" required :disabled="isPhoneVerified && otpEnabled" class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none" placeholder="017...">
                            <button v-if="otpEnabled && !isPhoneVerified && !showOtpInput" type="button" @click="sendOtp" class="bg-gray-800 text-white px-3 py-2 rounded text-sm whitespace-nowrap hover:bg-black">Verify</button>
                            <span v-if="otpEnabled && isPhoneVerified" class="text-green-600 font-bold self-center px-2">Verified</span>
                        </div>
                        <!-- OTP Input -->
                        <div v-if="otpEnabled && showOtpInput && !isPhoneVerified" class="mt-2 flex gap-2">
                            <input v-model="otp" type="text" placeholder="Enter OTP" class="w-24 p-2 border rounded text-center">
                            <button type="button" @click="verifyOtp" class="bg-brand-blue text-white px-3 py-2 rounded text-sm hover:bg-blue-700">Submit OTP</button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">TIN Number</label>
                        <input v-model="form.tin_number" type="text" class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none">
                    </div>
                </div>
            </div>

            <!-- Step 2: Income & Service -->
            <div v-if="currentStep === 2" class="space-y-4">
                <h3 class="text-xl font-semibold text-brand-dark">Income & Service Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Service Type <span class="text-red-500">*</span></label>
                        <select v-model="form.service_type" class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none">
                            <option value="personal_tax">Individual Tax Return</option>
                            <option value="company_vat">Corporate VAT</option>
                            <option value="audit">Audit Support</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium">Total Income</label>
                        <input v-model="form.total_income" type="number" class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium">Message / Instructions</label>
                    <textarea v-model="form.message" class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none" rows="3"></textarea>
                </div>
            </div>

            <!-- Step 3: Documents -->
            <div v-if="currentStep === 3" class="space-y-4">
                <h3 class="text-xl font-semibold text-brand-dark">Document Upload</h3>
                <div class="border-2 border-dashed border-gray-300 p-6 rounded-lg text-center hover:border-brand-blue transition" @dragover.prevent @drop.prevent="handleFileDrop">
                    <p class="text-gray-500 mb-2">Drag & Drop files here or click to select</p>
                    <input type="file" multiple @change="handleFileSelect" class="hidden" ref="fileInput">
                    <button type="button" @click="$refs.fileInput.click()" class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Select Files</button>
                    
                    <div class="mt-6 text-left space-y-3">
                        <div v-for="(doc, index) in form.documents" :key="index" class="bg-gray-50 border p-3 rounded flex flex-col md:flex-row md:items-center gap-3 animate-fade-in">
                            <div class="flex-grow flex items-center overflow-hidden">
                                <i class="fas fa-file text-brand-blue mr-3 text-xl"></i>
                                <div class="truncate">
                                    <p class="text-sm font-bold text-gray-700 truncate">{{ doc.name }}</p>
                                    <p class="text-xs text-gray-500">{{ (doc.file.size / 1024).toFixed(1) }} KB</p>
                                </div>
                            </div>
                            
                            <div class="w-full md:w-48">
                                <select v-model="doc.type" class="w-full text-sm p-2 border rounded bg-white focus:ring-1 focus:ring-brand-blue outline-none cursor-pointer">
                                    <option v-for="type in docTypes" :key="type.value" :value="type.value">
                                        {{ type.label }}
                                    </option>
                                </select>
                            </div>

                            <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700 p-2 rounded hover:bg-red-50 transition">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="mt-8 flex justify-between">
                <button type="button" v-if="currentStep > 1" @click="currentStep--" class="px-6 py-2 bg-gray-300 rounded hover:bg-gray-400">Back</button>
                <div v-else></div> <!-- Spacer -->

                <button type="button" v-if="currentStep < 3" @click="nextStep" class="px-6 py-2 bg-brand-blue text-white rounded hover:bg-blue-700 font-bold">Next</button>
                <button type="submit" v-if="currentStep === 3" class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 font-bold" :disabled="isSubmitting">
                    {{ isSubmitting ? 'Submitting...' : 'Submit' }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import axios from 'axios';

const emit = defineEmits(['submitted']);

const currentStep = ref(1);
const isSubmitting = ref(false);
const fileInput = ref(null);

const form = reactive({
    full_name: '',
    phone_number: '',
    tin_number: '',
    service_type: 'personal_tax',
    total_income: '',
    message: '',
    documents: []
});

const nextStep = () => {
    // Basic validation per step
    if (currentStep.value === 1) {
        if (!form.full_name || !form.phone_number) {
            alert('Please fill in required fields.');
            return;
        }
        if (otpEnabled.value && !isPhoneVerified.value) {
            alert('Please verify your phone number first.');
            return;
        }
    }
    currentStep.value++;
};

const docTypes = [
    { value: 'trade_license', label: 'Trade License' },
    { value: 'tin_certificate', label: 'TIN Certificate' },
    { value: 'bin_certificate', label: 'BIN Certificate' },
    { value: 'nid_copy', label: 'NID Copy' },
    { value: 'bank_statement', label: 'Bank Statement' },
    { value: 'previous_return', label: 'Previous Return Copy' },
    { value: 'import_export', label: 'Import/Export Document' },
    { value: 'other', label: 'Other' }
];

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        form.documents.push({
            file: file,
            name: file.name,
            type: 'other' // Default type
        });
    });
    // Reset input
    if (fileInput.value) fileInput.value.value = '';
};

const handleFileDrop = (event) => {
    const files = Array.from(event.dataTransfer.files);
    files.forEach(file => {
        form.documents.push({
            file: file,
            name: file.name,
            type: 'other'
        });
    });
};

const removeFile = (index) => {
    form.documents.splice(index, 1);
};

const submitForm = async () => {
    isSubmitting.value = true;
    try {
        const formData = new FormData();
        Object.keys(form).forEach(key => {
            if (key === 'documents') {
                form.documents.forEach((doc, index) => {
                    formData.append(`documents[${index}]`, doc.file);
                    formData.append(`doc_types[${index}]`, doc.type);
                });
            } else {
                formData.append(key, form[key]);
            }
        });

        await axios.post('/api/submissions', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        alert('Submission successful!');
        emit('submitted');
        
        // Reset Form
        currentStep.value = 1;
        form.message = '';
        form.documents = [];
        // Don't reset name/phone likely as they might want to submit another
    } catch (error) {
        console.error(error);
        alert('Submission failed. Please try again.');
    } finally {
        isSubmitting.value = false;
    }
};

// OTP Logic
const otp = ref('');
const showOtpInput = ref(false);
const isPhoneVerified = ref(false);

const sendOtp = async () => {
    if (!form.phone_number) return alert('Please enter phone number');
    try {
        const res = await axios.post('/api/otp/send', { phone_number: form.phone_number });
        alert(res.data.message + (res.data.debug_otp ? ` (Code: ${res.data.debug_otp})` : ''));
        showOtpInput.value = true;
    } catch (e) {
        alert('Failed to send OTP');
    }
};

const verifyOtp = async () => {
    try {
        await axios.post('/api/otp/verify', { phone_number: form.phone_number, otp: otp.value });
        isPhoneVerified.value = true;
        showOtpInput.value = false;
        alert('Phone verified successfully');
    } catch (e) {
        alert('Invalid OTP');
    }
};


const otpEnabled = ref(true); // Default to true for safety

const fetchConfig = async () => {
    try {
        const res = await axios.get('/api/system-config');
        otpEnabled.value = res.data.otp_enabled;
    } catch (e) {
        console.error('Failed to fetch config');
    }
};

onMounted(() => {
    fetchConfig();
});
</script>
