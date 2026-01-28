<template>
    <AppLayout>
        <section class="py-16">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl font-bold font-heading text-brand-dark mb-12 text-center">Our Services</h1>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service 1 -->
                    <div class="bg-white border rounded-lg p-6 shadow hover:shadow-lg transition">
                        <div class="text-brand-blue text-4xl mb-4"><i class="fas fa-file-invoice-dollar"></i></div>
                        <h3 class="text-xl font-bold mb-3 font-heading">Personal Income Tax</h3>
                        <p class="text-gray-600 mb-4">Comprehensive filing services for individuals, ensuring maximum entitled rebates and full compliance.</p>
                        <router-link to="/portal" class="text-brand-red font-bold hover:underline">File Return &rarr;</router-link>
                    </div>

                    <!-- Service 2 -->
                    <div class="bg-white border rounded-lg p-6 shadow hover:shadow-lg transition">
                        <div class="text-brand-blue text-4xl mb-4"><i class="fas fa-percent"></i></div>
                        <h3 class="text-xl font-bold mb-3 font-heading">Corporate VAT</h3>
                        <p class="text-gray-600 mb-4">Setup, registration, monthly returns (Mushak 9.1), and handling of all VAT-related correspondence.</p>
                        <router-link to="/portal" class="text-brand-red font-bold hover:underline">Get Support &rarr;</router-link>
                    </div>

                    <!-- Service 3 -->
                    <div class="bg-white border rounded-lg p-6 shadow hover:shadow-lg transition">
                        <div class="text-brand-blue text-4xl mb-4"><i class="fas fa-calculator"></i></div>
                        <h3 class="text-xl font-bold mb-3 font-heading">Bookkeeping & Accounting</h3>
                        <p class="text-gray-600 mb-4">Maintain accurate financial records with our professional bookkeeping services tailored for SMEs.</p>
                        <router-link to="/contact" class="text-brand-red font-bold hover:underline">Contact Us &rarr;</router-link>
                    </div>

                    <!-- Service 4 -->
                    <div class="bg-white border rounded-lg p-6 shadow hover:shadow-lg transition">
                        <div class="text-brand-blue text-4xl mb-4"><i class="fas fa-building"></i></div>
                        <h3 class="text-xl font-bold mb-3 font-heading">Company Incorporation</h3>
                        <p class="text-gray-600 mb-4">End-to-end support for RJSC registration, trade license acquisition, and TIN/BIN setup.</p>
                        <router-link to="/contact" class="text-brand-red font-bold hover:underline">Start Business &rarr;</router-link>
                    </div>

                    <!-- Service 5 -->
                    <div class="bg-white border rounded-lg p-6 shadow hover:shadow-lg transition">
                        <div class="text-brand-blue text-4xl mb-4"><i class="fas fa-gavel"></i></div>
                        <h3 class="text-xl font-bold mb-3 font-heading">Legal Consultancy</h3>
                        <p class="text-gray-600 mb-4">Expert legal advice on tax disputes, appeals, and tribunal hearings.</p>
                        <router-link to="/contact" class="text-brand-red font-bold hover:underline">Consult Now &rarr;</router-link>
                    </div>

                     <!-- Service 6 -->
                     <div class="bg-white border rounded-lg p-6 shadow hover:shadow-lg transition">
                        <div class="text-brand-blue text-4xl mb-4"><i class="fas fa-search"></i></div>
                        <h3 class="text-xl font-bold mb-3 font-heading">Internal Audit</h3>
                        <p class="text-gray-600 mb-4">Thorough internal audits to identify financial discrepancies and improve operational efficiency.</p>
                        <router-link to="/contact" class="text-brand-red font-bold hover:underline">Request Audit &rarr;</router-link>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
</script>
