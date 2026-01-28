<template>
    <AppLayout>
        <section class="bg-gray-100 py-16">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold font-heading text-brand-dark mb-8 text-center">About Us</h1>
                
                <div class="flex flex-col md:flex-row gap-12 items-center">
                    <div class="md:w-1/2">
                        <!-- Placeholder for About Image -->
                        <div class="w-full h-64 bg-gray-300 rounded-lg flex items-center justify-center text-gray-500">
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

                <!-- Team Section (Optional) -->
                <div class="mt-20">
                    <h2 class="text-3xl font-bold font-heading text-brand-dark mb-8 text-center">Our Expert Team</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div v-for="i in 3" :key="i" class="bg-white p-6 rounded shadow text-center">
                            <div class="w-24 h-24 bg-gray-200 rounded-full mx-auto mb-4"></div>
                            <h4 class="font-bold text-lg">Consultant Name</h4>
                            <p class="text-brand-blue text-sm">Senior Tax Advisor</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
</script>
