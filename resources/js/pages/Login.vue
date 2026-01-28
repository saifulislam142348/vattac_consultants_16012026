<template>
    <AppLayout>
        <div class="flex items-center justify-center min-h-[calc(100vh-200px)] bg-gray-50">
            <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow-md border-t-4 border-brand-blue">
                <h2 class="text-2xl font-bold text-center font-heading text-brand-dark">Sign In</h2>
                <form @submit.prevent="handleLogin" class="space-y-4">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Email or Username</label>
                        <input v-model="form.login" type="text" class="w-full border p-2 rounded focus:ring-1 focus:ring-brand-blue outline-none" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <input v-model="form.password" type="password" required class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:ring-brand-blue focus:border-brand-blue">
                    </div>
                    <div v-if="authStore.errors.email" class="text-red-500 text-sm">
                        {{ authStore.errors.email[0] }}
                    </div>
                    <button type="submit" class="w-full px-4 py-2 text-white bg-brand-blue rounded hover:bg-blue-800 font-bold transition">
                        Sign In
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import { reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import AppLayout from '../Layouts/AppLayout.vue';

const authStore = useAuthStore();
const router = useRouter();
const form = reactive({
    login: '',
    password: ''
});

const handleLogin = async () => {
    try {
        await authStore.login(form);
    } catch (error) {
        // Error handled in store
    }
};

onMounted(() => {
    if (authStore.isAuthenticated) {
        if (authStore.isAdmin) router.push('/admin/dashboard');
        else router.push('/portal');
    }
});
</script>
