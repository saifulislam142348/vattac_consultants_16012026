<template>
    <section class="relative bg-gray-900 text-white py-20 lg:py-32">
        <!-- Background Overlay -->
        <div class="absolute inset-0 bg-brand-dark opacity-80 z-0"></div>
        <!-- You would typically add an <img> or background-image style here -->
        
        <div class="container mx-auto px-4 relative z-10 text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                <span class="text-white">Expert </span>
                <span class="text-brand-red">VAT & Tax</span>
                <span class="text-white"> Consultant</span>
            </h1>
            <p class="text-lg md:text-xl text-gray-300 mb-10 max-w-2xl mx-auto">
                We provide professional Income Tax, VAT, and Company Affairs solutions in Bangladesh. 
                Secure your financial future with our expert guidance.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <router-link to="/portal" class="bg-brand-red text-white px-8 py-3 rounded font-bold text-lg hover:bg-red-700 transition">
                    File Your Return
                </router-link>
                <router-link to="/services" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded font-bold text-lg hover:bg-white hover:text-brand-dark transition">
                    Our Services
                </router-link>
            </div>
        </div>
    </section>
</template>
