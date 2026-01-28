<template>
    <div class="flex h-screen bg-gray-100 font-sans">
        <!-- Sidebar -->
        <aside class="w-64 bg-brand-dark text-white flex flex-col shadow-lg transition-all duration-300" :class="{'w-20': collapsed}">
            <div class="p-6 flex items-center justify-between border-b border-gray-700">
                <h1 class="font-bold text-xl tracking-wider" v-if="!collapsed">VATTAC</h1>
                <h1 class="font-bold text-xl tracking-wider" v-else>V</h1>
                <button @click="collapsed = !collapsed" class="text-gray-400 hover:text-white focus:outline-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            
            <nav class="flex-1 px-2 py-4 space-y-2">
                <router-link to="/admin/dashboard" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-list text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Customer List</span>
                </router-link>
                
                <router-link to="/admin/reports" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-chart-bar text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Reports</span>
                </router-link>

                <router-link to="/admin/content" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-edit text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Website Content</span>
                </router-link>

                <router-link to="/admin/team" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-users text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Our Team</span>
                </router-link>

                <router-link to="/admin/work" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-file-signature text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Return Work</span>
                </router-link>

                <router-link to="/admin/documents" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-folder py-1 text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Documents</span>
                </router-link>

                <router-link to="/admin/settings" class="flex items-center px-4 py-3 rounded hover:bg-brand-blue hover:text-white transition text-gray-300" exact-active-class="bg-brand-blue text-white">
                    <i class="fas fa-cog text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Settings</span>
                </router-link>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <button @click="authStore.logout()" class="flex items-center w-full px-4 py-2 text-red-400 hover:text-red-300 transition">
                    <i class="fas fa-sign-out-alt text-lg w-6"></i>
                    <span class="ml-3" v-if="!collapsed">Logout</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm p-4 flex justify-between items-center z-10">
                <h2 class="text-xl font-bold text-gray-800">{{ pageTitle }}</h2>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-brand-blue flex items-center justify-center text-white font-bold">
                            {{ authStore.user?.name?.charAt(0) || 'A' }}
                        </div>
                        <span class="font-medium text-gray-700">{{ authStore.user?.name || 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                <slot></slot>
            </div>
        </main>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRoute } from 'vue-router';

const authStore = useAuthStore();
const collapsed = ref(false);
const route = useRoute();

const pageTitle = computed(() => {
    if (route.path.includes('/admin/reports')) return 'Admin Reports';
    if (route.path.includes('/admin/content')) return 'Website Content';
    if (route.path.includes('/admin/settings')) return 'System Settings';
    if (route.path.includes('/admin/work')) return 'Return Processing';
    if (route.path.includes('/admin/documents')) return 'Document Repository';
    if (route.path.includes('/admin/team')) return 'Manage Team';
    return 'Admin Dashboard';
});
</script>
