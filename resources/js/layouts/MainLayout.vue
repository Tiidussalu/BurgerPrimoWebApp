<template>
  <div class="min-h-screen bg-[#0B0B0B] text-white">
    <!-- Navbar -->
    <Navbar />
    
    <!-- Flash Messages -->
    <div v-if="flash?.success" class="bg-green-600 text-white px-6 py-3 text-center">
      {{ flash.success }}
    </div>
    <div v-if="flash?.error" class="bg-red-600 text-white px-6 py-3 text-center">
      {{ flash.error }}
    </div>

    <!-- Main Content -->
    <main>
      <slot />
    </main>

    <Footer />
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';

const page = usePage();

const flash = computed(() => {
  try {
    return page.props.flash as any;
  } catch {
    return null;
  }
});

const currentYear = new Date().getFullYear();
</script>