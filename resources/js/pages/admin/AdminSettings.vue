<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-lg p-6 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                <i class="fas fa-sliders-h mr-2 text-brand-blue"></i> System Settings
            </h3>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border mb-4">
                <div>
                    <h4 class="font-bold text-gray-700 text-lg">Client OTP Login</h4>
                    <p class="text-sm text-gray-500">Enable or disable OTP verification for client logins.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="settings.client_otp_enabled" @change="updateSettings" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-blue"></div>
                </label>
            </div>

            <!-- Contact Information -->
            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2 flex items-center mt-8">
                <i class="fas fa-address-card mr-2 text-brand-blue"></i> Contact Information
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Company Phone</label>
                    <input v-model="settings.site_phone" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blueOutline" placeholder="+880 1712 ...">
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Company Email</label>
                    <input v-model="settings.site_email" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blueOutline" placeholder="info@example.com">
                </div>
            </div>

            <div class="mb-6">
                 <label class="block text-gray-700 font-bold mb-2">WhatsApp Number (Live Chat)</label>
                 <input v-model="settings.whatsapp_number" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blueOutline" placeholder="+880 17...">
                 <p class="text-xs text-gray-500 mt-1">This number will be used for the floating chat button.</p>
            </div>

            <div class="mb-6">
                 <label class="block text-gray-700 font-bold mb-2">About Us - Main Team Image URL</label>
                 <input v-model="settings.about_image" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blueOutline" placeholder="Full URL to image">
            </div>

             <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Logo URL (Optional)</label>
                <input v-model="settings.site_logo" type="text" class="w-full p-2 border rounded focus:ring-1 focus:ring-brand-blueOutline" placeholder="Full URL to logo image">
                <p class="text-xs text-gray-500 mt-1">Leave empty to use default text logo.</p>
            </div>

            <div class="text-right">
                <button @click="updateSettings" class="px-6 py-2 bg-brand-blue text-white rounded font-bold hover:bg-blue-700 transition">
                    Save Changes
                </button>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import axios from 'axios';
import { useToast } from 'vue-toastification';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const toast = useToast();
const settings = reactive({
    client_otp_enabled: true,
    site_phone: '',
    site_email: '',
    site_logo: '',
    whatsapp_number: '',
    about_image: ''
});

const fetchSettings = async () => {
    try {
        const res = await axios.get('/api/admin/settings');
        // Default to 'true' if the key doesn't exist yet
        settings.client_otp_enabled = res.data.client_otp_enabled !== 'false';
        settings.site_phone = res.data.site_phone || '';
        settings.site_email = res.data.site_email || '';
        settings.site_logo = res.data.site_logo || '';
        settings.whatsapp_number = res.data.whatsapp_number || '';
        settings.about_image = res.data.about_image || '';
    } catch (e) {
        console.error('Failed to fetch settings');
    }
};

const updateSettings = async () => {
    try {
        await axios.post('/api/admin/settings', {
            settings: {
                client_otp_enabled: settings.client_otp_enabled ? 'true' : 'false',
                site_phone: settings.site_phone,
                site_email: settings.site_email,
                site_logo: settings.site_logo,
                whatsapp_number: settings.whatsapp_number,
                about_image: settings.about_image
            }
        });
        toast.success(`Settings updated successfully`);
    } catch (e) {
        toast.error('Failed to update settings');
        // Revert switch on error
        settings.client_otp_enabled = !settings.client_otp_enabled;
    }
};

onMounted(() => {
    fetchSettings();
});
</script>
