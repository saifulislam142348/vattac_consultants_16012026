<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg my-10">
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
                            <input v-model="form.phone_number" type="text" required class="w-full p-2 border rounded focus:border-brand-blue focus:ring-1 focus:ring-brand-blue outline-none">
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
                        
                        <div class="mt-4 space-y-2">
                            <div v-for="(file, index) in form.documents" :key="index" class="flex items-center justify-between bg-gray-50 p-2 rounded">
                                <span class="text-sm truncate">{{ file.name }}</span>
                                <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700">&times;</button>
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
    </AppLayout>
</template>

<script setup>
import { reactive, ref } from 'vue';
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';

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
    if (currentStep.value === 1 && (!form.full_name || !form.phone_number)) {
        alert('Please fill in required fields.');
        return;
    }
    currentStep.value++;
};

const handleFileSelect = (event) => {
    form.documents.push(...Array.from(event.target.files));
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
                form.documents.forEach(file => formData.append('documents[]', file));
            } else {
                formData.append(key, form[key]);
            }
        });

        await axios.post('/api/submissions', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });

        alert('Submission successful!');
        // Reset form or redirect
    } catch (error) {
        console.error(error);
        alert('Submission failed. Please try again.');
    } finally {
        isSubmitting.value = false;
    }
};
</script>
