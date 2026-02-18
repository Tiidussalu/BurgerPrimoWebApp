<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import Navbar from '@/components/Navbar.vue'
import HeroSection from '@/components/sections/HeroSection.vue'
import PopularSection from '@/components/sections/PopularSection.vue'
import ContactSection from '@/components/sections/ContactSection.vue'
import EntertainmentSection from '@/components/sections/EntertainmentSection.vue'
import ReviewsSection from '@/components/sections/ReviewsSection.vue'

const cartItems = ref([])
</script>

<template>
  <Head title="Avaleht" />
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <Navbar :cartCount="cartItems.length" />

    <HeroSection />
    <PopularSection />
    <ContactSection />
    <EntertainmentSection />
    <ReviewsSection />

    <footer class="bg-black py-12 border-t border-gray-800">
      <div class="max-w-7xl mx-auto px-6 text-center">
        <h3 class="text-2xl font-bold mb-2">Burger Primo</h3>
        <p class="text-gray-400 mb-1">Kirglikult valmistatud, unkusega serveeritud</p>
        <p class="text-gray-500 text-sm">© 2026 Burger Primo. Kõik õigused kaitstud.</p>
      </div>
    </footer>
  </div>
</template>