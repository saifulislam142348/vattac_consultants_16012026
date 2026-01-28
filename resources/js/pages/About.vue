<template>
    <AppLayout>
        <section class="bg-gray-100 py-16">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold font-heading text-brand-dark mb-8 text-center">About Us</h1>
                
                <div class="flex flex-col md:flex-row gap-12 items-center">
                    <div class="md:w-1/2">
                        <!-- About Image -->
                        <div v-if="aboutImage" class="w-full h-auto rounded-lg overflow-hidden shadow-lg">
                            <img :src="aboutImage" alt="About Us" class="w-full h-full object-cover">
                        </div>
                        <div v-else class="w-full h-64 bg-gray-300 rounded-lg flex items-center justify-center text-gray-500">
                            Team Image Placeholder
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <h2 class="text-2xl font-bold font-heading text-brand-blue mb-4">Who We Are</h2>
                        <p class="text-gray-700 leading-relaxed mb-4">
                            VAT Consultant Bangladesh is a premier firm dedicated to providing comprehensive tax and VAT solutions. 
                            Established with a vision to simplify financial compliance for businesses and individuals, we bring 
                            years of expertise and a commitment to integrity.
                        </p>
                        <p class="text-gray-700 leading-relaxed mb-6">
                            Our team of certified tax consultants, lawyers, and financial analysts work tirelessly to ensure 
                            that you navigate the complex landscape of Bangladesh's tax laws with ease and confidence.
                        </p>
                        
                        <h3 class="text-xl font-bold font-heading text-brand-dark mb-2">Our Mission</h3>
                        <p class="text-gray-700 mb-4">To empower local businesses with accurate financial insights and compliant tax strategies.</p>
                        
                        <h3 class="text-xl font-bold font-heading text-brand-dark mb-2">Our Vision</h3>
                        <p class="text-gray-700">To be the most trusted and sought-after tax consultancy firm in the nation.</p>
                    </div>
                </div>

                <!-- Team Section -->
                <div class="mt-20" v-if="teams.length > 0">
                    <h2 class="text-3xl font-bold font-heading text-brand-dark mb-8 text-center">Our Expert Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="member in teams" :key="member.id" class="bg-white p-6 rounded shadow text-center hover:shadow-lg transition">
                            <div class="w-24 h-24 mx-auto mb-4 rounded-full overflow-hidden border-2 border-brand-blue">
                                <img v-if="member.image" :src="`/storage/${member.image}`" alt="Team" class="w-full h-full object-cover">
                                <div v-else class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-400">
                                    <i class="fas fa-user text-3xl"></i>
                                </div>
                            </div>
                            <h4 class="font-bold text-lg text-gray-800">{{ member.name }}</h4>
                            <p class="text-brand-blue text-sm font-semibold">{{ member.designation }}</p>
                            <p class="text-gray-500 text-xs mt-1">{{ member.education }}</p> 
                            
                            <div class="mt-4 flex justify-center space-x-3">
                                <a v-if="member.fb_link" :href="member.fb_link" target="_blank" class="text-blue-600 hover:text-blue-800 bg-blue-50 p-2 rounded-full w-8 h-8 flex items-center justify-center transition">
                                    <i class="fab fa-facebook-f text-xs"></i>
                                </a>
                                <a v-if="member.whatsapp" :href="`https://wa.me/${member.whatsapp.replace(/\D/g,'')}`" target="_blank" class="text-green-600 hover:text-green-800 bg-green-50 p-2 rounded-full w-8 h-8 flex items-center justify-center transition">
                                    <i class="fab fa-whatsapp text-xs"></i>
                                </a>
                            </div>

                            <div v-if="member.address" class="mt-3 text-xs text-gray-500 flex items-center justify-center gap-1">
                                <i class="fas fa-map-marker-alt"></i> {{ member.address }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '../Layouts/AppLayout.vue';

const teams = ref([]);
const aboutImage = ref('');

const fetchTeams = async () => {
    try {
        const res = await axios.get('/api/teams');
        teams.value = res.data;
    } catch (e) {
        console.error('Failed to fetch teams');
    }
};

const fetchConfig = async () => {
    try {
        const res = await axios.get('/api/system-config');
        if (res.data.about_image) aboutImage.value = res.data.about_image;
    } catch (e) {
        console.error('Failed to fetch settings');
    }
};

onMounted(() => {
    fetchTeams();
    fetchConfig();
});
</script>
