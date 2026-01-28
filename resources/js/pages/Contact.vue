<template>
    <AppLayout>
        <section class="bg-brand-dark text-white py-16">
            <div class="container mx-auto px-4 text-center">
                <h1 class="text-4xl font-bold font-heading mb-4">Contact Us</h1>
                <p class="opacity-80 max-w-2xl mx-auto">Have questions? We are here to help. Reach out to us via phone, email, or visit our office.</p>
            </div>
        </section>

        <section class="py-16">
            <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Contact Info -->
                <div class="space-y-8">
                    <div>
                        <h3 class="text-2xl font-bold font-heading text-brand-dark mb-4">Get In Touch</h3>
                        <p class="text-gray-600">We value your inquiries. Our team is available Sunday to Thursday, 9:00 AM - 6:00 PM.</p>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="text-brand-red text-2xl"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h4 class="font-bold text-lg">Head Office</h4>
                            <p class="text-gray-600">House #12, Road #5, Dhanmondi,<br>Dhaka-1209, Bangladesh.</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="text-brand-red text-2xl"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h4 class="font-bold text-lg">Phone</h4>
                            <p class="text-gray-600">+880 1712 345678</p>
                            <p class="text-gray-600">+880 1912 345678</p>
                        </div>
                    </div>

                    <div class="flex items-start space-x-4">
                        <div class="text-brand-red text-2xl"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h4 class="font-bold text-lg">Email</h4>
                            <p class="text-gray-600">info@vatconsultant.com</p>
                            <p class="text-gray-600">support@vatconsultant.com</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-gray-50 p-8 rounded-lg shadow-sm border">
                    <h3 class="text-2xl font-bold font-heading text-brand-dark mb-6">Send a Message</h3>
                    <form @submit.prevent>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                                <input type="text" class="w-full border rounded p-2 focus:ring-brand-blue focus:border-brand-blue outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email" class="w-full border rounded p-2 focus:ring-brand-blue focus:border-brand-blue outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" class="w-full border rounded p-2 focus:ring-brand-blue focus:border-brand-blue outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea rows="4" class="w-full border rounded p-2 focus:ring-brand-blue focus:border-brand-blue outline-none"></textarea>
                            </div>
                            <button class="w-full bg-brand-blue text-white font-bold py-3 rounded hover:bg-blue-800 transition">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </AppLayout>
</template>

<script setup>
import AppLayout from '../Layouts/AppLayout.vue';
</script>
