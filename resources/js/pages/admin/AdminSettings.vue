<template>
    <AdminLayout>
        <div class="bg-white rounded-lg shadow-lg p-6 animate-fade-in">
            <h3 class="text-xl font-bold text-gray-800 mb-6 border-b pb-2 flex items-center">
                <i class="fas fa-sliders-h mr-2 text-brand-blue"></i> System Settings
            </h3>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border">
                <div>
                    <h4 class="font-bold text-gray-700 text-lg">Client OTP Login</h4>
                    <p class="text-sm text-gray-500">Enable or disable OTP verification for client logins.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="settings.client_otp_enabled" @change="updateSettings" class="sr-only peer">
                    <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-brand-blue"></div>
                </label>
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
    client_otp_enabled: true
});

const fetchSettings = async () => {
    try {
        const res = await axios.get('/api/admin/settings');
        // Default to 'true' if the key doesn't exist yet, matching backend AuthController logic
        settings.client_otp_enabled = res.data.client_otp_enabled !== 'false';
    } catch (e) {
        console.error('Failed to fetch settings');
    }
};

const updateSettings = async () => {
    try {
        await axios.post('/api/admin/settings', {
            settings: {
                client_otp_enabled: settings.client_otp_enabled ? 'true' : 'false'
            }
        });
        toast.success(`Client OTP is now ${settings.client_otp_enabled ? 'Enabled' : 'Disabled'}`);
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
