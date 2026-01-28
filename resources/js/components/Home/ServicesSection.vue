<template>
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-brand-dark font-heading mb-4">{{ title || 'Our Services' }}</h2>
                <div class="w-20 h-1 bg-brand-blue mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Dynamic Services -->
                <div v-for="(service, index) in servicesList" :key="index" class="bg-white p-6 shadow-md rounded hover:shadow-lg transition text-center border-t-4 border-transparent hover:border-brand-red">
                    <div class="text-brand-blue text-4xl mb-4">
                        <i :class="service.icon"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">{{ service.title }}</h3>
                    <p class="text-sm text-gray-600">{{ service.description }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    content: {
        type: Object,
        default: () => ({})
    }
});

const title = computed(() => props.content?.services_title || 'Our Services');

const servicesList = computed(() => {
    // Default Services
    const defaults = [
        { icon: 'fas fa-file-invoice-dollar', title: 'Income Tax', description: 'Personal and Corporate Income Tax return filing and assessment.' },
        { icon: 'fas fa-percent', title: 'VAT Management', description: 'VAT registration, monthly return, and compliance audit support.' },
        { icon: 'fas fa-search-dollar', title: 'Audit Support', description: 'Prepare financial statements and handle audit queries professionally.' },
        { icon: 'fas fa-building', title: 'Company Affairs', description: 'RJSC registration, trade license, and business incorporation.' }
    ];

    // Merge/Override if dynamic content exists for services
    // For simplicity, we assume if services_json exists, we use it, otherwise defaults.
    // In a real CMS, we might want granular fields like 'service_1_title', etc.
    // But JSON is cleaner for lists.
    
    if (props.content?.services_json) {
        try {
            return JSON.parse(props.content.services_json);
        } catch (e) {
            return defaults;
        }
    }
    return defaults;
});
</script>
