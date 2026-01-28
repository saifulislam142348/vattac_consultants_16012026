<template>
    <AppLayout>
        <HeroSection 
            :title="content.hero_title"
            :subtitle="content.hero_subtitle" 
        />
        <ServicesSection :content="content" />
        
        <!-- About Summary -->
        <section class="py-16">
            <div class="container mx-auto px-4 flex flex-col md:flex-row items-center gap-12">
                <div class="md:w-1/2">
                    <h2 class="text-3xl font-bold text-brand-dark font-heading mb-6">Why Choose Us?</h2>
                    <p class="text-gray-700 mb-4 leading-relaxed">
                        {{ content.about_text || "We are a team of experienced tax consultants dedicated to simplifying your financial compliance. With years of expertise in Bangladesh's tax laws, we ensure you pay exactly what you owe and not a penny more." }}
                    </p>
                    <ul class="space-y-2 mb-6 text-gray-700">
                        <li class="flex items-center"><span class="text-brand-blue mr-2">✔</span> Expert Legal Advice</li>
                        <li class="flex items-center"><span class="text-brand-blue mr-2">✔</span> Timely Submission</li>
                        <li class="flex items-center"><span class="text-brand-blue mr-2">✔</span> Data Confidentiality</li>
                    </ul>
                    <router-link to="/about" class="text-brand-blue font-bold hover:underline">Learn More &rarr;</router-link>
                </div>
                <div class="md:w-1/2 bg-gray-200 h-64 rounded-lg flex items-center justify-center">
                    <span class="text-gray-500">Image Placeholder</span>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="bg-brand-blue text-white py-12 text-center">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold mb-4 font-heading">Ready to File Your Taxes?</h2>
                <p class="mb-8 opacity-90">Get started today with our secure online portal.</p>
                <router-link to="/portal" class="bg-white text-brand-blue px-8 py-3 rounded font-bold hover:bg-gray-100 transition">
                    Submit Documents Now
                </router-link>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';
import HeroSection from '../components/Home/HeroSection.vue';
import ServicesSection from '../components/Home/ServicesSection.vue';

const content = ref({});

const fetchContent = async () => {
    try {
        const res = await axios.get('/api/content/home');
        content.value = res.data;
    } catch (error) {
        console.error('Failed to load content');
    }
};

onMounted(() => {
    fetchContent();
});
</script>
