<template>
    <div class="flex flex-col min-h-screen">
        <!-- Top Bar -->
        <div class="bg-brand-blue text-white text-sm py-2">
            <div class="container mx-auto px-4 flex justify-between items-center">
                <p>Welcome to VAT & Tax Consultancy Bangladesh</p>
                <div class="flex items-center space-x-4">
                    <span><i class="fas fa-phone mr-1"></i> +880 1712 345678</span>
                    <span><i class="fas fa-envelope mr-1"></i> info@vatconsultant.com</span>
                </div>
            </div>
        </div>

        <!-- Main Header -->
        <header class="bg-white shadow-md sticky top-0 z-50">
            <div class="container mx-auto px-4 py-3 flex justify-between items-center">
                <router-link to="/" class="flex items-center gap-2">
                    <!-- Placeholder Logo -->
                    <div class="w-10 h-10 bg-brand-blue rounded-full flex items-center justify-center text-white font-bold text-xl">V</div>
                    <div class="leading-tight">
                        <h1 class="text-xl font-bold text-brand-dark">VAT CONSULTANT</h1>
                        <span class="text-xs text-brand-red font-semibold tracking-wider">BANGLADESH</span>
                    </div>
                </router-link>

                <!-- Navigation -->
                <nav class="hidden md:flex space-x-6 text-sm font-semibold uppercase text-brand-dark">
                    <router-link to="/" class="hover:text-brand-blue transition">Home</router-link>
                    <router-link to="/about" class="hover:text-brand-blue transition">About Us</router-link>
                    <router-link to="/services" class="hover:text-brand-blue transition">Services</router-link>
                    <router-link to="/portal" class="hover:text-brand-blue transition">Portal</router-link>
                    <router-link to="/contact" class="hover:text-brand-blue transition">Contact</router-link>
                </nav>

                <!-- CTA Button -->
                 <router-link to="/portal" class="bg-brand-red text-white px-5 py-2 rounded font-bold hover:bg-red-700 transition">
                    Get Started
                </router-link>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow">
            <slot></slot>
        </main>

        <!-- Footer -->
        <footer class="bg-brand-blue text-white pt-10 pb-6">
            <div class="container mx-auto px-4 grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Col 1 -->
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">VAT Consultant</h3>
                    <p class="text-sm opacity-90 leading-relaxed">
                        Leading VAT and Tax consultancy firm in Bangladesh. We provide professional services for Income Tax, VAT, and Company Affairs.
                    </p>
                </div>
                 <!-- Col 2 -->
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Quick Links</h3>
                    <ul class="text-sm space-y-2 opacity-90">
                        <li><router-link to="/" class="hover:text-gray-300">Home</router-link></li>
                        <li><router-link to="/about" class="hover:text-gray-300">About Us</router-link></li>
                        <li><router-link to="/services" class="hover:text-gray-300">Our Services</router-link></li>
                        <li><router-link to="/portal" class="hover:text-gray-300">Client Portal</router-link></li>
                    </ul>
                </div>
                 <!-- Col 3 -->
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Services</h3>
                    <ul class="text-sm space-y-2 opacity-90">
                        <li>Individual Tax Return</li>
                        <li>Company VAT Mgmt</li>
                        <li>Audit Assistance</li>
                        <li>IRC & ERC</li>
                    </ul>
                </div>
                 <!-- Col 4 -->
                <div>
                    <h3 class="text-lg font-bold mb-4 font-heading">Contact Us</h3>
                    <div class="text-sm space-y-2 opacity-90">
                        <p>House #12, Road #5, Dhanmondi, Dhaka.</p>
                        <p>Phone: +880 1712 345678</p>
                        <p>Email: info@vatconsultant.com</p>
                    </div>
                </div>
            </div>
            <div class="container mx-auto px-4 mt-8 pt-6 border-t border-blue-400 text-center text-sm opacity-75">
                &copy; {{ new Date().getFullYear() }} VAT Consultant Bangladesh. All Rights Reserved.
            </div>
        </footer>

        
        <!-- WhatsApp Floating Button -->
        <a href="https://wa.me/8801712345678" target="_blank" class="fixed bottom-6 right-6 bg-green-500 text-white rounded-full p-4 shadow-lg hover:bg-green-600 transition z-50 animate-bounce-slow flex items-center gap-2">
            <i class="fab fa-whatsapp text-3xl"></i>
            <span class="font-bold hidden md:inline">Chat with Us</span>
        </a>
    </div>
</template>

<style scoped>
/* Scoped styles if needed */
</style>
