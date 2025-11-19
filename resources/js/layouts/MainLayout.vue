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

    <!-- Footer -->
    <footer class="border-t border-[#121212] mt-16 py-12 bg-[#121212]">
      <div class="max-w-7xl mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
          <!-- About -->
          <div>
            <h3 class="text-[#D2691E] font-bold text-lg mb-4">Burger Primo</h3>
            <p class="text-gray-400 text-sm">
              Parimad burgerid linnas. Värskelt valmistatud igal päeval.
            </p>
          </div>

          <!-- Quick Links -->
          <div>
            <h3 class="text-white font-bold text-lg mb-4">Kiirlingid</h3>
            <ul class="space-y-2">
              <li>
                <Link href="/menu" class="text-gray-400 hover:text-[#D2691E] text-sm transition">
                  Menüü
                </Link>
              </li>
              <li>
                <Link href="/burger-builder" class="text-gray-400 hover:text-[#D2691E] text-sm transition">
                  Ehita burger
                </Link>
              </li>
              <li>
                <Link href="/orders" class="text-gray-400 hover:text-[#D2691E] text-sm transition">
                  Tellimused
                </Link>
              </li>
            </ul>
          </div>

          <!-- Contact -->
          <div>
            <h3 class="text-white font-bold text-lg mb-4">Kontakt</h3>
            <ul class="space-y-2 text-gray-400 text-sm">
              <li>📞 +372 1234 5678</li>
              <li>📧 info@burgerprimo.ee</li>
              <li>📍 Tallinn, Eesti</li>
            </ul>
          </div>

          <!-- Hours -->
          <div>
            <h3 class="text-white font-bold text-lg mb-4">Lahtiolekuajad</h3>
            <ul class="space-y-2 text-gray-400 text-sm">
              <li>E-R: 11:00 - 22:00</li>
              <li>L-P: 12:00 - 23:00</li>
            </ul>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-[#0B0B0B] pt-8 text-center text-gray-400 text-sm">
          <p>&copy; {{ currentYear }} Burger Primo. Kõik õigused kaitstud.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';

const page = usePage();

// Get flash messages safely
const flash = computed(() => {
  try {
    return page.props.flash as any;
  } catch {
    return null;
  }
});

const currentYear = new Date().getFullYear();
</script>