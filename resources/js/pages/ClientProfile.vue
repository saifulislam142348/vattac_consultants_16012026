<template>
    <AppLayout>
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-brand-blue text-white p-6">
                    <h2 class="text-2xl font-bold font-heading">My Profile</h2>
                    <p class="opacity-90">Manage your personal and business information.</p>
                </div>
                
                <form @submit.prevent="updateProfile" class="p-8">
                    <!-- Personal Info -->
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">NID Number</label>
                            <input v-model="form.nid_no" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="Enter NID No">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Passport Number</label>
                            <input v-model="form.passport_no" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="Enter Passport No">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-bold text-gray-700 mb-2">Personal Address</label>
                            <textarea v-model="form.address" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" rows="2" placeholder="Your Home Address"></textarea>
                        </div>
                    </div>

                    <!-- Business Info -->
                    <h3 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2 mt-8">Business Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Business Name</label>
                            <input v-model="form.business_name" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="Company Name" required>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">Business Type</label>
                            <select v-model="form.business_type" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none">
                                <option value="">Select Type</option>
                                <option value="Proprietorship">Proprietorship</option>
                                <option value="Limited Company">Limited Company</option>
                                <option value="Partnership">Partnership</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">BIN Number</label>
                            <input v-model="form.bin_no" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="Business Identification Number">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">TIN Number</label>
                            <input v-model="form.tin_no" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" placeholder="Tax Identification Number">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">VAT Circle</label>
                            <input v-model="form.vat_circle" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none">
                        </div>
                        <div>
                            <label class="block font-bold text-gray-700 mb-2">VAT Zone</label>
                            <input v-model="form.vat_zone" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-bold text-gray-700 mb-2">Business Address</label>
                            <textarea v-model="form.business_address" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blue outline-none" rows="2" placeholder="Office Address"></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4">
                         <router-link to="/portal" class="px-6 py-2 bg-gray-200 text-gray-700 font-bold rounded hover:bg-gray-300 transition">Back to Portal</router-link>
                        <button type="submit" class="bg-brand-blue text-white px-8 py-2 rounded font-bold hover:bg-blue-700 transition shadow">
                            Save Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted, reactive } from 'vue';
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';

const form = reactive({
    nid_no: '',
    passport_no: '',
    address: '',
    business_name: '',
    business_type: '',
    bin_no: '',
    tin_no: '',
    vat_circle: '',
    vat_zone: '',
    business_address: '',
});

const fetchProfile = async () => {
    try {
        const response = await axios.get('/api/profile');
        if (response.data) {
            Object.assign(form, response.data);
        }
    } catch (error) {
        console.error('Failed to fetch profile', error);
    }
};

const updateProfile = async () => {
    try {
        await axios.post('/api/profile', form);
        alert('Profile updated successfully!');
    } catch (error) {
        alert('Failed to update profile');
        console.error(error);
    }
};

onMounted(() => {
    fetchProfile();
});
</script>
