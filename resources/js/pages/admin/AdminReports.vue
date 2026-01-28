<template>
    <AdminLayout>
        <div class="px-6 pb-6 animate-fade-in mt-6">
                <!-- Monthly Trend Chart -->
                <div class="bg-white rounded-lg border p-6 mb-8 shadow-sm">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-brand-blue"></i> Monthly Volume
                </h3>
                <div class="h-64 flex items-end justify-between space-x-2">
                    <template v-if="reportData.monthly.length > 0">
                        <div v-for="(item, index) in reportData.monthly" :key="index" class="flex flex-col items-center flex-1 group relative">
                            <div class="w-full max-w-[40px] bg-brand-blue rounded-t transition-all duration-500 hover:bg-blue-600 relative group-hover:shadow-lg" 
                                    :style="{ height: `${(item.count / maxMonthlyCount) * 100}%` }">
                                    <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10">
                                    {{ item.count }} Submissions
                                    <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 rotate-45 w-2 h-2 bg-gray-800"></div>
                                    </div>
                            </div>
                            <div class="mt-3 text-xs font-bold text-gray-600">{{ getMonthName(item.month) }}</div>
                            <div class="text-[10px] text-gray-400">{{ item.year }}</div>
                        </div>
                    </template>
                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400 italic">
                        No submission data available yet.
                    </div>
                </div>
            </div>

            <!-- Top Clients Table -->
            <div class="bg-white rounded-lg border overflow-hidden shadow-sm">
                <div class="bg-gray-50 px-6 py-4 border-b">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center">
                        <i class="fas fa-trophy mr-2 text-yellow-500"></i> Top Active Clients
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white text-gray-500 text-xs uppercase font-bold border-b">
                            <tr>
                                <th class="p-5">Client Name</th>
                                <th class="p-5">Email</th>
                                <th class="p-5 text-right">Total Submissions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr v-for="(client, idx) in reportData.clients" :key="client.id" class="hover:bg-gray-50 transition">
                                <td class="p-5 font-bold text-gray-800 flex items-center">
                                    <span class="w-6 h-6 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center text-xs mr-3">{{ idx + 1 }}</span>
                                    {{ client.name }}
                                </td>
                                <td class="p-5 text-gray-600">{{ client.email }}</td>
                                <td class="p-5 text-right">
                                    <span class="inline-block bg-blue-100 text-brand-blue px-3 py-1 rounded-full text-xs font-bold">
                                        {{ client.tax_submissions_count }}
                                    </span>
                                </td>
                            </tr>
                            <tr v-if="reportData.clients.length === 0">
                                <td colspan="3" class="p-8 text-center text-gray-500 italic">No client activity found.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { reactive, computed, onMounted } from 'vue';
import axios from 'axios';
import AdminLayout from '../../Layouts/AdminLayout.vue';

const reportData = reactive({
    monthly: [],
    clients: []
});

const maxMonthlyCount = computed(() => {
    if (reportData.monthly.length === 0) return 10; // default max
    return Math.max(...reportData.monthly.map(m => m.count));
});

const getMonthName = (monthNum) => {
    const date = new Date();
    date.setMonth(monthNum - 1);
    return date.toLocaleString('default', { month: 'short' });
};

const fetchReports = async () => {
    try {
        const res = await axios.get('/api/admin/reports');
        reportData.monthly = res.data.monthly;
        reportData.clients = res.data.clients;
    } catch (e) {
        console.error('Failed to fetch reports');
    }
};

onMounted(() => {
    fetchReports();
});
</script>
